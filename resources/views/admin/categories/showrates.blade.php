@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة تقييمات المنتج @endsection
@section('content')

<style>
.checked {
    color: orange;
}
</style>
    <section class="content-header">
      <h1>
        كل التقييمات
      </h1>
    </section>
    <section style="direction: ltr;" class="content">
      <div class="row">
        <div class="col-md-12">
          <ul class="timeline">
        @if(count($convdates) > 0)
            @foreach($convdates as $obj)
            <?php
              $numdate = date('Y-m-d', strtotime($obj));
              $adrates = DB::table('rates')->where('created_date','=',$numdate)->where('category_id',$showitem->id)->orderby('created_time','desc')->get();
             ?>
                <li class="time-label"><span class="bg-red">{{ $obj}}</span></li>

                @foreach($adrates as $comm)
                <?php $commowner = DB::table('members')->where('id',$comm->user_id)->value('name'); ?>
                        <li>
                            <i class="fa fa-star-half-o bg-yellow"></i>
                            <div style="direction: rtl;margin-bottom: 1%;" class="timeline-item">
                                <span style="float:left;" class="time"><i class="fa fa-clock-o"></i>  {{ $comm->created_time }}</span>
                                <h3 style="float:right;" class="timeline-header"><a href="{{asset('adminpanel/users/'.$comm->user_id)}}">{{$commowner}}</a>قام بتقييم هذا المنتج</h3>
                                <br><br>
                                <div style="float: right;margin-right:0;" class="timeline-body">
                                <span class="fa fa-star @if($comm->rate == 1 || $comm->rate > 1) checked @endif"></span>
                                <span class="fa fa-star @if($comm->rate == 2 || $comm->rate > 2) checked @endif"></span>
                                <span class="fa fa-star @if($comm->rate == 3 || $comm->rate > 3) checked @endif"></span>
                                <span class="fa fa-star @if($comm->rate == 4 || $comm->rate > 4) checked @endif"></span>
                                <span class="fa fa-star @if($comm->rate == 5) checked @endif"></span>
                                {{-- {{$comm->comment}} --}}
                                </div>
                                <br>
                                <div class="timeline-footer">
                                {{ Form::open(array('method' => 'DELETE',"onclick"=>"return confirm('هل انت متأكد ؟!')",'files' => true,'url' => array('adminpanel/ratedel/'.$comm->id))) }}
                                <input type="hidden" name="delrate" >
                                <button style="width: 10%;" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" aria-hidden="true"></i> حذف </button>
                                {!! Form::close() !!}
                                </div>
                            </div>
                        </li>
                @endforeach
            @endforeach
        @else
            <div style="direction: rtl;margin-bottom: 1%;" class="timeline-item">
                <h3 style="margin-left: 75%;" class="timeline-header"></h3>
                <div style="float: right;" class="timeline-body">
                لا يوجد تقييمات ضمن هذا المنتج
                </div>
            </div>
        @endif
          </ul>
        </div>
      </div>
    </section>
@endsection
