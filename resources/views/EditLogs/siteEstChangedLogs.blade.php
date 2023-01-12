@extends('layouts.app')
<style>
    caption {
        font-weight: bold;
    }
</style>

@section('content')
    <div class="table-responsive">
        <h1>Site: {{$site->site_name}}</h1>

        <table  class="table table-striped table-bordered table-responsive mt-3 caption-top" style="width:100%">
            <caption class="text-center">Site Activity Estimate Quantities Edit Log</caption>

            <thead>

                <tr class="">

                    <th>Activity</th>

                    <th>Unit</th>

                    <th>Estimate Qty</th>

                    <th>Changed At</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($estEditLogs as $log)
                    <tr>
                        <td>{{$log->getActivity->activity_name}}</td>
                        <td>{{$log->getActivity->getUnits->title}}</td>
                        <td>{{$log->value}}</td>
                        <td>{{$log->created_at}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>

        <table  class="table table-striped table-bordered table-responsive mt-3 caption-top" style="width:100%">
            <caption class="text-center">Site Project Manager Edit Log</caption>

            <thead>

                <tr class="">

                    <th>Project Manager</th>

                    <th>Email</th>

                    <th>Mobile</th>

                    <th>Changed At</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($projectManagerEditLogs as $log)
                    <tr>
                        <td>{{$log->getEmployee->name}}</td>
                        <td>{{$log->getEmployee->email}}</td>
                        <td>{{$log->getEmployee->mobile}}</td>
                        <td>{{$log->created_at}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>

        <table  class="table table-striped table-bordered table-responsive mt-3 caption-top" style="width:100%">
            <caption class="text-center">Site Data Entry Operator Edit Log</caption>

            <thead>

                <tr class="">

                    <th>Data Entry Operator</th>

                    <th>Email</th>

                    <th>Mobile</th>

                    <th>Changed At</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($dataEntryOperatorEditLogs as $log)
                    <tr>
                        <td>{{$log->getEmployee->name}}</td>
                        <td>{{$log->getEmployee->email}}</td>
                        <td>{{$log->getEmployee->mobile}}</td>
                        <td>{{$log->created_at}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>

    </div>
@endsection