@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Create New Cement Supplier</h4>
                    <form method="POST" action="{{ route('save.cementsupplier') }}" >
                        @csrf
                        
    
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Name</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" placeholder="Name">
                                </div>
                                @if($errors->has('name'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('name') }}</p>
                                @endif
                            </label>
                        </div>
    
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Mobile</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="tel" placeholder="+91" class="form-control" name="mobile" value="{{ old('mobile') }}" id="mobile" placeholder="+91">
                                </div>
                                @if($errors->has('mobile'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('mobile') }}</p>
                                @endif
                            </label>
                        </div>
    
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Details</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="textArea" class="form-control" name="details" id="details" value="{{ old('details') }}" placeholder="Details">
                                </div>
                                @if($errors->has('details'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('details') }}</p>
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