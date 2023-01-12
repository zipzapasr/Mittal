@extends('./Godown/layout')

@section('content')
    {{-- {{dd($allsites)}} --}}

    <p style="color: white;">Role: {{ ($role== 5) ? 'Godown' : '' }}</p>

    <div class="row ">

        <div class="col-lg-12">

            <div class="white_card card_height_100 mb_30">

                <div class="white_card_body row" >

                    <h4 style="padding: 20px 0px" >Cement Out Form</h4>

                    <form method="POST" action="{{ route('store.godown.cementOut', ['user' => $user]) }}" class="col-md-6">

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

                                        <input type="radio" class="form-check-input" name="date" id="flexRadioDefault1" checked value="{{$today}}">

                                    </div>

                                    <div class="form-check form-control col-md-3">

                                        <label class="form-check-label" for="flexRadioDefault2">Yesterday</label>

                                        <input type="radio" class="form-check-input" name="date" id="flexRadioDefault2" value="{{$yesterday}}">

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

                                    <input type="number" min="0" class="form-control" name="bags" value="{{ old('bags') ?? 0 }}" id="bags" placeholder="Num. of Bags" required>

                                </div>

                                @if($errors->has('bags'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('bags') }}</p>

                                @endif

                            </label>

                        </div>



                        {{-- <div class="mb_30">

                            <label style="width: 100%" for="">

                                <div class="main-title">

                                    <h3 class="m-0">From Site(My Sites)</h3>

                                </div>

                                <div class=" mb-0">

                                    <select class="form-control" name="from_site" required>

                                        @foreach($mysites as $site)

                                            <option value="{{ $site->id }}">{{ $site->site_name }}</option>

                                        @endforeach

                                    </select>

                                </div>

                                @if($errors->has('from_site'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('from_site') }}</p>

                                @endif

                            </label>

                        </div> --}}

                        <div class="mb_30">

                            <label style="width: 100%" for="">

                                <div class="main-title">

                                    <h3 class="m-0">To Site(All Sites)</h3>

                                </div>

                                <div class=" mb-0">

                                    <select class="form-control" name="to_site" required>

                                        @foreach($allsites as $site)

                                            <option value="{{ $site->id }}">{{ $site->site_name }}</option>

                                        @endforeach

                                    </select>

                                </div>

                                @if($errors->has('to_site'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('to_site') }}</p>

                                @endif

                            </label>

                        </div>

                        <div class="mb_30">

                            <label style="width: 100%" for="">

                                <div class="main-title">

                                    <h3 class="m-0">Remark</h3>

                                </div>

                                <div class=" mb-0">

                                    <input type="textArea" class="form-control" name="remark" required placeholder="Remark" value="{{old('remark') ?? '' }}"></input>

                                </div>

                                @if($errors->has('remark'))

                                    <p style="color:red;margin-left: 20px">{{ $errors->first('remark') }}</p>

                                @endif

                            </label>

                        </div>

                        <div class="mb_30" style="float: right">

                            <button type="submit" class="btn btn-primary" >Submit</button>

                        </div>



                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection