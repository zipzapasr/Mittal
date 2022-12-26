@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Update Activity</h4>
                    <form method="POST" action="{{ route('update.activity', ['activity' => $activity]) }}" >
                        <input type="hidden" name="activityId" value="{{ $activity->id }}" >
                        @csrf
                        <div class="row-container">
                            <div class="mb_30 col-md-2 floatLeft">
                                <label style="width: 100%" for="">
                                    <div class="main-title">
                                        <h3 class="m-0">Activity Type</h3>
                                    </div>
                                    <div class=" mb-0">
                                        <select name="activity_type" class="form-control">
                                            @foreach($activity_types_list as $ind => $activity_type)
                                                <option value="{{$activity_type->id}}" {{ ($activity_type->id == $activity->activity_type) ? 'selected' : '' }}>{{$activity_type->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </label>
                            </div>
                            <div class="mb_30 col-md-2 floatLeft">
                                <label style="width: 100%" for="">
                                    <div class="main-title">
                                        <h3 class="m-0">Activity Name</h3>
                                    </div>
                                    <div class=" mb-0">
                                        <input type="text" class="form-control" name="activity_name" id="activity_name" value="{{ $activity->activity_name }}" placeholder="Activity Name">
                                    </div>
                                    @if($errors->has('activity_name'))
                                        <p style="color:red;margin-left: 20px">{{ $errors->first('activity_name') }}</p>
                                    @endif
                                </label>
                            </div>


                            <div class="mb_30 col-md-2 floatLeft">
                                <label style="width: 100%" for="">
                                    <div class="main-title">
                                        <h3 class="m-0">Activity Description</h3>
                                    </div>
                                    <div class=" mb-0">
                                        <input type="text" class="form-control" name="activity_description" id="activity_description" value="{{ $activity->activity_description }}" placeholder="Activity Description">
                                    </div>
                                    @if($errors->has('activity_description'))
                                        <p style="color:red;margin-left: 20px">{{ $errors->first('activity_description') }}</p>
                                    @endif
                                </label>
                            </div>

                            <div class="mb_30 col-md-2 floatLeft">
                                <label style="width: 100%" for="">
                                    <div class="main-title">
                                        <h3 class="m-0">Unit</h3>
                                    </div>
                                    <div class=" mb-0">
                                        <select class="form-control" name="unit">
                                            @foreach($unitlist as $k => $v)
                                                <option value="{{ $v->id }}" {{ ($v->id == $activity->unit) ? 'selected' : '' }}>{{ $v->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="mb_30" style="float: right">
                            <button type="submit" class="btn btn-primary" >Update</button>
                        </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection