@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Create New Unit</h4>
                    <form method="POST" action="{{ route('save.unit') }}" >
                        @csrf
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Unit</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="title" id="Unit" value="{{ old('title') }}" placeholder="Unit title">
                                </div>
                                @if($errors->has('title'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('title') }}</p>
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