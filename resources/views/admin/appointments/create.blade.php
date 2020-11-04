@extends('admin/include/master')
@section('title') لوحة التحكم |حجز موهد  @endsection
@section('content')

<section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">

              <div class="box-header with-border">
                <h3 class="box-title"> حجز موعد</h3>
              </div>

              {!! Form::open(array('method' => 'POST','files' => true,'url' =>'adminpanel/appointment')) !!}
                <div class="box-body">

                <div class="form-group col-md-6">
                    <label>الاسم بالكامل </label>
                    <input type="text" class="form-control" name="name" placeholder="ادخل الاسم بالكامل " value="{{ old('name') }}" required>
                    @if ($errors->has('name'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('name') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label>البريد الالكتروني </label>
                    <input type="email" class="form-control" name="email" placeholder="ادخل البريد الالكترومي " value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label>رقم الهاتف</label>
                    <input type="text" class="form-control" name="phone" placeholder=" أدخل رقم الهاتف" value="{{ old('phone') }}" required>
                    @if ($errors->has('phone'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('phone') }}</div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label>التاريخ</label>
                    <input type="text" class="form-control" name="mobile" placeholder="ادخل رقم الجوال" value="{{ old('mobile') }}" required>
                    @if ($errors->has('mobile'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('mobile') }}</div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label> كلمة المرور</label>
                    <input type="password" class="form-control" id="adminpass1" name="pass" placeholder="كلمة المرور" required>
                    <div style="padding:1%;" id="errorpass"></div>
                    <div class="figure" id="strength_human2"></div>
                    @if ($errors->has('pass'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('pass') }}</div>
                    @endif
                </div>

                 <div class="form-group col-md-6">
                    <label> إعادة كلمة المرور</label>
                    <input type="password" class="form-control" id="confirmadminpass1" name="confirmpass" placeholder="إعادة كلمة المرور" required>
                    <!--<div style="color: crimson;font-size: 12px;display:none;" class="error" id="errorconfirm"></div>-->
                    @if ($errors->has('confirmpass'))
                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('confirmpass') }}</div>
                    @endif
                </div>

                </div>

                <div class="box-footer">
                  <button style="width: 20%;margin-right: 40%;" type="submit"  class="btn btn-primary">إضافة</button>
                </div>
                {!! Form::close() !!}
          </div>
        </div>
</section>
@endsection
