@extends('layouts.app')

<style>
    .modal-backdrop.show {
        z-index: 9
    }
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@section('content')
    {{-- {{dd($siteActivity, $entriesByDate)}} --}}
    <div class="container" style="margin-top:70px;">
        @php
            $remQty = [];
            foreach($siteActivity as $id => $qty)
                $remQty[$id] = $qty;
        @endphp


        <h3>Filter Entries</h3>

        <form action="{{ route('list.siteEntries.filter',['site' => $site_id] )}}" method="post">

            @csrf

            <div class="row">

                <div class="col-md-3">

                    <label>

                        From Date

                        <input type="date" class="form-control" name="from" />

                    </label>

                </div>

                <div class="col-md-3">

                    <label>

                        To

                        <input type="date" class="form-control" name="to" />

                    </label>

                </div>

                <div class="col-md-2" style="margin-top: 25px">

                    <button type="submit" class="btn btn-primary btn-sm"> Get Data </button>

                </div>

            </div>

        </form>

        @foreach($entriesByDate as $date => $entries)
            <div class="{{($date <= $from || $date >= $to) ? 'invisible' : ''}}">
                <h2 class="mt-2">Site Entries {{ $date }}</h2>

                @php

                    $verified = $entries[0]->status == '2';

                    //dump($verified);

                @endphp

                <div class="table-responsive">

                    <table class="display nowrap EntryTable" style="width:100%">

                        <thead>

                            <tr class="">

                                <th>Activity</th>

                                <th>Unit</th>

                                <th>Field Type</th>

                                <th>Petty Contractor</th>

                                <th>Est. Qty</th>

                                <th>Used Qty</th>

                                <th>Rem. Qty</th>

                                <th>Remarks</th>

                                <th>Skilled Workers</th>

                                <th>Skilled Workers Ot(Hr)</th>

                                <th>Skilled Workers Total Days</th>

                                <th>UnSkilled Workers</th>

                                <th>UnSkilled Workers Ot(Hr)</th>

                                <th>UnSkilled Workers Total Days</th>

                                <th>Cement Bags</th>

                                <th>Wastage</th>

                                <th>Images</th>

                                <th>Edit Wastage</th>

                            </tr>

                        </thead>



                        <tbody class="">

                            @foreach($entries as $entry)

                                @php

                                    $images = ($entry->images) ? explode(',', $entry->images) : [];
                                    $currActivity = $entry->getActivity;
                                    $remQty[$currActivity->id] -= $entry->qty;

                                @endphp


                                <tr>

                                    <td style="text-transform: capitalize;">

                                        <p>{{ $currActivity->activity_name }}</p>

                                    </td>

                                    <td style="text-transform: capitalize" class="unitname">

                                        <p>{{ $currActivity->getUnits->title }}</p>

                                    </td>

                                    <td>

                                        <p>{{ ($entry->getFieldType) ? ($entry->getFieldType->title) : 'None' }}</p>

                                    </td>

                                    <td>

                                        <p>{{ ($entry->getFieldType) ? (($entry->getFieldType->id == '4') ? ($entry->getContractor ? ($entry->getContractor->business_name) : '') : 'None') : '' }}</p>

                                    </td>

                                    <td>

                                        <p>{{ ($siteActivity[$currActivity->id]) ? ($siteActivity[$currActivity->id]) : 0 }}</p>

                                    </td>

                                    <td>

                                        <p>{{$entry->qty}}</p>

                                    </td>

                                    <td>

                                        <p>{{ ($remQty[$currActivity->id]) ? ($remQty[$currActivity->id]) : -($entry->qty)}}</p>

                                    </td>

                                    <td>

                                        <p>{{$entry->remark}}</p>

                                    </td>

                                    <td>

                                        <p>{{ $entry->skilled_workers }}</p>

                                    </td>

                                    <td>

                                        <p>{{ $entry->skilled_workers_overtime }}</p>

                                    </td>

                                    <td>

                                        <p>{{ (($entry->skilled_workers * 8) + ($entry->skilled_workers_overtime))/8 }}</p>

                                    </td>

                                    <td>

                                        <p>{{ $entry->unskilled_workers }}</p>

                                    </td>

                                    <td>

                                        <p>{{ $entry->unskilled_workers_overtime }}</p>

                                    </td>

                                    <td>

                                        <p>{{ (($entry->unskilled_workers * 8) + ($entry->unskilled_workers_overtime))/8 }}</p>

                                    </td>

                                    <td>

                                        <p>{{ $entry->cement_bags }}</p>

                                    </td>

                                    <td>

                                        <p class='wastageData'>{{ $entry->wastage }}</p>

                                    </td>

                                    <td>

                                        <p>

                                            @foreach($images as $img)

                                                <a target="_blank"  href="{{'/mittal/storage/app/public/'.$img}}">

                                                    <img src="{{'/mittal/storage/app/public/'.$img}}" alt="" style="width:15px;height:15px;">

                                                </a>

                                            @endforeach

                                        </p>

                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-danger btnUpdateWastage {{ ($verified == true) ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        data-wastage = "{{$entry->wastage}}" data-id="{{$entry->id}}" data-clicked="0">
                                            <i class="bi bi-recycle"></i></
                                        </button>
                                    </td>

                                </tr>

                            @endforeach


                            <a target="_blank" href="{{ route( 'verify.site', ['site' => $site_id, 'date' => $date] ) }}"  class="m-1 btn btn-primary {{ ($verified == true) ? 'disabled' : '' }}">Verify Entries</a>
                            <a target="_blank" href="{{ route( 'giveEditAccess.site', ['site' => $site_id, 'date' => $date] ) }}"  class="m-1 btn btn-danger">Give Edit Access To PM</a>
                            <a target="_blank" href="{{ route( 'logs.siteEntries', ['site' => $site_id, 'date' => $date] ) }}"  class="m-1 btn btn-success">View Edit Logs</a>

                        </tbody>



                    </table>

                </div>
            
            </div>
            
        @endforeach



    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Today's Site Entries</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div>
                        Current Cement Wastage: <span id="wastage"></span>
                    </div> --}}
                    <input type="number" step="any" required id="wastageInput" value="0" />
                    <button class="formSubmitBtn" >Update Cement Wastage</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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

        // Initialize the DataTable

        //console.log('working');

        $(document).ready(function() {

            $('.EntryTable').dataTable({

                scrollX: true,

                dom: 'lBfrtip',

                buttons: [

                    'copy', 'csv', 'excel', 'pdf'

               ],

            });

            $('a[data-bs-toggle=modal], button[data-bs-toggle=modal]').click(function () {

                $('.btnUpdateWastage').attr('data-clicked', '0');
                var data_wastage = '';
                var data_id = '';
                //console.log('Form', $('#wastageForm'))
                $(this).attr('data-clicked', '1')

                data_wastage = $(this).data('wastage');

                $('#wastage').text(data_wastage);

            })

            $('.formSubmitBtn').click(function() {

                //console.log('Form', $('#wastageForm'))
                var btnUpdateWastage = ''

                $('.btnUpdateWastage').each(function( index ) {
                    if($( this ).attr('data-clicked') == '1'){
                        btnUpdateWastage = $(this)
                    }
                    // console.log( index + ": " + $( this ).attr('data-clicked') );
                })
                console.log(btnUpdateWastage);
                

                var data_id= btnUpdateWastage.attr('data-id');

                // console.log(data_id)

                var token = $('input[name=csrf_token]').val();

                var site = data_id;

                var wastageInput = $('#wastageInput').val();


                if (site && wastageInput) {

                    $.ajax({

                        url: "{{route('mysites.updateCementWastage')}}",

                        type: 'post',

                        data: {

                            _token: token,

                            site: site,

                            wastage: wastageInput

                        },

                        success: function(res) {
                            //console.log(res)
                            btnUpdateWastage.parents('tr').find('.wastageData').text(wastageInput);
                            btnUpdateWastage.attr('data-wastage', wastageInput);
                            $('#wastageInput').val('0');
                            $('#exampleModal').removeClass('show');

                        },

                        error: function(err) {

                            console.log(err);

                        }

                    })

                }

            })

        });

    </script>

@endsection