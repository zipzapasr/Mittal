@extends('./EmployeeHome/layout')

@section('content')

    <p style="color: white;">Role: {{ ($role== 3) ? 'Project Manager' : 'Data Entry Operator' }}</p>

    <div class="row ">

        <div class="col-lg-12">

            <div class="white_card card_height_100 mb_30">

                <div class="white_card_body row" >

                    <h4 style="padding: 20px 0px" >Cement Transfer To Client Form</h4>

                    <form method="POST" action="{{ route('store.cementTransfer', ['user' => $user]) }}" class="col-md-6">

                        @csrf

                        <div class="mb_30 row">

                            <label style="width: 50%" for="" class="col-md-6">

                                <div class="main-title">

                                    <h3 class="m-0">Date</h3>

                                </div>

                                <div class=" mb-0 row form-control">

                                    {{--<input type="date" class="form-control" name="date" id="date" value="{{ old('date') ?? date('Y-m-d') }}" placeholder="Date">--}}

                                    <div class="form-check form-control col-md-3 col-offset-3">

                                        <label class="form-check-label" for="flexRadioDefault1">Today</label>

                                        <input type="radio" class="form-check-input" name="date" id="flexRadioDefault1" value="{{$today}}" checked {{(old('date') == $today) ? 'checked' : ''}} required>

                                    </div>

                                    <div class="form-check form-control col-md-3">

                                        <label class="form-check-label" for="flexRadioDefault2">Yesterday</label>

                                        <input type="radio" class="form-check-input" name="date" id="flexRadioDefault2" value="{{$yesterday}}" {{(old('date') == $yesterday) ? 'checked' : ''}}>

                                    </div>

                                    

                                </div>

                                @if($errors->has('date'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('date') }}</p>

                                @endif

                            </label>

                        </div>



                        <div class="mb_30">

                            <label style="width: 100%" for="">

                                <div class="main-title">

                                    <h3 class="m-0">Num. of Bags</h3>

                                </div>

                                <div class=" mb-0">

                                    <input type="number" min="1" class="form-control" name="bags" value="{{ old('bags') ?? 0 }}" id="bags" placeholder="Num. of Bags" required>

                                </div>

                                @if($errors->has('bags'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('bags') }}</p>

                                @endif

                            </label>

                        </div>

                        <div class="mb_30">

                            <label style="width: 100%" for="">

                                <div class="main-title">

                                    <h3 class="m-0">Site</h3>

                                </div>

                                <div class=" mb-0">

                                    <select class="form-control" name="site" required>

                                        @foreach($sites as $site)

                                            <option value="{{ $site->id }}">{{ $site->site_name }}</option>

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

                                    <h3 class="m-0">Remark</h3>

                                </div>

                                <div class=" mb-0">

                                    <input type="textArea" class="form-control" name="remark" placeholder="Remark" value="{{old('remark') ?? '' }}" />

                                </div>

                                @if($errors->has('remark'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('remark') }}</p>

                                @endif

                            </label>

                        </div>

                        <div class="mb_30" style="float: right">

                            <button type="submit" class="btn btn-primary">Submit</button>

                        </div>



                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection