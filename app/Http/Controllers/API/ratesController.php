<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Cutting;
use App\District;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Mail\activationmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Category;
use App\Rate;
use App\member;
use App\setting;
use App\notification;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class ratesController extends BaseController
{
    public function makerate(Request $request)
    {

            $user = member::where('id', $request->user_id)->first();
            // return $user;
            if ($user) {
                $newrate                  = new Rate();
                $newrate->user_id         = $request->user_id;
                $newrate->category_id     = $request->category_id;
                $newrate->rate            = $request->rate;
                $newrate->deliver_id      = $request->deliver_id;
                $newrate->deliver_rate    = $request->deliver_rate;
                $newrate->created_date    = $request->created_date;
                $newrate->created_time    = $request->created_time;
                $newrate->save();
                // dd($newrate);

                $notification                = new notification();
                $notification->user_id       = $request->user_id;
                $notification->notification  = 'تم إنشاء تقيم  جديد';
                $notification->save();
                // dd($notification);

                $usertoken = member::where('id', $request->member_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->first();
                if ($usertoken) {
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder('إنشاء تقيم جديد');
                    $notificationBuilder->setBody('تم إنشاء تقيم جديد')
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

                $errormessage = 'تم ارسال التقيم بنجاح';
                // $msg['booking_number'] = $newrate->order_number;
                $msg['message'] = $errormessage;
                return $this->sendResponse('success', $msg);
            } else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
            return $this->sendError('success', 'عفوا التقيم غير صحيح من فضلك أضف تقيم');
    }

    public function myrate(Request $request)
    {
        $user = member::where('id', $request->user_id)->first();
        if ($user) {

            $myrate = Rate::where('user_id', $request->user_id)->get();
            // dd($myrate);
            $ratedetails = array();

            if (count($myrate) != 0) {
                foreach ($myrate as $rate) {
                        $cat   = Category::where('id',$rate->category_id)->first();
                        $categoryarr  = array();


                        // return $categoryarr;
                        array_push(
                            $ratedetails,
                            array(
                                "id"              => $rate->id,
                                "user_id"         => $rate->user_id,
                                "rate"            => $rate->rate,
                                "deliver_id"      => $rate->deliver_id,
                                "deliver_rate"    => $rate->deliver_rate,
                                "created_date"    => $rate->created_date,
                                "created_time"    => $rate->created_time,
                                "categorys"       => $cat,
                            )
                        );
                        // return $this->sendResponse('success', $ratedetails);

                }
                return $this->sendResponse('success', $ratedetails);
            } else {
                $errormessage = 'لا يوجد تقيمات';
                return $this->sendError('success', $errormessage);
            }
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }


    public function showrates(Request $request)
    {

        $keyword   = $request->keyword;

        $allrates = rate::all();
        if (count($allrates) != 0) {

            return $this->sendResponse('success', $allrates);
        } else {
            $errormessage =  'لا يوجد تعليقات متاحة';
            return $this->sendError('success', $errormessage);
        }
    }


    // public function deleterate(Request $request)
    // {
    //     $user = member::where('id', $request->member_id)->first();
    //     // dd($user);
    //     if ($user) {
    //         // return $user;
    //     $rate = DB::table('rates')->where('member_id', $request->user_id)->where('id', $request->rate_id);
    //     return $rate;
    //     $rate->delete();
    //     $errormessage = 'تم حذف تقيمك';
    //     return $this->sendResponse('success', $errormessage);
    //     }else{
    //         $errormessage = 'المستخدم غير موجود';
    //         return $this->sendError('success', $errormessage);
    //     }
    // }

}
