@extends('admin.include.master')
@section('title') لوحة التحكم @endsection
@section('content')
{{-- {{dd('amr')}} --}}
<section class="content">
<h2> لوحة التحكم | احصائيات </h2>
<?php
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
?>
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$allmembers}}</h3>
              <p>الاعضاء</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{asset('adminpanel/users')}}" class="small-box-footer">المزيد<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$allcats}}</h3>
              <p>الصالونات</p>
            </div>
            <div class="icon">
              <i class="fa fa-tags"></i>
            </div>
            <a href="{{asset('adminpanel/categories')}}" class="small-box-footer"> المزيد <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$allbooking}}</h3>
              <p>طلبات الحجز</p>
            </div>
            <div class="icon">
              <i class="fa fa-tags"></i>
            </div>
            <a href="{{asset('adminpanel/booking')}}" class="small-box-footer"> المزيد <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>



        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$allservices}}</h3>
              <p>الخدمات</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{asset('adminpanel/services')}}" class="small-box-footer">المزيد<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


</section>
@endsection
