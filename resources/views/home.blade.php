@extends('layouts.app')
<style>
    body {
        font-size: 10px !important;
    }
</style>

@section('content')
    {{--dd($afterSiteGroup)--}}
    <div class="row ">
        <div class="col-lg-6">
            <div class="white_card card_height_100  mb_20">
                <div class="white_card_header">
                    <div class="box_header m-0">
                        <div class="main-title" style="width: 100%">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-0" style="text-align: center;float: center">Site Entries</h3>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="white_card_body">
                    <div class="table-responsive">
                        <table class="table bayer_table m-0">
                            <tbody>
                                @foreach($afterSiteGroup as $date => $entriesByDate)
                                    @foreach($entriesByDate as $site_id => $entries)
                                        <tr class="mt-1 mb-1">
                                            <td>
                                                <div class="payment_gatway row">
                                                    <h4 class="byer_name  f_s_16 f_w_700 color_theme col-md-4"><a href="list/site/{{ $entries[0]->getSite->id }}">{{ $entries[0]->getSite->serial_no }} {{ $entries[0]->getSite->site_name }}</a></h4>
                                                    <h5 class="col-md-4">Date: {{$date}}</h5>
                                                    <h5 class="col-md-4">Submitted By: {{ $entries[0]->getSite->projectManager->name }} </h5>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                {{-- @foreach ( $mysites as $site )
                                    <tr class="mt-1 mb-1">
                                        <td>
                                            <div class="payment_gatway row">
                                                <h4 class="byer_name  f_s_16 f_w_700 color_theme col-md-4">$site->site_name</h4>
                                                <h5 class="col-md-4"></h5>
                                                <h5 class="col-md-4"> </h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="white_card card_height_100  mb_20">
                <div class="white_card_header">
                    <div class="box_header m-0">
                        <div class="main-title" style="width: 100%">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-0" style="text-align: center;float: center">Cement Transactions</h3>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="white_card_body">
                    <div class="table-responsive">
                        <table class="table bayer_table m-0">
                            <tbody>
                                @foreach($sitesData as $site_id => $arr)
                                    @if($arr['received'] != 0 || $arr['sent'] != 0)
                                        <tr class="mt-1 mb-1">
                                            <td>
                                                <div class="payment_gatway row">
                                                {{--"list/site/{{ $entries[0]->getSite->id }}"--}}
                                                {{--{{ $entries[0]->getSite->serial_no }}--}}
                                                    <h4 class="byer_name  f_s_16 f_w_700 color_theme col-md-4"><a href=""> {{ $sites[$site_id]->site_name }}</a></h4>
                                                    <h5 class="col-md-4">
                                                        <p>Received Diff: {{$arr['received']}}</p>
                                                        <p>Sent Diff: {{$arr['sent']}}</p>
                                                    </h5>
                                                    {{-- <h5 class="col-md-4">Submitted By: {{ $entries[0]->getSite->projectManager->name }} </h5> --}}
                                                </div>
                                            </td>

                                        </tr>
                                    @endif
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection