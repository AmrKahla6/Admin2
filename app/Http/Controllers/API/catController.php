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
use App\Categoryimages;
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

        $districts = District::find($request->districts_id);

        if ($districts) {
            $disinfo     = array();
            $city      = City::where('id', $districts->cities_id)->first();
            // dd($city);
            array_push(
                $disinfo,
                array(
                    "city_id"         => $city->id,
                    "city"            => $city->name_ar,
                    "id"              => $districts->id,
                    'name '           => $districts->name,
                )
            );

            return $this->sendResponse('success', $disinfo);
        } else {
            return $this->sendError('success', 'لا توجد أحياء في هذه المدينة');
        }
    }

    // public function citydistricts(Request $request)
    // {
    //     // $showdis = District::get();
    //     // dd($showdis);
    //     // if ($showdis) {
    //     //     $districtinfo     = array();
    //     //     $current          = array();
    //     //     if ($request->cities_id) {
    //     //         $cities = City::where($request->cities_id);
    //     //         $current['cities'] = $cities;
    //     //     }
    //     //     $current['districtinfo'] = $showdis;
    //     //     return $this->sendResponse('success', $current);
    //     // } else {
    //     //     $errormessage =  'الصالون غير موجود';
    //     //     return $this->sendError('success', $errormessage);
    //     // }
    // }

    public function showcat(Request $request)
    {
        $showcat = Category::find($request->category_id);
        // dd($showcat);
        if ($showcat) {
            $catinfo     = array();
            $current      = array();

            $setting   = setting::first();
            $images    = Categoryimages::where('category_id', $showcat->id)->get();
            $district  = District::where('id', $showcat->district_id)->first();
            $city      = City::where('id', $showcat->city_id)->first();

            array_push(
                $catinfo,
                array(
                    "id"              => $showcat->id,
                    'name '           => $showcat->name,
                    "des"             => $showcat->des,
                    "lat"             => $showcat->lat,
                    "lng"             => $showcat->lng,
                    "district"        => $district->name,
                    "city_id"         => $city->id,
                    "city"            => $city->name_ar,
                    'images'          => $images,
                )
            );

            return $this->sendResponse('success', $catinfo);
        } else {
            $errormessage =  'الصالون غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }
}
