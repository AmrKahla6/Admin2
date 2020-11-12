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
use App\Service;
use App\Categoryimages;
use App\Comment;
use App\notification;
use App\Rate;
use App\member;
use App\setting;
use App\weight;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class catController extends BaseController
{
    public function makecat(Request $request)
    {

        $user = member::where('id', $request->user_id)->first();
        if ($user) {

            $validator = Validator::make(
                $request->all(),
                [
                    'name'              => 'required',
                    'des'               => 'required',
                    'lat'               => 'required',
                    'lng'               => 'required',
                    'city_id'           => 'required',
                    'district_id'       => 'required',
                ],
                [
                    'name.required'            => 'هذا الحقل مطلوب',
                    'des.required'             => 'هذا الحقل مطلوب',
                    'lat.required'             => 'هذا الحقل مطلوب',
                    'lng.required'             => 'هذا الحقل مطلوب',
                    'city_id.required'         => 'هذا الحقل مطلوب',
                    'district_id.required'     => 'هذا الحقل مطلوب',

                ]
            );

            if ($validator->fails()) {
                return $this->sendError('success', $validator->errors());
            }

            $newcat   = new Category();
            $newcat->user_id      = $request->user_id;
            $newcat->name         = $request->name;
            $newcat->des          = $request->des;
            $newcat->lat          = $request->lat;
            $newcat->lng          = $request->lng;
            $newcat->city_id      = $request->city_id;
            $newcat->district_id  = $request->district_id;


            $newcat->save();


            if ($request->hasFile('imagesarr')) {
                $images = $request['imagesarr'];

                if($images){
                    // $image = $images;
                    //  return ($images);
                    foreach ($images as $image) {
                        // dd($image);
                        $newimg = new Categoryimages;
                        // dd($newimg);
                        $img_name = rand(55556, 99999) . '.' . $image->getClientOriginalExtension();
                        $image->move(base_path('users/images/'), $img_name);
                        $newimg->image   = $img_name;
                        $newimg->category_id = $newcat->id;
                        $newimg->save();
                    }
                 }
            }

            return $this->sendResponse('success', 'تم إضافة الصالون بنجاح');
        } else {
            $errormessage = 'المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }


    public function upcat(Request $request)
    {

        $upcat = Category::where('id', $request->category_id)->first();
        if ($upcat) {

            $user = member::where('id', $request->user_id)->first();
            if ($user) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'name'              => 'required',
                        'des'               => 'required',
                        'lat'               => 'required',
                        'lng'               => 'required',
                        'city_id'           => 'required',
                        'district_id'       => 'required',
                    ],
                    [
                        'name.required'            => 'هذا الحقل مطلوب',
                        'des.required'             => 'هذا الحقل مطلوب',
                        'lat.required'             => 'هذا الحقل مطلوب',
                        'lng.required'             => 'هذا الحقل مطلوب',
                        'city_id.required'         => 'هذا الحقل مطلوب',
                        'district_id.required'     => 'هذا الحقل مطلوب',

                    ]
                );

                if ($validator->fails()) {
                    return $this->sendError('success', $validator->errors());
                }


                // $upcat->code  = $request->code;
                $upcat->user_id      = $request->user_id;
                $upcat->name         = $request->name;
                $upcat->des          = $request->des;
                $upcat->lat          = $request->lat;
                $upcat->lng          = $request->lng;
                $upcat->city_id      = $request->city_id;
                $upcat->district_id  = $request->district_id;

                $upcat->save();

                //update images

                 if ($request->hasFile('imagesarr')) {
                      $images = $request['imagesarr'];
                if($images){
                    foreach ($images as $image) {
                        $newimg = new Categoryimages;
                        $img_name = rand(0, 999) . '.' . $image->getClientOriginalExtension();
                        $image->move(base_path('users/images/'), $img_name);
                        $newimg->image   = $img_name;
                        $newimg->category_id = $upcat->id;
                        $newimg->save();
                      }
                   }
                }


                return $this->sendResponse('success', 'تم تعديل الصالون بنجاح');
            } else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
        } else {
            $errormessage = 'الصالون غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }




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

        $districts = District::find($request->city_id);

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
            $cominfo     = array();
            $current     = array();

            $setting            = setting::first();
            $images             = Categoryimages::where('category_id', $showcat->id)->get();
            $district           = District::where('id', $showcat->district_id)->first();
            $city               = City::where('id', $showcat->city_id)->first();

            $totalcomments      = Comment::where('category_id', $showcat->id)->count();
            $rate               = Rate::where('category_id', $showcat->id)->get();
            $totalrate          = Rate::where('category_id', $showcat->id)->count();
            $services           = Service::where('category_id', $showcat->id)->get();

            $comments = [];
            foreach(Comment::where('category_id', $showcat->id)->get() as $com){
                $comments[] = ['name' => $com->comment, 'user' => $com->user->name];
            }

            // dd($comments);




            array_push(
                $catinfo,
                array(
                    "id"                   => $showcat->id,
                    'name '                => $showcat->name,
                    "des"                  => $showcat->des,
                    "lat"                  => $showcat->lat,
                    "lng"                  => $showcat->lng,
                    "district"             => $district->name,
                    "city_id"              => $city->id,
                    "city"                 => $city->name_ar,
                    "city"                 => $city->image,
                    'images'               => $images,
                    'services'             => $services,
                    'comments'             => $comments,

                    'totalcomments'        => $totalcomments,
                    'rate'                 => $rate,
                    'totalrate'            => $totalrate,
                )
            );

            $current['catinfo']  = $catinfo;
            // $current['cominfo']  = $cominfo;
            return $this->sendResponse('success', $current);
        } else {
            $errormessage =  'الصالون غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }

      public function delcat(Request $request)
    {
        $delcat = Category::where('id', $request->category_id)->first();
        if ($delcat) {
            Categoryimages::where('category_id', $request->category_id)->delete();
            // favorite_item::where('category_id', $request->category_id)->delete();
            Comment::where('category_id', $request->category_id)->delete();
            Rate::where('category_id', $request->category_id)->delete();
            $delcat->delete();
            return $this->sendResponse('success', 'تم حذف الصالون بنجاح');
        } else {
            return $this->sendError('success', 'هذا الصالون غير موجود');
        }
    }


    public function delimage(Request $request)
    {
        $image = Categoryimages::where('id', $request->image_id)->first();
        if($image){
            $image->delete();
            return $this->sendResponse('success', 'تم حذف الصورة بنجاح');
        }else{
            return $this->sendError('success', 'هذه الصورة غير موجود');
        }
    }
}
