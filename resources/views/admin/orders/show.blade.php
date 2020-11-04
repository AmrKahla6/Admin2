@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة تفاصيل الطلب  @endsection
@section('content')

  <section class="content-header"></section>
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> رقم الطلب   {{$showorder->order_number}}#
            <small class="pull-left">تاريخ الطلب : {{ date('Y/m/d', strtotime($showorder->created_at)) }}</small>
          </h2>
        </div>
      </div>
     
      <div class="row invoice-info">
        <div class="col-sm-12 invoice-col">
            @if($showorder->status == 0)
                <span style="border-radius: 3px;border: 1px solid green;color: orange;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">قيد الانتظار</span>
            @elseif($showorder->status == 1) 
                  <span style="border-radius: 3px;border: 1px solid green;color: springgreen;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">جارى التجهيز</span>
            @elseif($showorder->status == 2)   
                  <span style="border-radius: 3px;border: 1px solid #c22356;float:left;color:crimson;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم رفض الطلب</span>
            @elseif($showorder->status == 3)   
                  <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم التسليم</span>
            @endif    
               
            @if($showorder->paid == 0)   
              {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/bills/'.$showorder->id )) }}
                      <input type="hidden" name="confirm" >الدفع عن طريق التحويل البنكي
                      <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i>تفعيل</button>
              {!! Form::close() !!}
            @elseif($showorder->paid == 1)   
                  {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/bills/'.$showorder->id )) }}
                      <input type="hidden" name="confirm" >الدفع عند الإستلام   
                      <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i>تفعيل</button>
              {!! Form::close() !!}
            @elseif($showorder->paid == 2)   
                  <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;margin-left: 5px;" class="ads__item__featured">تم الدفع</span>
            @endif 
            
            
          صاحب الطلب
          <address>
           <a href="{{asset('adminpanel/users/'.$ownerinfo->id)}}"> 
            <strong>{{$showorder->name}}</strong> </a> <br>
             رقم الهاتف : {{$showorder->phone}}<br>
             رقم الجوال : {{$showorder->mobile}}<br>
            المدينة     : {{$city->name}}<br>
          </address>
        </div>
        
      <div class="row">
        <div class="col-xs-12">
          <div class="table-responsive">
            @foreach($itemorders as $item)
              <?php 
                  $iteminfo  = DB::table('items')->where('id',$item->item_id)->first();
                  $cutting     = DB::table('cuttings')->where('id', $item->cutting_id)->first();
                  $weight     = DB::table('weights')->where('id', $item->weight_id)->first();

              ?>
              <div class="col-md-8">
                <table class="table border-bottom" >
                    <tbody>

                      <tr>
                          <th style="width: 25%;">نوع الذبيحة</th>
                          <td>
                            <a href="{{asset('adminpanel/items/'.$iteminfo->id)}}">{{$iteminfo->artitle}}</a>
                          </td>
                      </tr>
                      
                      
                      <tr>
                          <th style="width: 25%;">نوع التقطيع</th>
                         
                          <td>
                            {{$cutting->cutting_name}}
                          </td>
                          
                      </tr>
                     
                     
                      <tr>
                          
                          <th style="width: 25%;">الوزن</th>
                           @if($weight)
                          <td>
                            {{$weight->weight_name}}
                          </td>
                         @endif
                      </tr>
                     
                      <tr>
                          <th style="width: 25%;"> التغليف</th>
                          @if ($item->packaging ==0)
                              <td>صحون فلين</td>
                          @elseif($item->packaging ==1)
                              <td> أكياس نايلون</td>    
                          @endif
                          
                      </tr>

                      <tr>
                        
                          <th style="width: 25%;"> (شلوطة الرأس والمقادم (بالنار</th>
                          @if ($item->skin ==0)
                              <td>لا</td>
                          @elseif($item->skin ==1)
                              <td>نعم</td>    
                        
                          @endif
                          
                      </tr>

                      
                       <tr>
                          <th style="width: 25%;">مفروم عدد كيلو </th>
                          <td>{{$item->minced}}</td>
                      </tr>
                        
                      
                       <tr>
                            <th style="width: 25%;"> لف المغاضيف  </th>
                            <td>{{$item->bowels}} </td>
                      </tr>

                       <tr>
                            <th style="width: 25%;"> تقطيع شوربة أو مقلقل </th>
                            <td>{{$item->moqalqal}} </td>
                      </tr>
                      
                      <tr>
                            <th style="width: 25%;">الكمية</th>
                            <td>{{$item->qty}}</td>
                      </tr>

                      <tr>
                            <th style="width: 25%;">السعر</th>
                            <td>{{$weight->price}} ريال</td>
                      </tr>

                      <tr>
                            <th style="width: 25%;">طريقة التوصيل</th>
                            <td>{{$showorder->deliver}} </td>
                      </tr>
                      <tr>
                            <th style="width: 25%;">التاريخ</th>
                            <td>{{$showorder->date}} </td>
                      </tr>
                       
                      <tr>
                            <th style="width: 25%;">الوقت</th>
                            <td>{{$showorder->time}} </td>
                      </tr>


                      <tr>
                            <th style="width: 25%;">ملاحظات</th>
                            <td>{{$showorder->notes}}</td>
                      </tr>
                      <tr>
                            <th style="width: 25%;"></th>
                            <td><form method="post" action="https://api.whatsapp.com/send?phone=+966555965587&text=https://maps.google.com/?q={{$showorder->lat}},{{$showorder->lng}}                                                                                          Name: {{$showorder->name}}   / Phone: {{$showorder->phone}}  /  Mobile: {{$showorder->mobile}}">
                                
                      <input type="hidden" name="confirm" >إرسال بيانات العميل على الواتساب   
                      <button type="submit" class="btn btn-success"><i class="fa fa-whatsapp" aria-hidden="true"></i>whatsapp</button>
              </form></td>
                      </tr>
                      
                      
                        
                    </tbody>
                </table>
              </div>
              <div class="col-md-4">
                  <img style="width:100%;height:110px;" src="{{asset('users/images/'.$iteminfo->image)}}" alt="{{$iteminfo->artitle}}">
              </div>
              <br><br><hr>
            @endforeach
          </div>
          <div class="col-md-12">
              <h3>الاجمالى : <span style="color:#500253">{{$showorder->total}}</span> ريال</h3>
          </div>  
        </div>
      </div>

    </section>
@endsection