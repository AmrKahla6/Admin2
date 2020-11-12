@extends('admin/include/master')
@section('title') لوحة التحكم | تعديل بيانات الصالون @endsection
@section('content')

<section class="content">
    <div class="row">
      <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">تعديل بيانات الصالون </h3>
          <br><br>
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

            <div class="form-group col-md-12">
                <label>اختر العضو</label>
                <div class="form-group col-md-12">
                    <select name="user_id" id="user_id">
                     {{-- <option value="">اختر المدينة</option> --}}
                     @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id || $category->user_id == $user->id  ? 'selected' : '' }} >{{$user->name}}</option>
                     @endforeach
                    </select>
                 </div>
            </div>

            <div class="form-group col-md-12">
                <label>صور اكثر عن الاعلان [يمكنك رفع اكثر من صورة]</label>
                <input type="file" name="items[]" multiple>
                @if ($errors->has('items'))
                    <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('items') }}</div>
                @endif
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
            </div>

            <div class="form-group col-md-6">
                <label>خط الطول(lat)</label>
                <input type="text" name="lat" value="{{$category->lat}}" class="form-control" placeholder = 'خط الطول '>
                @if ($errors->has('lat'))
                    <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('lat') }}</div>
                @endif
            </div>

            <div class="form-group col-md-6">
                <label>خط العرض(lng)</label>
                <input type="text" name="lng" value="{{$category->lng}}" class="form-control" placeholder = 'خط العرض '>
                @if ($errors->has('lng'))
                    <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('lng') }}</div>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label>اختر المدينة</label>
                <div class="form-group col-md-12">
                    <select name="city_id" id="city_id">
                     {{-- <option value="">اختر المدينة</option> --}}
                     @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id || $category->city_id == $city->id  ? 'selected' : '' }} >{{ $city->name_ar }}</option>
                     @endforeach
                    </select>
                 </div>
            </div>

            <div class="form-group col-md-12">
                <label>اختر الحي</label>
                <div class="form-group col-md-12">
                    <select name="district_id" id="district_id">
                     {{-- <option value="">اختر المدينة</option> --}}
                     @foreach ( \App\District::where('cities_id' , $category->city_id)->get() as $district)
                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id || $category->district_id == $district->id  ? 'selected' : '' }} >{{ $district->name }}</option>
                     @endforeach
                    </select>
                 </div>
            </div>
            <div class="box-footer">
                <button style="width: 20%;margin-right: 40%;" type="submit" class="btn btn-success">تعديل</button>
            </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
</section>

<script>

    $('#city_id').change(function () {
        var CSRF_TOKEN  = $('meta[name="csrf-token"]').attr('content');
        var id          = $('#city_id').val();
        var action      =  "{{route('city.districts')}}";
        $.ajax({
            url:  action,
            type: 'POST',
            dataType: 'JSON',
            data: {_token: CSRF_TOKEN, id: id},
            success: function(data, status){
                $('#district_id').empty();
                var i = 0;
                for(i; i < data.length; i++) {
                    $('#district_id').append(`<option value="${data[i].id}">${data[i].name}</option>`);
                }

            }
        });
    });
</script>

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
