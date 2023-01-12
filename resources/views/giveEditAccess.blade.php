@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Give Edit Access</h4>
                    <form method="POST" action="{{ route('giveEditAccess') }}" >
                        @csrf
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Edit Access for</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="access_for" id="access_for">
                                        <option value="0">Cement Purchase</option>
                                        <option value="1">Cement In</option>
                                        <option value="2">Cement Out</option>
                                        <option value="3">Cement Transfer To Client</option>
                                    </select>
                                </div>
                                @if($errors->has('access_for'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('access_for') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Site</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="site" id="site">
                                        <@foreach ($sites as $site )
                                            <option value="{{$site->id}}">{{$site->site_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('site'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('site') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Date</h3>
                                </div>
                                <div class=" mb-0">
                                    <input class="form-control" name="date" id="date" type="date" />
                                </div>
                                @if($errors->has('date'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('date') }}</p>
                                @endif
                            </label>
                        </div>
                        
                        <div class="mb_30" style="float: right">
                            <button type="submit" class="btn btn-danger" >Give Edit Access To PM</button>
                        </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection