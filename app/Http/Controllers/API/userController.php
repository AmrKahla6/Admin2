<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\member;
use App\order;
use App\item;
use App\notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
use DB;

class userController extends BaseController
{
    //registeration process
    public function register(Request $request)
    {
        // return $request;
        $validator = Validator::make(
            $request->all(),
            [
                'name'           => 'required',
                'email'          => 'required|unique:members',
                'phone'          => 'required|unique:members',
                'password'       => 'required|min:6',
            ],
            [
                'name.required'         => 'هذا الحقل مطلوب',
                'email.required'        => 'هذا الحقل مطلوب',
                'email.unique'          => 'تم اخذ هذا البريد سابقا',
                'phone.required'        => 'هذا الحقل مطلوب',
                'phone.unique'          => 'تم اخذ هذا الهاتف سابقا',
                'password.required'     => 'هذا الحقل مطلوب',
                'password.min'          => 'كلمة المرور لا تقل عن 6 احرف',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('success', $validator->errors());
        }

        $newmember                 = new member;
        $newmember->name           = $request['name'];
        $newmember->email          = $request['email'];
        $newmember->phone          = $request['phone'];
        $newmember->password       = Hash::make($request['password']);
        // $newmember->firebase_token = $request['firebase_token'];
        // $newmember->suspensed    = 2;

        // $randomcode        = substr(str_shuffle("0123456789"), 0, 6);
        // $newmember->activation_code  = $randomcode;
        $newmember->save();
        $reguser = member::find($newmember->id);

        //remove the first digit (0) from phone number and replace it with saudi  key
        $phone = $newmember->mobile;
        $newphone = "+966" . substr($phone, 1);



        //set POST variables
                $url = 'https://www.hisms.ws/api.php/send_sms';
                $fields_string = '';
                $fields = array(
                    'username' => urlencode('966559965344'),
                    'password' => urlencode('Aa12345678'),
                    'numbers'  => urlencode($newphone),
                    'sender'   => urlencode('albalad'),
                    'message'  => urlencode($newmember->activation_code.'كود التفعيل هو   '),
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



        return $this->sendResponse('success', $reguser);
    }

    //account activation process
    public function account_activation(Request $request)
    {
        $user = member::where('id',$request->user_id)->where('activation_code',$request->activation_code)->first();
        if($user){
            $user->suspensed = 0;
            $user->save();

        $notification                = new notification();
        $notification->user_id       = $user->id;
        $notification->notification  = 'تم تفعيل حسابك بنجاح';
        $notification->save();
            return $this->sendResponse('success', 'تم تفعيل الحساب بنجاح');
        }else{
            return $this->sendError('success', 'هذا الكود غير صحيح');
        }
    }

    // resending activation code process
    public function resend_activation_code(Request $request)
    {
        $user = member::where('id',$request->user_id)->first();
        if($user){

        $randomcode        = substr(str_shuffle("0123456789"), 0, 6);
        $user->activation_code  = $randomcode;
        $user->save();

        //remove the first digit (0) from phone number and replace it with saudi  key
        $phone = $user->mobile;
        $newphone = "+966" . substr($phone, 1);

        //set POST variables
                $url = 'https://www.hisms.ws/api.php/send_sms';
                $fields_string = '';
                $fields = array(
                    'username' => urlencode('966559965344'),
                    'password' => urlencode('Aa12345678'),
                    'numbers'  => urlencode($newphone),
                    'sender'   => urlencode('albalad'),
                    'message'  => urlencode($user->activation_code.'كود التفعيل هو   '),
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



            return $this->sendResponse('success', 'تم إعادة إرسال كود التفعيل  ');
        }else{
            return $this->sendError('success', 'هذا المستخدم غير موجود');
        }
    }

    //Login process
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'          => 'required',
            'password'       => 'required',
        ], [
            'email.required' => 'هذا الحقل مطلوب',
        ]);


        if ($validator->fails()) {
            return $this->sendError('success', $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user                 = Auth::user();
            $user->firebase_token = $request->firebase_token;
            $user->save();
            return $this->sendResponse('success', $user);
        } else {
            $errormessage = ' البريد الالكتروني أو كلمة المرور غير صحيحة';
            return $this->sendError('success', $errormessage);
        }
    }

 //forgetpassword process
 public function forgetpassword(Request $request)
 {
     $user = member::where('phone', $request->phone)->first();
     if (!$user) {
         $errormessage = ' الهاتف غير صحيح';
         return $this->sendError('success', $errormessage);
     } else {
         $randomcode        = substr(str_shuffle("0123456789"), 0, 4);
         $user->forgetcode  = $randomcode;
         $user->save();

         return $this->sendResponse('success', $user->forgetcode);
     }
 }

    public function activcode(Request $request)
    {
        $user = member::where('phone', $request->phone)->where('forgetcode', $request->forgetcode)->first();
        if ($user) {
            return $this->sendResponse('success', 'true');
        } else {
            $errormessage = ' الكود غير صحيح';
            return $this->sendError('success', $errormessage);
        }
    }

    //rechangepassword process
    public function rechangepass(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'new_password'    => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('success', $validator->errors());
        }

        $user = member::where('phone', $request->phone)->first();
        if ($user) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            $errormessage = 'تم تغيير كلمة المرور بنجاح';
            return $this->sendResponse('success', $errormessage);
        } else {
            $errormessage = 'هذا الهاتف غير صحيح';
            return $this->sendError('success', $errormessage);
        }
    }

    //profile process
    public function profile(Request $request)
    {
        $user = member::where('id', $request->user_id)->where('suspensed', 0)->first();
        if (!$user) {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        } else {
            return $this->sendResponse('success', $user);
        }
    }

    //updating profile process
    public function update(Request $request)
    {
        $upuser = member::where('id', $request->user_id)->first();
        if ($upuser) {
            $validator = Validator::make($request->all(), [
                'name'        => 'required',
                'email'       => 'required|unique:members,email,' . $upuser->id,
                'phone'       => 'required|unique:members,phone,' . $upuser->id,

            ]);

            if ($validator->fails()) {
                return $this->sendError('success', $validator->errors());
            }

            $upuser->name      = $request['name'];
            $upuser->email     = $request['email'];
            $upuser->phone     = $request['phone'];
            $upuser->password  = $request['password'] ? Hash::make($request['password']) : $upuser->password;
            $upuser->save();
            return $this->sendResponse('success', $upuser);
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }

    public function mynotification(Request $request)
    {
        DB::table('notifications')->where('user_id', $request->user_id);
        $mynotifs = notification::where('user_id', $request->user_id)->orderBy('id', 'desc')->get();
        if (count($mynotifs) != 0) {
            return $this->sendResponse('success', $mynotifs);
        } else {
            $errormessage = 'لا يوجد تنبيهات';
            return $this->sendError('success', $errormessage);
        }
    }

    public function deletenotification(Request $request)
    {
        $notification = DB::table('notifications')->where('user_id', $request->user_id)->where('id', $request->notification_id);
        $notification->delete();
        $errormessage = 'تم حذف الاشعار';
        return $this->sendResponse('success', $errormessage);
    }



    public function updatefirebasebyid(Request $request)
    {
        $user = member::where('id', $request->user_id)->first();
        if ($user) {
            $user->firebase_token = Hash::make($request->firebase_token);
            $user->save();
            $errormessage = 'تم التحديث';
            return $this->sendResponse('success', $errormessage);
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }
}
