<?php

namespace App\Http\Controllers\API;

use App\Cutting;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\member;
use App\item;
use App\notification;
use App\order_item;
use App\order;
use App\weight;
use App\City;
use Carbon\Carbon;
use DB;


class orderController extends BaseController
{

    public function makeorder(Request $request)
    {
        if ($request->total != 0) {
            $user = member::where('id', $request->user_id)->first();
            if ($user) {
                $neworder                = new order();
                $neworder->order_number  = date('dmY') . rand(0, 999);
                $neworder->user_id       = $request->user_id;
                $neworder->total         = $request->total;
                $neworder->paid          = $request->paid;
                $neworder->name          = $request->name;
                $neworder->phone          = $request->phone;
                $neworder->city_id          = $request->city_id;
                $neworder->mobile          = $request->mobile;
                $neworder->deliver          = $request->deliver;
                $neworder->date          = $request->date;
                $neworder->time          = $request->time;
                $neworder->lat          = $request->lat;
                $neworder->lng          = $request->lng;
                $neworder->notes   = $request->notes;
                $neworder->save();

                $orderarr  = $request->orderarr;
                $new_array = json_decode($orderarr, true);
                foreach ($new_array as $arr) {
                    $neworderitem = new order_item();
                    $neworderitem->order_id = $neworder->id;
                    $neworderitem->item_id = $arr['item_id'];
                    $neworderitem->qty     = $arr['qty'];
                    $neworderitem->price   = $arr['price'];
                    $neworderitem->cutting_id   = $arr['cutting_id'];
                    if ($arr['weight_id']) {
                        $neworderitem->weight_id   = $arr['weight_id'];
                    }


                    $neworderitem->skin   = $arr['skin'];
                    $neworderitem->packaging   = $arr['packaging'];
                    $neworderitem->minced   = $arr['minced'];
                    $neworderitem->bowels   = $arr['bowels'];
                    $neworderitem->moqalqal   = $arr['moqalqal'];
                    $neworderitem->save();
                }
                $notification                = new notification;
                $notification->user_id       = $neworder->user_id;
                $notification->notification  = 'تم إنشاء طلب جديد بنجاح';
                $notification->save();

                $user = member::where('id', $request->user_id)->where('firebase_token', '!=', null)->where('firebase_token', '!=', 0)->first();
                if ($usertoken) {
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder('إنشاء طلب جديد');
                    $notificationBuilder->setBody('تم إنشاء طلب جديد')
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
                    'message'  => urlencode($user->name. ' تم استلام طلب جديد من العميل '),
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
                
                $errormessage = 'تم ارسال الطلب بنجاح';
                $msg['bill_number'] = $neworder->order_number;
                $msg['message'] = $errormessage;
                return $this->sendResponse('success', $msg);
            } else {
                $errormessage = 'المستخدم غير موجود';
                return $this->sendError('success', $errormessage);
            }
        } else {
            return $this->sendError('success', 'عفوا الطلب غير صحيح من فضلك أضف منتجات');
        }
    }

    public function myorders(Request $request)
    {
        $user = member::where('id', $request->user_id)->first();
        if ($user) {
            
            $myorders = order::where('user_id', $request->user_id)->get();
            $orderdetails = array();
            
            if (count($myorders) != 0) {
                foreach ($myorders as $showorder) {
                        $city = City::where('id',$showorder->city_id)->first();
                        $orderitems   = order_item::where('order_id', $showorder->id)->get();
                        $itemarr      = array();

                        foreach ($orderitems as $item) {
                            $orderitem = item::where('id', $item->item_id)->first();
                            $cutting = Cutting::where('id', $item->cutting_id)->first();
                            $weight = weight::where('id', $item->weight_id)->first();
                            
                            array_push(
                                $itemarr,
                                array(
                                    "id"           => $orderitem->id,
                                    'image'        => $orderitem->image,
                                    'title'        => $orderitem->artitle,
                                    'price'        => $weight->price,
                                    'qty'          => $item->qty,
                                    'cutting'      => $cutting->cutting_name,
                                    'weight'       => $weight->weight_name,
                                    'packaging'    => $item->packaging,
                                    'skin'         => $item->skin,
                                    'minced'       => $item->minced,
                                    'bowels'        => $item->bowels,
                                    'moqalqal'        => $item->moqalqal,

                                )
                            );
                        }
                        // return $itemarr;
                        array_push(
                            $orderdetails,
                            array(
                                "id"            => $showorder->id,
                                "order_number"  => $showorder->order_number,
                                "user_id"       => $showorder->user_id,
                                "user_name"     => $showorder->name,
                                "user_address"  => null,
                                "mobile"        => $showorder->mobile,
                                "phone"         => $showorder->phone,
                                "date"       => $showorder->date,
                                "time"       => $showorder->time,
                                "notes"        => $showorder->notes,
                                "total"         => $showorder->total,
                                "status"        => $showorder->status,
                                "paid"          => $showorder->paid,
                                "deliver"        => $showorder->deliver,
                                "created_at"    => $showorder->created_at,
                                "items"         => $itemarr,
                            )
                        );
                        // return $this->sendResponse('success', $orderdetails);
                    
                }
                return $this->sendResponse('success', $orderdetails);
            } else {
                $errormessage = 'لا يوجد طلبات';
                return $this->sendError('success', $errormessage);
            }
        } else {
            $errormessage = 'هذا المستخدم غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }

    
}