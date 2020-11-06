@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة تفاصيل الخدمه @endsection
@section('content')
<?php use Carbon\Carbon; ?>
        <div class="box-body">
            <div style="margin-top: 7%;" class="col-md-6">



                <div class="form-group col-md-12">
                    <label>اسم الخدمه </label>
                    <input type="text" class="form-control" value="{{$showservice->name}}" readonly>
                </div>


                <div class="form-group col-md-12">
                    <label>السعر [ريال]</label>
                    <input type="text" class="form-control" value="{{$showservice->price}}" readonly>
                </div>
                <div class="form-group col-md-12">
                    @php
                       $newservice = \App\Category::find($showservice->category_id);
                    @endphp
                    <label>اسم المشغل</label>
                     <input type="text" class="form-control" value="{{$newservice ? $newservice->name : ''}}" readonly>
                   </div>

                    <div class="form-group col-md-12">
                        <label>(Icon) ايكون الخدمه</label>
                        <input type="text" class="form-control" value="{{$showservice->icon}}" readonly>
                    </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                حجز موعد
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href='{{asset("adminpanel/appointment/{id}")}}' class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>

            <div class="col-md-6">
            <h6 class="box-title" style="float:left;"> تاريخ الخدمه : {{ $showservice->created_at }}</h6><br>

                <h4 style="float:right;margin-top: 5%;">
                     @if($showservice->suspensed == 0)
                      غير معطل<span> <i class="fa fa-unlock text-success"></i> </span>
                    @else
                       معطل<span> <i class="fa fa-lock text-danger"></i> </span>
                    @endif
                </h4>


                <div class="col-md-12">
                      <img class="img-thumbnail" style="width:100%; height:30%;" src="{{asset('/../users/images/'.$showservice->image)}}" alt="">
                </div>


                </hr>
                <div class="form-group col-md-12">
                    <label>تفاصيل الخدمه</label>
                    <textarea rows='10' type="text" class="form-control" readonly>{!! $showservice->des !!}</textarea>
                </div>
            </div>
        </div>
@endsection
