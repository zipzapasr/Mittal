@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Update Contractor</h4>
                    <form method="POST" action="{{ route('update.cementsupplier') }}" >
                        <input type="hidden" name="cementsupplierId" value="{{ $cementsupplier->id }}" >
                        @csrf
                        
    
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Name</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $cementsupplier->name }}" placeholder="Name">
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
                                    <input type="tel" placeholder="+91" class="form-control" name="mobile" value="{{ $cementsupplier->mobile }}" id="mobile" placeholder="+91">
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
                                    <input type="textArea" class="form-control" name="details" id="details" value="{{ $cementsupplier->details}}" placeholder="details">
                                </div>
                                @if($errors->has('details'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('details') }}</p>
                                @endif
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