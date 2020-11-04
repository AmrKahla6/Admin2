<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\setting;
use App\contact;
use App\Cutting;
use App\item;
use App\member;
use App\City;
use App\District;
use App\notification;
use App\transfer;
use App\weight;
use Settings;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;

class appsettingController  extends BaseController
{
    public function settingindex(Request $request)
    {

        $jsonarr              = array();
        $setting              = setting::all();
        $jsonarr['info']      = $setting;
        return $this->sendResponse('success', $jsonarr);
    }

    public function home(Request $request)
    {

        $items = item::where('suspensed', 0)->orderBy('id', 'desc')->get();

        return $this->sendResponse('success', $items);
    }

    public function districts(Request $request)
    {

        $districts = District::where('cities_id', $request->city_id)->get();
        if ($districts) {
            return $this->sendResponse('success', $districts);
        } else {
            return $this->sendError('success', 'لا توجد أحياء في هذه المدينة');
        }
    }


    public function addtransfer(Request $request)
    {
        $newtransfer                = new transfer();
        $newtransfer->name          = $request->name;
        $newtransfer->phone         = $request->phone;
        $newtransfer->bank_name         = $request->bank_name;
        $info =  DB::table('orders')->where('order_number', $request->bill_number)->first();

        if ($info) {
            $newtransfer->bill_number   = $request->bill_number;
        }

        if ($request->hasFile('image')) {
            $image    = $request['image'];
            $filename = rand(0, 9999) . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('users/images/'), $filename);
            $newtransfer->image = $filename;
        }
        $newtransfer->save();

        $notification                = new notification();
        $notification->user_id       = $info->user_id;
        $notification->notification  = 'تم انشاء طلب جديد ';
        $notification->save();
        $usertoken = member::where('id', $request->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->value('firebase_token');
        if ($usertoken) {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder('إنشاء طلب جديد');
            $notificationBuilder->setBody($request->notification)
                ->setSound('default');

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['a_type' => 'message']);
            $option       = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data         = $dataBuilder->build();
            $token        = $usertoken;

            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            $downstreamResponse->tokensToDelete();
            $downstreamResponse->tokensToModify();
            $downstreamResponse->tokensToRetry();
        }
        $errormessage = 'تم ارسال التحويل بنجاح';
        return $this->sendResponse('success', $errormessage);
    }
}