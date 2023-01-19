@extends('./Godown/layout')
@section('content')
    <p style="color: white;">Role: {{ ($role== 3) ? 'Godown' : '' }}</p>
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body row" >
                    <h4 style="padding: 20px 0px" >Cement Purchase Form</h4>
                    <form method="POST" action="{{ route('update.godown.cementPurchase', ['cement_purchase' => $cement_purchase->id]) }}" class="col-md-6">
                        @csrf

                        <div class="mb_30 row">
                            <label style="width: 50%" for="" class="col-md-6">
                                <div class="main-title">
                                    <h3 class="m-0">Date</h3>
                                </div>
                                <div class=" mb-0 row form-control">
                                    <div class="form-check form-control col-md-3 col-offset-3">
                                        <label class="form-check-label" for="flexRadioDefault1">Today</label>
                                        <input type="radio" class="form-check-input" name="date" id="flexRadioDefault1" value="{{$today}}" {{($cement_purchase->date == $today) ? 'checked' : ''}} required>
                                    </div>
                                    <div class="form-check form-control col-md-3">
                                        <label class="form-check-label" for="flexRadioDefault2">Yesterday</label>
                                        <input type="radio" class="form-check-input" name="date" id="flexRadioDefault2" value="{{$yesterday}}" {{($cement_purchase->date == $yesterday) ? 'checked' : ''}}>
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
                                    <h3 class="m-0">Number of Bags</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="number" min="0" class="form-control" name="bags" value="{{ $cement_purchase->bags }}" id="bags" placeholder="Number of Bags" required>
                                </div>
                                @if($errors->has('bags'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('bags') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Cement Supplier</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="supplier" required>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{($cement_purchase->supplier_id == $supplier->id) ? 'selected' : ''}}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('supplier'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('supplier') }}</p>
                                @endif
                            </label>
                        </div>
                        {{-- <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Receiving Site</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="site" required>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{($cement_purchase->site_id == $site->id) ? 'selected' : ''}}>{{ $site->site_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('site'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('site') }}</p>
                                @endif
                            </label>
                        </div> --}}
                        <div class="mb_30">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Remark</h3>
                                </div>
                                <div class=" mb-0">
                                    <input type="textArea" class="form-control" name="remark" required placeholder="Remark" value="{{ $cement_purchase->remark}}" />
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