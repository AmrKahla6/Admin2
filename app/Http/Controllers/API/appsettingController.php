<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\setting;
use App\contact;
use App\Cutting;
use App\Category;
use App\member;
use App\City;
use App\District;
use App\notification;
use App\Booking;
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

        $items = Category::orderBy('id', 'desc')->get();

        return $this->sendResponse('success', $items);
    }

    public function districts(Request $request)
    {

        $districts = District::where('cities_id', $request->cities_id)->get();
        if ($districts) {
            return $this->sendResponse('success', $districts);
        } else {
            return $this->sendError('success', 'لا توجد أحياء في هذه المدينة');
        }
    }



}
