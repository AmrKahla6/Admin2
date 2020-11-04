@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة بيانات الصالون @endsection
@section('content')
<section class="content">
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li style="margin-right: 0px; width:33%" class="active "><a href="#activity" data-toggle="tab"> بيانات الصالون </a></li>
                    <li style="margin-right: 0px; width:33%"><a href="#activity1" data-toggle="tab">التعليقات</a></li>
                    <li style="margin-right: 0px; width:33%;"><a href="#activity2" data-toggle="tab">التقيم</a></li>
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
                                                <img class="img-circle" style="width:100%; height:50%;" src="{{asset('users/images/'.$category->image)}}" alt="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="col-md-12">

                                                {{-- <img class="img-circle" style="width:100%; height:50%;" src="{{asset('users/images/default2.png')}}" alt="{{$showuser->name}}"> --}}

                                            </div>
                                        </div>
                                    </div>
                    </div>

                    <div class="tab-pane" id="activity1">
                        <div class="box">
                            <h3 class="box-title">التعليقات</h3>
                            @include('admin.categories.commentsDisplay', ['comments' => $category->comments, 'category_id' => $category->id])
                                <div class="table-responsive box-body">
                                    <h4>اضف تعليق</h4>
                                        <form method="post" action="{{ route('comments.store'   ) }}">
                                            @csrf
                                            <div class="form-group">
                                                <textarea class="form-control" name="comment"></textarea>
                                                <input type="hidden" name="category_id" value="{{ $category->id }}" />
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success" value="Add Comment" />
                                            </div>
                                        </form>
                                </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="activity2">
                        <div class="box">
                            <h3 class="box-title">التقيم</h3>
                                <div class="table-responsive box-body">
                                    <h4>ضع تقيم</h4>
                                        {{-- <form method="post" action="{{ route('rates.store'   ) }}">
                                            @csrf
                                            <div class="form-group">
                                                <textarea class="form-control" name="rate"></textarea>
                                                <input type="hidden" name="category_id" value="{{ $category->id }}" />
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success" value="Add rate" />
                                            </div>
                                        </form> --}}
                                        <div class="stars">
                                             <form method="post" action="{{ route('rates.store'   ) }}">
                                              <input class="star star-5" id="star-5" type="radio" name="star"/>
                                              <label class="star star-5" for="star-5"></label>
                                              <input class="star star-4" id="star-4" type="radio" name="star"/>
                                              <label class="star star-4" for="star-4"></label>
                                              <input class="star star-3" id="star-3" type="radio" name="star"/>
                                              <label class="star star-3" for="star-3"></label>
                                              <input class="star star-2" id="star-2" type="radio" name="star"/>
                                              <label class="star star-2" for="star-2"></label>
                                              <input class="star star-1" id="star-1" type="radio" name="star"/>
                                              <label class="star star-1" for="star-1"></label>
                                            </form>
                                        </div>
                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection
