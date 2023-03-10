@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >Update Petty Contractor</h4>
                    <form method="POST" action="{{ route('update.contractor') }}" enctype="multipart/form-data" >
                        <input type="hidden" name="contractorId" value="{{ $contractor->id }}" >
                        @csrf
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Business Name</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="business_name" id="business_name" value="{{ $contractor->business_name }}" placeholder="Business Name">
                                </div>
                                @if($errors->has('business_name'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('business_name') }}</p>
                                @endif
                            </label>
                        </div>
                        
    
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Name</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $contractor->name }}" placeholder="Name">
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
                                    <input type="tel" placeholder="+91" class="form-control" name="mobile" value="{{ $contractor->mobile }}" id="mobile" placeholder="+91">
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
                                    <input type="textArea" class="form-control" name="details" id="details" value="{{ $contractor->details}}" placeholder="details">
                                </div>
                                @if($errors->has('details'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('details') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Identification Type</h3>
                                </div>
                                <div class="mb-0 row">
                                    {{-- <div class="col-md-4" class="form-control">
                                        <label for="Aadhar Card">Aadhar Card</label>
                                        <input type="radio"  name="identification_type" id='Aadhar Card' value='Aadhar Card' required>
                                    </div>
                                    <div class="col-md-4" class="form-control">
                                        <label for="Driving License">Driving License</label>
                                        <input type="radio"  name="identification_type" id='Driving License' value='Driving License' required>
                                    </div>
                                    <div class="col-md-4" class="form-control">
                                        <label for="Passport">Passport</label>
                                        <input type="radio"  name="identification_type" id='Passport' value='Passport' required>
                                    </div> --}}
                                    @foreach ($identification_types as $id => $identification_type )
                                        <div class="col-md" class="form-control">
                                            <label for="{{$identification_type}}">{{$identification_type}}</label>
                                            <input type="radio" name="identification_type" id='{{$identification_type}}' value='{{$identification_type}}' required {{($contractor->identification_type == $identification_type) ? 'checked' : ''}}>
                                        </div>
                                    @endforeach
                                </div> 
                                @if($errors->has('identification_type'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('identification_type') }}</p>
                                @endif
                            </label>
                        </div>
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Identification</h3>
                                </div>
                                <div class="mb-0">
                                    <input type="file" accept="img/*,apllication/pdf" name="identification" required />
                                </div> 
                                <img src="{{'/mittal/storage/app/public/'.$contractor->identification}}" />
                                <a href="{{'/mittal/storage/app/public/'.$contractor->identification}}" target="_blank">View Uploaded Identification</a>
                                @if($errors->has('identification'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('identification') }}</p>
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