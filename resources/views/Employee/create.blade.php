@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Create New Employee</h4>
                    <form method="POST" action="{{ route('save.employee') }}" >
                        @csrf
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Username</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}" placeholder="Username" required>
                                </div>
                                @if($errors->has('username'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('username') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Email</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required>
                                </div>
                                @if($errors->has('email'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('email') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Mobile</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="tel" placeholder="+91" class="form-control" name="mobile" value="{{ old('mobile') }}" id="mobile" required>
                                </div>
                                @if($errors->has('mobile'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('mobile') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Password</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" placeholder="Password" required>
                                </div>
                                @if($errors->has('password'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('password') }}</p>
                                @endif
                            </label>
                        </div>
                        @php
                            $roles = app('App\Models\User')->roles;
                        @endphp
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Role</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="role">
                                        @foreach($roles as $k => $v)
                                            <option value="{{ $k }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
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