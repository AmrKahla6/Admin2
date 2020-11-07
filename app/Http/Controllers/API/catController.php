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
use App\order;
use App\member;
use App\setting;
use App\weight;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class catController extends BaseController
{
    public function allcities(Request $request)
    {

        $keyword   = $request->keyword;

        $allcities = City::all();
        if (count($allcities) != 0) {

            return $this->sendResponse('success', $allcities);
        } else {
            $errormessage =  'لا يوجد مدن متاحة';
            return $this->sendError('success', $errormessage);
        }
    }

    public function alldistricts(Request $request)
    {

        $districts = District::where('cities_id', $request->cities_id)->get();
        if ($districts) {
            return $this->sendResponse('success', $districts);
        } else {
            return $this->sendError('success', 'لا توجد أحياء في هذه المدينة');
        }
    }

    public function citydistricts(Request $request)
    {
        $showdis = District::find($request->district_id);
        // dd($showdis);
        if ($showdis) {
            $districtinfo     = array();
            $current          = array();
            if ($request->cities_id) {
                $cities = City::find($request->cities_id);
                $current['cities'] = $cities;
            }
            $current['districtinfo'] = $showdis;
            return $this->sendResponse('success', $current);
        } else {
            $errormessage =  'الصالون غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }

    public function showcat(Request $request)
    {
        $showcat = Category::find($request->category_id);
        // dd($showcat);
        if ($showcat) {
            $catinfo     = array();
            $current      = array();
            $setting = setting::first();
            if ($request->city_id) {
                $districts = District::where('cities_id', $request->city_id)->get();
                $current['districts'] = $districts;
            }
            $current['catinfo'] = $showcat;
            // $current['weights'] = $weights;
            // $current['cuttings'] = $cuttings;

            return $this->sendResponse('success', $current);
        } else {
            $errormessage =  'الصالون غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }
}
