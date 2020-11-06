@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة بيانات الصالون @endsection
@section('content')
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li style="margin-right: 0px; width:33%" class="active "><a href="#activity" data-toggle="tab"> بيانات الصالون </a></li>
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="activity">
                                    <div class="box-body">
                                        <div style="margin-top: 7%;" class="col-md-6">
                                            <div class="form-group col-md-12">
                                                <label>الاسم المشغل</label>
                                                <input type="text" class="form-control" value="{{$category->name}}" readonly>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>تفاصيل المشغل</label>
                                                <input type="text" class="form-control" value="{{$category->des}}" readonly>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>خط الطول (lat)</label>
                                                <input type="text" class="form-control" value="{{$category->lat}}" readonly>
                                            </div>

                                             <div class="form-group col-md-12">
                                                <label>خط العرض (lng)</label>
                                                <input type="text" class="form-control" value="{{$category->lng}}" readonly>
                                            </div>

                                            <?php
                                               $city  = DB::table('cities')->where('id',$category->city_id)->first();
                                             ?>
                                            <div class="form-group col-md-12">
                                                <label>المدينه</label>
                                                <input type="text" class="form-control" value="{{$city ? $city->name_ar : ""}}" readonly>
                                            </div>
                                            <?php
                                                $district  = DB::table('districts')->where('id',$category->district_id)->first();
                                            ?>
                                            <div class="form-group col-md-12">
                                                <label>الحي</label>
                                                <input type="text" class="form-control" value="{{$district ? $district->name : ''}}" readonly>
                                            </div>
                                           </div>

                                          <div class="form-group col-md-12">
                                              <label>صور العنصر</label>
                                              <br>
                                              @foreach($adimages as $image)
                                              {{-- {{dd($image)}} --}}
                                              <div style="padding: 2%;" class="col-md-3">
                                                  <img class="img-thumbnail" style="width:100%; height:10%;" src={{ url("/../users/images/".$image->image)}} alt="">
                                              </div>
                                              @endforeach
                                          </div>
                                    </div>
                                </div>
            </div>
        </div>
    </div>
</section>

@endsection
