@extends('admin/include/master')
@section('title') لوحة التحكم | تعديل بيانات الصالون @endsection
@section('content')

<section class="content">
    <div class="row">
      <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">تعديل بيانات الصالون </h3>
          <p> {{ $category->name}} </p>
        </div>

        {!! Form::open(array('method' => 'patch','files' => true,'url' =>'adminpanel/categories/'.$category->id)) !!}
        <div class="box-body">
            <div class="form-group col-md-12">
                <label>اسم المشغل</label>
                <div class="form-group col-md-12">
                    <input style="width:100%;" type="text" class="form-control" name="name" placeholder="اسم المشغل" value="{{$category->name}}" required>
                    @if ($errors->has('name'))
                    <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                    <h3 class="box-title" > تفاصيل المشغل </h3>
                    </div>
                    <div class="box-body pad">
                        <textarea id="editor1" name="des" rows="10" cols="80" required>{!! $category->des !!}</textarea>
                        @if ($errors->has('des'))
                            <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('des') }}</div>
                        @endif
                    </div>
                </div>
            </div
            <div class="form-group col-md-12">
                <label>اختر المدينة</label>
                <div class="form-group col-md-12">
                    <select name="city_id" id="">
                     {{-- <option value="">اختر المدينة</option> --}}
                     @foreach ($cities as $city)
                           <option  value="{{$city->id}}">{{$city->name_ar}}</option>
                     @endforeach
                    </select>
                 </div>
            </div
            <div class="form-group col-md-12">
                <label>صورة المدينة</label>
                <br>
                <input type="file" name="image" >
                <div style="padding: 2%;" class="col-md-3">
                    <img class="img-thumbnail" style="width:100%; height:10%;" src="{{asset('users/images/'.$category->image)}}" alt="Not Found">
                </div>
            </div>
            <button type="submit" class="btn btn-primary col-md-offset-4 col-md-4">تعديل</button>
            {!! Form::close() !!}
        </div>
      </div>
    </div>
</section>

<script type="text/javascript">
        $("#country").change(function(){
            $.ajax({
                url: "{{ route('admin.list_cities') }}?country_id=" + $(this).val(),
                method: 'GET',
                success: function(data) {
                    $('#city').html(data.html);
                }
            });
        });
   </script>
@endsection
