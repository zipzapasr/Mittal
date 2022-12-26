@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Create New Site Transfer</h4>
                    <form method="POST" action="{{ route('save.sitetransfer') }}" >
                        @csrf

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Date</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}" placeholder="Date">
                                </div>
                                @if($errors->has('date'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('date') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">From Site</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="site_from" value="{{ old('site_from') }}" id="site_from">
                                        @foreach($sitetransferlist as $k => $v)
                                            <option value="{{$v->id}}">{{$v->site_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('site_from'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('site_from') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">To Site</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="site_to" value="{{ old('site_to') }}" id="site_to">
                                        @foreach($sitetransferlist as $k => $v)
                                            <option value="{{$v->id}}">{{$v->site_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('site_to'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('site_to') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Num of Bags</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="number" class="form-control" name="num_bags" id="num_bags" value="{{ old('num_bags') }}" placeholder="Num Of Bags">
                                </div>
                                @if($errors->has('num_bags'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('num_bags') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30" style="float: right">
                            <button type="submit" class="btn btn-primary" >Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection