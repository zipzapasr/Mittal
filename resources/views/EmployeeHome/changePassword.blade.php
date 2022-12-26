@extends('./EmployeeHome/layout')
@section('content')
    <p style="color: white;">Role: {{ ($role == 3) ? 'Project Manager' : 'Data Entry Operator' }}</p>
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Change Your Password</h4>
                    <form method="POST" action="{{ route('authenticate.employee.password', ['user' => $user]) }}" >
                        @csrf

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Old Password</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="password" class="form-control" name="old_password" id="old_password" value="{{ old('old_password') }}" placeholder="Old Password">
                                </div>
                                @if($errors->has('old_password'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('old_password') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">New Password</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="password" class="form-control" name="new_password" value="{{ old('new_password') }}" id="new_password" placeholder="New Password">
                                </div>
                                @if($errors->has('new_password'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('new_password') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Confirm New Password</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}" placeholder="Confirm New Password">
                                </div>
                                @if($errors->has('confirm_password'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('confirm_password') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30" style="float: right">
                            <button type="submit" class="btn btn-primary" >Confirm</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection