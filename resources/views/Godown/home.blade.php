@extends('./Godown/layout')

@section('content')

    <p style="color: white;">Role: {{ ($godown->role == 5) ? 'Godown' : '' }}</p>
    <div class="row ">
        <div class="col-lg-10">
            <div class="white_card card_height_100  mb_20">
                <div class="white_card_header">
                    <div class="box_header m-0">
                        <div class="main-title" style="width: 100%">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-0" style="text-align: center;float: center">Cement Bags Available: {{$cement_available}} </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection