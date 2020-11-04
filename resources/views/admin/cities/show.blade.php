@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة بيانات المدينه @endsection
@section('content')
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li style="margin-right: 0px; width:33%" class="active "><a href="#activity" data-toggle="tab"> بيانات المدينه </a></li>
                    {{-- <li style="margin-right: 0px; width:33%"><a href="#activity1" data-toggle="tab">طلباتى</a></li>
                    <li style="margin-right: 0px; width:33%;"><a href="#activity2" data-toggle="tab">الفواتير</a></li> --}}
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="activity">
                                    <div class="box-body">
                                        <div style="margin-top: 7%;" class="col-md-6">

                                            <div class="form-group col-md-12">
                                                <label>الاسم المدينه باللغه العربيه</label>
                                                <input type="text" class="form-control" value="{{$city->name_ar}}" readonly>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>الاسم المدينه باللغه الانجليزيه</label>
                                                <input type="text" class="form-control" value="{{$city->name_en}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <img class="img-circle" style="width:100%; height:50%;" src="{{asset('users/images/'.$city->image)}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection
