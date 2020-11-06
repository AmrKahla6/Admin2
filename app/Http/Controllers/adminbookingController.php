<?php

namespace App\Http\Controllers;

use App\Cutting;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Carbon\Carbon;
use DB;
use App\notification;
use App\member;
use App\transfer;
use App\item;
use App\order;
use App\order_item;
use App\City;
use App\Booking;
use App\District;
use App\Category;

class adminbookingController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainactive      = 'bookings';
        $subactive       = 'booking';
        $logo            = DB::table('settings')->value('logo');
        $daytotal        = 0;
        $weektotal       = 0;
        $monthtotal      = 0;
        $yeartotal       = 0;
        $mytotal         = 0;
        $nowyear         = Carbon::createFromFormat('Y-m-d H:i:s', now())->year;
        $nowmonth        = Carbon::createFromFormat('Y-m-d H:i:s', now())->month;
        $nowweek         = Carbon::createFromFormat('Y-m-d H:i:s', now())->week;
        $nowday          = Carbon::createFromFormat('Y-m-d H:i:s', now())->day;
        $booking         = Booking::orderBy('id', 'desc')->get();

        return view('admin.booking.index', compact('mainactive', 'subactive', 'logo', 'booking', 'daytotal', 'weektotal', 'monthtotal', 'yeartotal', 'mytotal', 'nowyear', 'nowmonth', 'nowweek', 'nowday'));
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mainactive    = 'bookings';
        $subactive     = 'booking';
        $logo          = DB::table('settings')->value('logo');
        $showbooking   = Booking::findorfail($id);
        $ownerinfo     = DB::table('members')->where('id', $showbooking->user_id)->first();
        $total       = 0;
        return view('admin.booking.show', compact('mainactive', 'subactive', 'logo', 'showbooking', 'ownerinfo'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $upbooking = Booking::find($id);
        if (request()->has('accept')) {
            DB::table('bookings')->where('id', $id)->update(['status' => 1]);
            // dd($upbooking);
            $notification                = new notification();
            $notification->user_id       = $upbooking->user_id;
            $notification->notification  = 'تم قبول الحجز ';
            $notification->save();
            $usertoken = member::where('id', $upbooking->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->value('firebase_token');
            if ($usertoken) {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60 * 20);

                $notificationBuilder = new PayloadNotificationBuilder('قبول الطلب');
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
            session()->flash('success', 'تم قبول الحجز بنجاح');
            return back();
        } elseif (request()->has('reject')) {
            DB::table('orders')->where('id', $id)->update(['status' => 2]);
            $notification                = new notification();
            $notification->user_id       = $upbooking->user_id;
            $notification->notification  = 'تم رفض الحجز ';
            $notification->save();

            $usertoken = member::where('id', $upbooking->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->value('firebase_token');
            if ($usertoken) {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60 * 20);

                $notificationBuilder = new PayloadNotificationBuilder('رفض الحجز');
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
            session()->flash('success', 'تم رفض الحجز بنجاح');
            return back();
        } elseif (request()->has('finish')) {
            DB::table('orders')->where('id', $id)->update(['status' => 3]);
            $notification                = new notification();
            $notification->user_id       = $upbooking->user_id;
            $notification->notification  = 'تم تسليم الطلب ';
            $notification->save();
            $usertoken = member::where('id', $upbooking->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->value('firebase_token');
            if ($usertoken) {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60 * 20);

                $notificationBuilder = new PayloadNotificationBuilder('تسليم الطلب');
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
            session()->flash('success', 'تم تسليم الطلب بنجاح');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);
        // if ($booking) {
        //     Category::where('category_id', $id)->delete();
            $booking->delete();
        // }
        session()->flash('success', 'تم حذف الحجز بنجاح');
        return back();
    }

    public function deleteAll(Request $request)
    {
        $ids            = $request->ids;
        $selectebooking = DB::table("orders")->whereIn('id', explode(",", $ids))->get();
        foreach ($selectebooking as $booking) {
            Category::where('id', $category->id)->delete();
        }
        return response()->json(['success' => "تم الحذف جميع الحجوزات"]);
    }



}
