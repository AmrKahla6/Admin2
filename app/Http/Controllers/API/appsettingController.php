<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\setting;
use App\contact;
use App\Categoryimages;
use App\Category;
use App\member;
use App\City;
use App\slider;
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
        $topsliders      = array();
        $maincategories  = array();
        $cities          = array();
        $current         = array();

        //main categories
        $categories = Category::orderBy('id', 'desc')->get();
        foreach ($categories as $category) {
            $image     = Categoryimages::where('category_id', $category->id)->first();
            $city      = DB::table('cities')->where('id',$category->city_id)->first();

            // dd($city);
            if($image){
                $image_name = $image->image ;
            }else{
                $image_name= null;
            }
            array_push(
                $maincategories,
                array(
                    "id"      => $category->id,
                    "name"    => $category->name,
                    "des"     => $category->des,
                    'image'   => $image_name,
                    'city'    => $city->name_ar,
                )
            );
        }

        $allcities = City::orderBy('id', 'desc')->get();

        foreach ($allcities as $city) {
            array_push($cities, array(
                "id"  => $city->id,
                "name" => $city->name_ar,
                "imge" => $city->image,
            ));
        }

        // $current['topsliders']     = $topsliders;
        $current['categories']     = $maincategories;
        $current['cities']         = $cities;
        return $this->sendResponse('success', $current);
    }

    public function contactus(Request $request)
    {
        $newcontact          = new contact();
        $newcontact->name    = $request->name;
        $newcontact->message = $request->message;
        $newcontact->email   = $request->email ;
        $newcontact->save();
        $errormessage =  'تم ارسال الرسالة بنجاح';
        return $this->sendResponse('success', $errormessage);
    }




}
