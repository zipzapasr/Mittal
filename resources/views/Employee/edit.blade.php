@extends('layouts.app')

@section('content')
    {{-- @php
        $today = new \DateTime(date("Y-m-d"));
        dump($today->format('Y-m-d'));
        $after = $today->modify('+7 days');
        dump($after->format('Y-m-d'));
    @endphp --}}
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Update Employee</h4>
                    <form method="POST" action="{{ route('update.employee') }}" >
                        <input type="hidden" name="employeeId" value="{{ $user->id }}" >
                        @csrf
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Username</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="username" id="username" value="{{ $user->name }}" placeholder="Username">
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
                                    <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}" placeholder="Email">
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
                                    <input type="tel" placeholder="+91" class="form-control" name="mobile" value="{{ $user->mobile }}" id="mobile" placeholder="+91">
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
                                    <input type="text" class="form-control" name="password" id="password" value="" placeholder="Password">
                                </div>
                                @if($errors->has('username'))
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
                                            <option {{ ($k == $user->role) ? 'selected' : '' }} value="{{ $k }}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </label>
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