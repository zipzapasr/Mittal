@extends('layouts.app')
<style>
    caption {
        font-weight: bold;
    }
</style>

@section('content')
    <div class="table-responsive">
        <h1>View Edit Access Logs</h1>

        <table  class="table table-hover table-bordered table-responsive caption-top " style="width:100%">
            <caption class="text-center">Active Edit Access given to PM</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>For</th>

                    <th>Date</th>

                    <th>Access Given On</th>

                    <th>Action</th>

                </tr>

            </thead>



            <tbody class="">

                @foreach ($currEditAccess as $log)
                    <tr>
                        <td>{{$log->getSite->site_name}}</td>
                        <td>{{$log->getSite->ProjectManager->name}}</td>
                        <td>{{$keys[$log->key]}}</td>
                        <td>{{$log->date}}</td>
                        <td>{{$log->created_at}}</td>
                        <td><a href="{{route('edit.revokeAccess', ['editAccess' => $log->id])}}" class="btn btn-sm btn-danger">Revoke Access</a></td>
                    </tr>
                @endforeach

            </tbody>



        </table>

        <table  class="table table-hover table-bordered table-responsive caption-top" style="width:100%">
            <caption class="text-center">All Edit Access given to PM</caption>

            <thead class=" border">

                <tr class="">

                    <th>Site</th>

                    <th>Project Manager</th>

                    <th>For</th>

                    <th>Date</th>

                    <th>Access Given On</th>

                </tr>

            </thead>

            <tbody class="">

                @foreach ($allEditAccess as $log)
                    <tr>
                        <td>{{$log->getSite->site_name}}</td>
                        <td>{{$log->getSite->ProjectManager->name}}</td>
                        <td>{{$keys[$log->key]}}</</td>
                        <td>{{$log->date}}</td>
                        <td>{{$log->created_at}}</td>
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