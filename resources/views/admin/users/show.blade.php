@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة بيانات العضو @endsection
@section('content')
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li style="margin-right: 0px; width:33%" class="active "><a href="#activity" data-toggle="tab"> بيانات العضو الشخصية </a></li>
                    {{-- <li style="margin-right: 0px; width:33%"><a href="#activity1" data-toggle="tab">طلباتى</a></li> --}}
                    {{-- <li style="margin-right: 0px; width:33%;"><a href="#activity2" data-toggle="tab">الفواتير</a></li> --}}
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="activity">
                                    <div class="box-body">
                                        <div style="margin-top: 7%;" class="col-md-6">

                                            <div class="form-group col-md-12">
                                                <label>الاسم بالكامل</label>
                                                <input type="text" class="form-control" value="{{$showuser->name}}" readonly>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>رقم الهاتف</label>
                                                <input type="text" class="form-control" value="{{$showuser->phone}}" readonly>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>البريد الالكتروني</label>
                                                <input type="text" class="form-control" value="{{$showuser->email}}" readonly>
                                            </div>


                                        </div>

                                        <div class="col-md-6">

                                        <h3 class="box-title" style="float:left;"> {{$showuser->name}}</h3>

                                            <h4 style="float:right;margin-top: 5%;">
                                                @if($showuser->suspensed == 0)
                                                غير معطل<span> <i class="fa fa-unlock text-success"></i> </span>
                                                @else
                                                معطل<span> <i class="fa fa-lock text-danger"></i> </span>
                                                @endif
                                            </h4>

                                            <div class="col-md-12">

                                                <img class="img-circle" style="width:100%; height:50%;" src="{{asset('users/images/default2.png')}}" alt="{{$showuser->name}}">

                                            </div>
                                        </div>
                                    </div>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection
