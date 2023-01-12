@extends('layouts.app')

@section('content')
    <div class=" mt-0 mb-3">
        <h2>Site: {{$site->site_name}}</h2>
        <h3>Date: {{$date}}</h3>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-responsive mt-3" style="width:100%">

            <thead>

                <tr>

                    <th>Activity</th>

                    <th>Unit</th>

                    <th>Entry</th>

                    <th>Changed At</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($editLogs as $log)
                    @php
                        $value = json_decode($log->value, true)
                    @endphp
                    <tr>
                        <td>{{$log->getActivity->activity_name}}</td>
                        <td>{{$log->getActivity->getUnits->title}}</td>
                        <td>
                            <p>Qty: {{$value['qty']}}</p>
                            <p>Skilled Workers: {{$value['skilled_workers']}}</p>
                            <p>Remark: {{$value['remark']}}</p>
                            <p>Cement Bags: {{$value['cement_bags']}}</p>
                        </td>
                        <td>{{$log->created_at}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>
    </div>
@endsection