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

    public function showcat(Request $request)
    {
        $showcat = Category::find($request->category_id);
        if ($showcat) {
            $catinfo     = array();
            // $weights     = array();
            // $cuttings     = array();
            $current      = array();

            // $weights = weight::where('cat_id', $showcat->id)->get();
            // $cuttings = Cutting::all();
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
