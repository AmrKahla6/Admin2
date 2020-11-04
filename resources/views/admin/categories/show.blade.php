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
                                    <form action="{{ route('rating.store') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="card">
                                            <div class="container-fliud">
                                                <div class="wrapper row">
                                                    <div class="preview col-md-6">
                                                        <div class="preview-pic tab-content">
                                                            <div class="tab-pane active" id="pic-1"><img src="https://dummyimage.com/300x300/0099ff/000" /></div>
                                                        </div>
                                                    </div>
                                                    <div class="details col-md-6">
                                                        <h3 class="product-title">ما هو تقيمك للمشغل</h3>
                                                        <div class="rating">
                                                            <input id="input-1" name="rate" class="rating rating-loading" data-min="0" data-max="5" data-step="1" value="{{ $category->userAverageRating }}" data-size="xs">
                                                            <input type="hidden" name="id" required="" value="{{ $category->id }}">
                                                            <br/>
                                                            <button class="btn btn-success">قم بالتقيم</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <

                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $("#input-id").rating();
    </script>

</section>

@endsection
