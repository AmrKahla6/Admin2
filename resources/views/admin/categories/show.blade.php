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
                                            <?php
                                               $city  = DB::table('cities')->where('id',$category->city_id)->first();
                                             ?>
                                            <div class="form-group col-md-12">
                                                <label>المدينه</label>
                                                <input type="text" class="form-control" value="{{$city->name_ar}}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <img class="img-circle" style="width:100%; height:50%;" rc={{ url("/../users/images/".$category->image)}} alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
            </div>
        </div>
    </div>
</section>

@endsection
