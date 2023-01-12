@extends('./EmployeeHome/layout')

@section('content')

    <p style="color: white;">Role: {{ ($role == 3) ? 'Project Manager' : 'Data Entry Operator' }}</p>
    {{-- {{dd($dataBySite[1])}} --}}
    <div class="row ">
        <div class="col-lg-10">
            <div class="white_card card_height_100  mb_20">
                <div class="white_card_header">
                    <div class="box_header m-0">
                        <div class="main-title" style="width: 100%">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-0" style="text-align: center;float: center">Cement Bags available per site</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="white_card_body">
                    <div class="table-responsive">
                        <table class="table bayer_table m-0">
                            <tbody>
                                @foreach ( $mysites as $site )
                                    <tr class="mt-1 mb-1">
                                        <td>
                                            <div class="payment_gatway row">
                                                <h4 class="byer_name  f_s_16 f_w_700 color_theme col-md-4">
                                                    <a href="{{route('employee.siteview', ['site' => $site->id])}}">
                                                        {{$site->site_name}}
                                                    </a>
                                                    
                                                </h4>
                                                <div class="col-md-4">
                                                    @php
                                                        $aval = $dataBySite[$site->id]['cement_purchase'] + $dataBySite[$site->id]['cement_in'] - $dataBySite[$site->id]['cement_out'] - $dataBySite[$site->id]['cement_consumption'] - $dataBySite[$site->id]['cement_transfer_to_client']
                                                    @endphp
                                                    <p style="font-weight: bold;">Cement Available = {{$aval}}</p>
                                                    @foreach ( $dataBySite[$site->id] as $field => $val )
                                                        <p>{{str_replace('_', ' ', mb_convert_case($field, MB_CASE_TITLE))}} = {{$val}}</p>
                                                    @endforeach
                                                </div>
                                                <h5 class="col-md-4"> </h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection