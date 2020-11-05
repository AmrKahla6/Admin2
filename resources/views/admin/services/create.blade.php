@extends('admin/include/master')
@section('title') لوحة التحكم | إضافة خدمه جديد @endsection
@section('content')

<section class="content">
        <div class="row">
                <div class="col-xs-12">
                <div class="box box-primary">

              <div class="box-header with-border">
                <h3 class="box-title">إضافة خدمه جديد</h3>
              </div>

              {!! Form::open(array('method' => 'POST','files' => true,'url' =>'adminpanel/services')) !!}
                <div class="box-body">
                  <div class="form-group col-md-6">
                    <label>اسم الخدمه </label>
                    <input type="text" class="form-control" name="name" placeholder="ادخل اسم الخدمه " value="{{ old('name') }}" required>
                    @if ($errors->has('name'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('name') }}</div>
                    @endif
                  </div>

                  <div class="form-group col-md-6">
                    <label>السعر [ريال]</label>
                    <input type="number" name="price" class="form-control" placeholder = 'ادخل السعر' required>
                    @if ($errors->has('price'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('price') }}</div>
                    @endif
                  </div>

                  <div class="form-group col-md-12">
                    <label>اختر مشغل</label>
                    <div class="form-group col-md-12">
                        <select name="category_id" id="">
                         @foreach ($categories as $category)
                               <option  value="{{$category->id}}">{{$category->name}}</option>
                         @endforeach
                        </select>
                     </div>
                    </div>

                  <div class="form-group col-md-12">
                      <label>صوره الخدمه</label>
                      <input type="file" name="image" >
                      @if ($errors->has('image'))
                          <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('image') }}</div>
                      @endif
                  </div>
                  <div class="form-group col-md-12">
                    <label>صور الخدمه</label>
                    <input type="file" name="images[]" multiple>
                    @if ($errors->has('images'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('images') }}</div>
                    @endif
                </div>

                  <div class="col-md-12">
                      <div class="box box-info">
                          <div class="box-header">
                          <h3 class="box-title" > تفاصيل الخدمه </h3>
                          </div>
                          <div class="box-body pad">
                              <textarea id="editor1" name="des" rows="10" cols="167" required>{!! old('des') !!}</textarea>
                              @if ($errors->has('des'))
                                  <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('des') }}</div>
                              @endif
                          </div>
                      </div>
                  </div>

                </div>

                <div class="box-footer">
                  <button style="width: 20%;margin-right: 40%;" type="submit" class="btn btn-primary">إضافة</button>
                </div>
                {!! Form::close() !!}
          </div>
          </div>
          </div>
</section>

<script type="text/javascript">

    $(document).ready(function () {
        $('#itme_offer').change(function() {
         if($(this).val() == 1)
         {
            $("#discountprice").css('display', 'block');
         } else {
            $("#discountprice").css('display', 'none');
         }
        });
    });

</script>

@endsection
