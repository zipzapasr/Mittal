@extends('layouts.app')
<style>
    caption {
        font-weight: bold;
    }
</style>
@php
    //dd($logs);
    $act_est = array_key_exists($remarks[0], $logs) ? $logs[$remarks[0]] : [];
    $project_manager = array_key_exists($remarks[1], $logs) ? $logs[$remarks[1]] : [];
    $data_entry_operator = array_key_exists($remarks[2], $logs) ? $logs[$remarks[2]] : [];
    $entry = array_key_exists($remarks[3], $logs) ? $logs[$remarks[3]] : [];
    $cement_purchase = array_key_exists($remarks[4], $logs) ? $logs[$remarks[4]] : [];
    $cement_in = array_key_exists($remarks[5], $logs) ? $logs[$remarks[5]] : [];
    $cement_out = array_key_exists($remarks[6], $logs) ? $logs[$remarks[6]] : [];
    $cement_transfer_to_client = array_key_exists($remarks[7], $logs) ? $logs[$remarks[7]] : [];
    //dd('working');
@endphp

@section('content')
    <h1>View Edit Logs</h1>

    <div class="table-responsive">
        <table  class="table table-hover table-bordered caption-top " style="width:100%">{{--Site Estimate Quantities Changed--}}
            <caption class="text-center">Site Estimate Quantities Changed</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Activity Name</th>

                    <th>Unit</th>

                    <th>For Date</th>

                    <th>Changed On</th>

                    <th>Changed By</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($act_est as $log)
                    {{-- {{dd($log['get_site'])}} --}}
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_activity']['activity_name']}}</td>
                        <td>{{$log['get_activity']['get_units']['title']}}</td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                        <td>{{$log['get_employee']['name']}}</td>
                    </tr>
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered caption-top" style="width:100%"> {{-- Project Managers Changed --}}
            <caption class="text-center">Project Managers Changed</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>For</th>

                    <th>Date</th>

                    <th>Changed On</th>

                </tr>

            </thead>

            <tbody class="">

                @foreach ($project_manager as $log)
                    {{-- {{dd($log)}} --}}
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_project_manager']['name']}}</td>
                        <td></td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered caption-top" style="width:100%"> {{-- Data Entry Operators Changed --}}
            <caption class="text-center">Data Entry Operators Changed</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Data Entry Operator</th>

                    <th>For</th>

                    <th>Date</th>

                    <th>Changed On</th>

                </tr>

            </thead>

            <tbody class="">

                @foreach ($data_entry_operator as $log)
                    {{-- {{dd($log)}} --}}
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_data_entry_operator']['name']}}</td>
                        <td></td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>

        <table class="table table-hover table-bordered caption-top" style="width:100%">{{--Site Entries Changed--}}
            <caption class="text-center">Site Entries Changed by PM </caption>
            <thead>

                <tr>
                    <th>Site</th>

                    <th>Activity</th>

                    <th>Unit</th>

                    <th>Entry</th>

                    <th>For Date</th>

                    <th>Changed At</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($entry as $log)
                    @php
                        $value = json_decode($log['value'], true)
                    @endphp
                    <tr>
                        <td>{{$log["get_site"]['site_name']}}</td>
                        <td>{{$log['get_activity']['activity_name']}}</td>
                        <td>{{$log['get_activity']['get_units']['title']}}</td>
                        <td>
                            <p>Qty: {{$value['qty']}}</p>
                            <p>Skilled Workers: {{$value['skilled_workers']}}</p>
                            <p>Remark: {{$value['remark']}}</p>
                            <p>Cement Bags: {{$value['cement_bags']}}</p>
                        </td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                    
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered caption-top " style="width:100%">{{--Cement Purchases Changed--}}
            <caption class="text-center">Cement Purchases Changed by PM</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>Cement Purchase</th>

                    <th>Date</th>

                    <th>Changed On</th>

                    {{-- <th>Action</th> --}}

                </tr>

            </thead>



            <tbody class="">

                @foreach ($cement_purchase as $log)
                    @php
                        $value = json_decode($log['value'], true);
                        // dump($value['supplier_id']);
                        // dump($log['value']);
                    @endphp
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_employee']['name']}}</td>
                        <td>
                            <p>Num. of Bags: {{($value['bags'])}}</p>
                            <p>Supplier Id: {{($value['supplier_id'])}}</p>
                            <p>Site Id: {{($value['site_id'])}}</p>
                            <p>Remark: {{($value['remark'])}}</p>
                        </td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered caption-top " style="width:100%">{{--Cement Ins Changed--}}
            <caption class="text-center">Cement Ins Changed by PM</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>Cement In</th>

                    <th>Date</th>

                    <th>Changed On</th>

                    {{-- <th>Action</th> --}}

                </tr>

            </thead>



            <tbody class="">

                @foreach ($cement_in as $log)
                    @php
                        $value = json_decode($log['value'], true);
                        // dump($value['supplier_id']);
                        // dump($log['value']);
                    @endphp
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_employee']['name']}}</td>
                        <td>
                            <p>Num. of Bags: {{($value['bags'])}}</p>
                            <p>From Site Id: {{($value['from_site_id'])}}</p>
                            <p>To Site Id: {{($value['to_site_id'])}}</p>
                            <p>Remark: {{($value['remark'])}}</p>
                        </td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered caption-top " style="width:100%">{{--Cement Outs Changed--}}
            <caption class="text-center">Cement Outs Changed by PM</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>Cement Out</th>

                    <th>Date</th>

                    <th>Changed On</th>

                    {{-- <th>Action</th> --}}

                </tr>

            </thead>



            <tbody class="">

                @foreach ($cement_out as $log)
                    @php
                        $value = json_decode($log['value'], true);
                        // dump($value['supplier_id']);
                        // dump($log['value']);
                    @endphp
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_employee']['name']}}</td>
                        <td>
                            <p>Num. of Bags: {{($value['bags'])}}</p>
                            <p>From Site Id: {{($value['from_site_id'])}}</p>
                            <p>To Site Id: {{($value['to_site_id'])}}</p>
                            <p>Remark: {{($value['remark'])}}</p>
                        </td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered caption-top " style="width:100%">{{--Cement Transfers to Clients Changed--}}
            <caption class="text-center">Cement Transfers to Clients Changed by PM</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>Cement Transfer to Client</th>

                    <th>Date</th>

                    <th>Changed On</th>

                    {{-- <th>Action</th> --}}

                </tr>

            </thead>



            <tbody class="">

                @foreach ($cement_transfer_to_client as $log)
                    @php
                        $value = json_decode($log['value'], true);
                        // dump($value['supplier_id']);
                        // dump($log['value']);
                    @endphp
                    <tr>
                        <td>{{$log['get_site']['site_name']}}</td>
                        <td>{{$log['get_employee']['name']}}</td>
                        <td>
                            <p>Num. of Bags: {{($value['bags'])}}</p>
                            <p>From Site Id: {{($value['site_id'])}}</p>
                            {{-- <p>To Site Id: {{($value['to_site_id'])}}</p> --}}
                            <p>Remark: {{($value['remark'])}}</p>
                        </td>
                        <td>{{$log['date']}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                @endforeach

            </tbody>



        </table>
    </div>
@endsection
@section('javascript')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

    <script>
        $('.table').dataTable({

            scrollX: true,

            dom: 'lBfrtip',

            buttons: [

                'copy', 'csv', 'excel', 'pdf'

            ],
        });
    </script>
@endsection