@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة تفاصيل الطلب  @endsection
@section('content')

  <section class="content-header"></section>
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> رقم الحجز   {{$showbooking->order_number}}#
            <small class="pull-left">تاريخ الحجز : {{ date('Y/m/d', strtotime($showbooking->created_at)) }}</small>
          </h2>
        </div>
      </div>

      <div class="row invoice-info">
        <div class="col-sm-12 invoice-col">
            @if($showbooking->status == 0)
                <span style="border-radius: 3px;border: 1px solid green;color: orange;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">قيد الانتظار</span>
            @elseif($showbooking->status == 1)
                  <span style="border-radius: 3px;border: 1px solid green;color: springgreen;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">جارى التجهيز</span>
            @elseif($showbooking->status == 2)
                  <span style="border-radius: 3px;border: 1px solid #c22356;float:left;color:crimson;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم رفض الطلب</span>
            @elseif($showbooking->status == 3)
                  <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم التسليم</span>
            @endif

            @if($showbooking->paid == 0)
              {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/bills/'.$showbooking->id )) }}
                      <input type="hidden" name="confirm" >الحجز عن طريق التحويل البنكي
                      <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i>تفعيل</button>
              {!! Form::close() !!}
            @elseif($showbooking->paid == 1)
                  {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/bills/'.$showbooking->id )) }}
                      <input type="hidden" name="confirm" >الحجز عند الحضور
                      <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i>تفعيل</button>
              {!! Form::close() !!}
            @elseif($showbooking->paid == 2)
                  <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;margin-left: 5px;" class="ads__item__featured">تم الدفع</span>
            @endif
            <?php
            $cat  = DB::table('categories')->where('id',$showbooking->category_id)->first();
            $serv  = DB::table('services')->where('id',$showbooking->service_id)->first();

             ?>


          صاحب الحجز
          <address>
           <a href="{{asset('adminpanel/users/'.$ownerinfo->id)}}">
            <strong>{{$showbooking->name}}</strong> </a> <br>
              صالون :<strong> {{$cat->name}}  </strong>  <br>
              الخدمه :<strong> {{$serv->name}}  </strong>  <br>
              السعر :<strong> {{$serv->price}} ريال سعودي  </strong>  <br>
             رقم الهاتف : {{$showbooking->phone}}<br>
             رقم الحجز : {{$showbooking->booking_number}}<br>

          </address>
        </div>



    </section>
@endsection
