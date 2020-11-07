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
use App\Comment;
use App\member;
use App\setting;
use App\notification;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class commentsController extends BaseController
{
    public function makecomment(Request $request)
    {

            $user = member::where('id', $request->member_id)->first();
            // return $user;
            if ($user) {
                $newcomment                  = new Comment();
                $newcomment->member_id       = $request->member_id;
                $newcomment->category_id     = $request->category_id;
                $newcomment->comment         = $request->comment;
                $newcomment->created_at      = $request->created_at;
                $newcomment->save();
                // dd($newcomment);

                $notification                = new notification();
                $notification->user_id       = $request->user_id;
                $notification->notification  = 'تم إنشاء تعليق  جديد';
                $notification->save();
                // dd($notification);

                $usertoken = member::where('id', $request->member_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->first();
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

                $errormessage = 'تم ارسال التعليق بنجاح';
                // $msg['booking_number'] = $newcomment->order_number;
                $msg['message'] = $errormessage;
                return $this->sendResponse('success', $msg);
            } else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
            return $this->sendError('success', 'عفوا التعليق غير صحيح من فضلك أضف تعليق');
    }

    public function mycomment(Request $request)
    {
        $user = member::where('id', $request->member_id)->first();
        if ($user) {

            $mycomment = Comment::where('member_id', $request->member_id)->get();
            // dd($mycomment);
            $commentdetails = array();

            if (count($mycomment) != 0) {
                foreach ($mycomment as $comment) {
                        $cat   = Category::where('id',$comment->category_id)->first();
                        $categoryarr  = array();


                        // return $categoryarr;
                        array_push(
                            $commentdetails,
                            array(
                                "id"              => $comment->id,
                                "member_id"       => $comment->member_id,
                                "comment"         => $comment->comment,
                                "created_at"      => $comment->created_at,
                                "categorys"       => $cat,
                            )
                        );
                        // return $this->sendResponse('success', $commentdetails);

                }
                return $this->sendResponse('success', $commentdetails);
            } else {
                $errormessage = 'لا يوجد تعليقات';
                return $this->sendError('success', $errormessage);
            }
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }


    public function showcomments(Request $request)
    {

        $keyword   = $request->keyword;

        $allcomments = Comment::all();
        if (count($allcomments) != 0) {

            return $this->sendResponse('success', $allcomments);
        } else {
            $errormessage =  'لا يوجد تعليقات متاحة';
            return $this->sendError('success', $errormessage);
        }
    }


    public function deletecomment(Request $request)
    {
        $user = member::where('id', $request->member_id)->first();
        // dd($user);
        if ($user) {
        $comment = DB::table('comments')->where('member_id', $request->member_id)->where('id', $request->comment_id);
        $comment->delete();
        $errormessage = 'تم حذف تعليقك';
        return $this->sendResponse('success', $errormessage);
        }else{
            $errormessage = 'المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }

}
