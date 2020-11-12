<?php

namespace App\Http\Controllers\API;

use App\Cutting;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\member;
use App\Category;
use App\Service;
use App\notification;
use App\order;
use App\Booking;
use App\weight;
use App\City;
use Carbon\Carbon;
use DB;


class bookingController extends BaseController
{

    public function makebooking(Request $request)
    {

            $user = member::where('id', $request->user_id)->first();
            // return $user;
            if ($user) {
                $newbooking                  = new Booking();
                $newbooking->booking_number  = $request->booking_number;
                $newbooking->user_id         = $request->user_id;
                $newbooking->category_id     = $request->category_id;
                $newbooking->service_id      = $request->service_id;
                $newbooking->status          = $request->status;
                $newbooking->paid            = $request->paid;
                $newbooking->name            = $request->name;
                $newbooking->phone           = $request->phone;
                $newbooking->total           = $request->total;
                // $newbooking->city_id         = $request->city_id;
                $newbooking->created_at      = $request->created_at;
                $newbooking->save();
                // dd($newbooking);

                $notification                = new notification();
                $notification->user_id       = $request->user_id;
                $notification->notification  = 'تم إنشاء حجز  جديد';
                $notification->save();
                // dd($notification);

                $usertoken = member::where('id', $request->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->first();
                if ($usertoken) {
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder('إنشاء حجز جديد');
                    $notificationBuilder->setBody('تم إنشاء حجز جديد')
                        ->setSound('default');

                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['a_type' => 'message']);
                    $option       = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data         = $dataBuilder->build();
                    $token        = $user->firebase_token;

                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                    $downstreamResponse->tokensToDelete();
                    $downstreamResponse->tokensToModify();
                    $downstreamResponse->tokensToRetry();

                }



                //set POST variables
                $url = 'https://www.hisms.ws/api.php/send_sms';
                $fields_string = '';
                $fields = array(
                    'username' => urlencode('966559965344'),
                    'password' => urlencode('Aa12345678'),
                    'numbers'  => urlencode('+966 55 596 5587'),
                    'sender'   => urlencode('albalad'),
                    'message'  => urlencode($user->name. ' تم استلام حجز جديد من العميل '),
                    'send_sms' => urlencode(''),
                );

                //url-ify the data for the POST
                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                rtrim($fields_string, '&');

                //open connection
                $ch = curl_init();

                //set the url, number of POST vars, POST data
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_POST, count($fields));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                //execute post
                $result = curl_exec($ch);

                //close connection
                curl_close($ch);

                $errormessage = 'تم ارسال الحجز بنجاح';
                // $msg['booking_number'] = $newbooking->order_number;
                $msg['message'] = $errormessage;
                return $this->sendResponse('success', $msg);
            } else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
            return $this->sendError('success', 'عفوا الحجز غير صحيح من فضلك أضف حجز');
    }

    public function mybooking(Request $request)
    {
        $user = member::where('id', $request->user_id)->first();
        if ($user) {

            $mybooking = Booking::where('user_id', $request->user_id)->get();
            // dd($mybooking);
            $orderdetails = array();

            if (count($mybooking) != 0) {
                foreach ($mybooking as $showbook) {
                        $cat   = Category::where('id',$showbook->category_id)->first();
                        $serv  = Service::where('id',$showbook->service_id)->first();
                        $categoryarr  = array();
                        $servarr      = array();


                        // return $categoryarr;
                        array_push(
                            $orderdetails,
                            array(
                                "id"              => $showbook->id,
                                "booking_number"  => $showbook->booking_number,
                                "user_id"         => $showbook->user_id,
                                "user_name"       => $showbook->name,
                                "phone"           => $showbook->phone,
                                "total"           => $showbook->total,
                                "status"          => $showbook->status,
                                "paid"            => $showbook->paid,
                                "created_at"      => $showbook->created_at,
                                "categorys"       => $cat,
                                "servarr"         => $serv,
                            )
                        );
                        // return $this->sendResponse('success', $orderdetails);

                }
                return $this->sendResponse('success', $orderdetails);
            } else {
                $errormessage = 'لا يوجد حجوزات';
                return $this->sendError('success', $errormessage);
            }
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }


    public function showbooking(Request $request)
    {

        $keyword   = $request->keyword;

        $allbooking = Booking::all();
        if (count($allbooking) != 0) {

            return $this->sendResponse('success', $allbooking);
        } else {
            $errormessage =  'لا يوجد صالونات متاحة';
            return $this->sendError('success', $errormessage);
        }
    }


   public function upbook(Request $request)
   {
    $upbook = Booking::where('id', $request->booking_id)->first();

    if($upbook)
    {
        $user = member::where('id', $request->user_id)->first();
        // dd($user);
        if ($user) {
            $upbook->name            = $request->name;
            $upbook->phone           = $request->phone;
            $upbook->booking_number  = $request->booking_number;
            $upbook->total           = $request->total;
            $upbook->status          = $request->status;
            $upbook->paid            = $request->paid;
            $upbook->user_id         = $request->user_id;
            $upbook->category_id     = $request->category_id;
            $upbook->service_id      = $request->service_id;

            $upbook->save();

            return $this->sendResponse('success', 'تم تعديل الحجز بنجاح');
         }else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
        }else {
        $errormessage = 'المنتج غير موجود';
        return $this->sendError('success', $errormessage);
    }
   }


   public function delbooking(Request $request)
   {
       $delbook = Booking::where('id', $request->booking_id)->first();
       if ($delbook) {
        //    Categoryimages::where('category_id', $request->category_id)->delete();
           // favorite_item::where('category_id', $request->category_id)->delete();
        //    Comment::where('category_id', $request->category_id)->delete();
        //    Rate::where('category_id', $request->category_id)->delete();
           $delbook->delete();
           return $this->sendResponse('success', 'تم حذف الحجز بنجاح');
       } else {
           return $this->sendError('success', 'هذا الحجز غير موجود');
       }
   }


    public function bookings(Request $request)
    {
        $lastbooking = array();

        $bookings = Booking::where('id', $request->booking_id)->get();
        if (count($bookings) > 0) {

            foreach($bookings as $booking)
            {
                $user     = member::where('id', $booking->user_id)->first();
                $category = Category::where('id', $booking->category_id)->first();
                $servce   = Service::where('id', $booking->service_id)->first();
                $humantime  = 'منذ'.Carbon::createFromTimeStamp(strtotime($booking->created_at))->diffForHumans();
                 $arhumantime = str_replace(
                    array('0','1','2','3','4','5','6','7','8','9'),
                    array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'),
                    $humantime
                    );
                    array_push(
                        $lastbooking,
                        array(
                            "id"                     => $booking->id,
                            'name'                   => $booking->name,
                            'phone'                  => $booking->phone,
                            'booking Number'         => $booking->booking_number,
                            'total'                  => $booking->total,
                            'status'                 => $booking->status,
                            'paid'                   => $booking->paid,
                            'user_id'                => $booking->user_id,
                            'category_id'            => $booking->category_id,
                            'category name'          => $category->name,
                            'service_id'             => $booking->service_id,
                            'service name'           => $servce->name,
                            'price'                  => $servce->price,
                            'created_at'             => $humantime,
                        )
                    );
            }
            return $this->sendResponse('success', $lastbooking);
        } else {
            $errormessage =  'لا يوجد صالونات متاحة';
            return $this->sendError('success', $errormessage);
        }
    }




}



