@extends('admin/include/master')
@section('title') لوحة التحكم |  الاقسام  @endsection
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">المشاغل</h3>
                    <button style="float:left" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-addclass"><i class="fa fa-plus" aria-hidden="true"></i> إضافة مشغل جديد</button>
                </div>

                <div class="modal fade" id="modal-addclass" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">إضافة مشغل جديد  </h4>
                        </div>
                        <div class="modal-body">
                            {{ Form::open(array('method' => 'POST','files'=>true,'url' => 'adminpanel/categories')) }}

                                <div class="form-group col-md-12">
                                    <label>اسم المشغل</label>
                                    <div class="form-group col-md-12">
                                        <input style="width:100%;" type="text" class="form-control" name="name" placeholder="اسم المشغل"  required>
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
                                            <textarea id="editor1" name="des" rows="10" cols="167" required>{!! old('des') !!}</textarea>
                                            @if ($errors->has('des'))
                                                <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('des') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>صورة المشغل</label>
                                    <input type="file" name="image" >
                                    @if ($errors->has('image'))
                                        <div style="color: crimson;font-size: 18px;" class="error">{{ $errors->first('image') }}</div>
                                    @endif
                                </div>

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
                                </div>

                                <button type="submit" class="btn btn-primary col-md-offset-4 col-md-4">اضافة</button>
                            {!! Form::close() !!}
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">اغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="active tab-pane" id="activity">
                    <div class="table-responsive box-body">
                        {{-- <button style="margin-bottom: 10px;float:left;" class="btn btn-danger delete_all" data-url="{{ url('mycategoriesDeleteAll') }}"><i class="fa fa-trash-o" aria-hidden="true"></i> حذف المحدد</button> --}}
                        <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>

                                        <th>الاسم</th>
                                        <th>مشاهده</th>
                                        <th>تعديل</th>
                                        <th> حذف</th>
                                        {{-- <th width="50px"><input type="checkbox" id="master"></th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($categories as $category)
                                    <?php
                                       $city  = DB::table('cities')->where('id',$category->city_id)->first();
                                     ?>
                                        <tr>
                                            <td>
                                                {{ $category->name}}
                                            </td>
                                            <td>
                                                <a href='{{asset("adminpanel/categories/".$category->id)}}' class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                              </td>
                                            <td>
                                                <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#modal-upclass{{$category->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            </td>

                                            <td>
                                                {{ Form::open(array('method' => 'DELETE',"onclick"=>"return confirm('هل انت متأكد ؟!')",'files' => true,'url' => array('adminpanel/categories/'.$category->id))) }}
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                {!! Form::close() !!}
                                            </td>
                                            {{-- <td><input type="checkbox" class="sub_chk"      data-id="{{$cutting->id}}"></td> --}}
                                        </tr>

                                    <div class="modal fade" id="modal-upclass{{$category->id}}" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title">تعديل المشغل</h4>
                                            </div>
                                            <div class="modal-body">
                                                {{ Form::open(array('method' => 'patch','files'=>true,'url' => 'adminpanel/categories/'.$category->id )) }}

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
                                                    </div>

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
                                                    </div>

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
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">اغلاق</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {

        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))
         {
            $(".sub_chk").prop('checked', true);
         } else {
            $(".sub_chk").prop('checked',false);
         }
        });


        $('.delete_all').on('click', function(e) {
            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });


            if(allVals.length <=0)
            {
                alert("حدد عنصر واحد ع الاقل ");
            }  else {


                var check = confirm("هل انت متاكد؟");
                if(check == true){
                    var join_selected_values = allVals.join(",");
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });


                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }
            }
        });


        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });


        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();

            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });
            return false;
        });
    });
</script>

@endsection
