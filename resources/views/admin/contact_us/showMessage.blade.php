@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة تفاصيل الرساله @endsection
@section('content')
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li style="margin-right: 0px; width:33%" class="active "><a href="#activity" data-toggle="tab"> بيانات العضو الشخصية </a></li>
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="activity">
                                    <div class="box-body">
                                        <div style="margin-top: 7%;" class="col-md-6">

                                            <div class="form-group col-md-12">
                                                <label>الاسم بالكامل</label>
                                                <input type="text" class="form-control" value="{{$showmessage->name}}" readonly>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>رقم الهاتف</label>
                                                <input type="text" class="form-control" value="{{$showmessage->phone}}" readonly>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>البريد الالكتروني</label>
                                                <input type="text" class="form-control" value="{{$showmessage->email}}" readonly>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label> محتوي الرساله</label>
                                                <input type="text" class="form-control" value="{{$showmessage->message}}" readonly>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>التاريخ</label>
                                                <input type="text" class="form-control" value="{{$showmessage->created_at->diffForHumans()}}" readonly>
                                            </div>
                                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection
