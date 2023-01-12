@extends('./EmployeeHome/layout')
    <style>
        nav{
            z-index: 1 !important;
        }
        .modal-backdrop.show {
            z-index: 9 !important;
        }
        .modal-dialog {
            z-index: 9999999 !important;
        } 

        table th {
            font-size: 75%;
        }
    </style>

@section('content')
    @php
        $entryCountT = $entriesT->count();
        $entryCountY = $entriesY->count();
    @endphp
    <h5 style="color: white;">User: {{ $employee->name }}</h5>
    <h5 style="color: white;">Role: {{ ($employee->role == '3') ? 'Project Manager' : 'Data Entry Operator' }}</h5>
    <div class="container" style="margin-top:70px;">
        <div class="row mt-3 border">
            <div class="col-md-4 border">
                <label for="site_serial_no">Site Serial No</label>
                <p id="site_serial_no">{{$site->serial_no}}</p>
            </div>
            <div class="col-md-4 border">
                <label for="site_name">Site Name</label>
                <p id="site_name">{{$site->site_name}}</p>
            </div>
            <div class="col-md-4 border">
                <label for="site_location">Site Location</label>
                <p id="site_location">{{$site->site_location}}</p>
            </div>
        </div>
        <div class="row mt-3 border">
            <div class="col-md-4 border">
                <label for="site_address">Site Address</label>
                <p id="site_address">{{$site->site_address}}</p>
            </div>
            <div class="col-md-4 border">
                <label for="site_admin">Project Manager</label>
                <p id="site_admin">{{$site->projectManager->name}}</p>
            </div>
            <div class="col-md-4 border">
                <label for="employees">Data Entry Operator</label>
                <p id="employees">{{ ($site->dataEntryOperator) ? ($site->dataEntryOperator->name) : 'None'}}</p>
            </div>
        </div>
        <div class="row mt-3 border">
            <div class="col-md-12 border">
                <label for="site_description">Site Description</label>
                <p id="site_description">{{$site->site_description}}</p>
            </div>
        </div>
        <div class="row mt-3"> {{-- today --}}
            <h5 style="font: bold;">Site Entries
                {{-- <select name="progress_date" id="progress_date" class="form-control"></select> --}}
                Today: {{ $today }}
            </h5>
            @if($submitted['today'] == 'true')
            <div class="row">
                <p class="bg-danger text-center">You have already submitted entries for today</p>
            </div>
            @else
                <form method="POST" action="{{$site->id}}/today" enctype="multipart/form-data">
                    @csrf
                    @if(Session::has('activityRequired'))
                        <p style="color:red;margin-left: 20px">{{ Session::get('activityRequired') }}</p>
                    @endif
                    <table class="table table-strip container">
                        <thead>
                            <tr class="row">
                                <th class="col-sm-1">Activity</th>
                                <th class="col-md-1">Unit</th>
                                <th class="col-md-1">Quantity</th>
                                <th class="col-lg-1">Remarks</th>
                                <th class="col-sm-1">Field Type</th>
                                <th class="col-sm-1" id="contractorT">Petty Contractor</th>
                                <th class="col-md-1">Skilled Workers</th>
                                <th class="col-md-1">Skilled Workers Overtime(Hr)</th>
                                <th class="col-md-1">UnSkilled Workers</th>
                                <th class="col-md-1">UnSkilled Workers Overtime(Hr)</th>
                                <th class="col-md-1">Cement Bags</th>
                                <th class="col-md-1">Images</th>
                            </tr>
                        </thead>

                        <tbody class="appendedRowT">
                            @foreach($entriesT as $k => $entry)
                                {{--dump($entry->images)--}}
                                @php
                                    $ind = $k;
                                @endphp
                                <tr class="row">
                                    <td style="text-transform: capitalize;" class="col-sm-1">
                                        <select class="form-control" id="selectActivity" name="selectActivity[]" required style="text-transform: capitalize; width:100%;">
                                            @foreach($activity as $k => $v)
                                            <option class="form-control" attunit="{{ $v->getActivity->getunits->title }}" value="{{ $v->getActivity->id }}" {{ ( (int)$v->getActivity->id == (int)$entry->activity_id) ? 'selected' : "" }}> {{ $v->getActivity->activity_name }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-1">
                                        {{ $entry->getActivity->getUnits->title }}
                                    </td>
                                    <td class="col-sm-1">
                                        <input type="number" class="form-control" name="qty[]" required value="{{ $entry->qty }}" min="0" />
                                    </td>
                                    <td class="col-lg-1"><input type="text" class="form-control" name="remark[]" required value="{{ $entry->remark }}"></td>
                                    <td class="col-sm-1">
                                        <select class="form-control" id="field_typeT" name="field_type[]" required style="text-transform: capitalize;">
                                            @foreach($field_types as $k => $v)
                                            <option class="form-control p-1" value="{{ $v->id }}" {{ ($v->id == $entry->field_type_id) ? 'selected' : "" }}> {{ $v->title }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-sm-1">
                                        <select {{($entry->field_type_id != '4') ? 'disabled' : ''}} name="contractor[]" id="selectContractorT" class="form-control selectedContractorT">
                                            @foreach($contractors as $k => $v)
                                            <option value="{{ $v->contractor_id }}" class="form-control" {{ ($v->contractor_id == $entry->contractor_id) ? 'selected' : '' }}>{{ $v->getContractor->business_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-sm-1"><input type="number" class="form-control" name="sw[]" required value="{{ $entry->skilled_workers }}" min="0" /></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="swo[]" value="{{ $entry->skilled_workers_overtime}}" min="0"/></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="usw[]" value="{{ $entry->unskilled_workers}}" min="0"/></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="uswo[]" value="{{ $entry->unskilled_workers_overtime}}" min="0" /></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="cement_bags[]" value="{{ $entry->cement_bags ?? 0 }}" min="0" /></td>

                                    <td class="col-sm-1">
                                        @php 
                                            $images = explode(',', $entry->images);
                                        @endphp
                                        @if($entry->images != '')
                                            @foreach ( $images as $img)
                                                <img src="{{'/mittal/storage/app/public/'.$img}}" alt="" style="width:15px;height:15px;">
                                            @endforeach
                                        @endif
                                        
                                        
                                        <input type="file" class="form-control" multiple name="{{'images'.$ind.'[]'}}" value="{{ $entry->images ?? '' }}">
                                        @if($entry->images != '')
                                            <button type="button" class="btn btn-primary btn-images" data-bs-toggle="modal" data-bs-target="#imagesModal" data-entryid="{{$entry->id}}">View Images</button>
                                        @endif
                                        
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mb_30" style="float: right">
                        <a href="javascript:void(0)" class="btn btn-info btn-sm addnewrowT">Add New Row</a>
                    </div>
                    <div class="row">
                        <div class="mb_30 col-md-6" style="float: left">
                            <button type="submit" id="saveToday" class="btn btn-primary btn-hidden-T" {{ ($entryCountT == 0) ? 'hidden' : '' }}>Save</button>
                        </div>
                        <div class="mb_30 col-md-6" style="float: right">
                            <button type="button" id="submitToday" class="btn btn-primary btn-hidden-T {{ ($employee->role == '4') ? 'invisible' : '' }}" data-bs-toggle="modal" data-bs-target="#exampleModalT" {{ ($entryCountT== 0) ? 'hidden' : '' }}>{{-- hidden to Data Entry Operator --}}
                                Submit To Admin
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalT" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg"> {{-- style="z-index: 99999" --}}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Today's Site Entries</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-strip">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>

                                    <tbody class="">
                                        @foreach($entriesT as $entry)
                                        @php
                                        $currActivity = $entry->getActivity;
                                        @endphp
                                        <tr>
                                            <td style="text-transform: capitalize;">
                                                <p>{{ $currActivity->activity_name }}</p>
                                            </td>
                                            <td style="text-transform: capitalize;" class="unitname">
                                                <p>{{ $currActivity->getUnits->title }}</p>
                                            </td>
                                            <td class="">
                                                <p>{{$entry->qty}}</p>
                                            </td>
                                            <td>
                                                <p>{{$entry->remark}}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="{{ $site->id }}/submit/today" type="button" class="btn btn-primary" id="btnToday">Submit To Admin</a>
                                <a href="{{ $site->id }}/submit/yesterday" type="button" class="btn btn-primary" id="btnYesterday" style="display: none;">Submit To Admin</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row mt-3"> {{-- yesterday --}}
            <h5 style="font: bold;">Site Entries
                {{-- <select name="progress_date" id="progress_date" class="form-control"></select> --}}
                Yesterday: {{ $yesterday }}
            </h5>
            @if($submitted['yesterday'] == 'true')
                <div class="row">
                    <p class="bg-danger text-center">You have already submitted entries for yesterday</p>
                </div>
            @else
                <form method="POST" action="{{$site->id}}/yesterday" enctype="multipart/form-data">
                    @csrf
                    @if(Session::has('activityRequired'))
                    <p style="color:red;margin-left: 20px">{{ Session::get('activityRequired') }}</p>
                    @endif
                    <table class="table table-strip container">
                        <thead>
                            <tr class="row">
                                <th class="col-sm-1">Activity</th>
                                <th class="col-md-1">Unit</th>
                                <th class="col-md-1">Quantity</th>
                                <th class="col-lg-1">Remarks</th>
                                <th class="col-sm-1">Field Type</th>
                                <th class="col-sm-1" id="contractorY">Petty Contractor</th>
                                <th class="col-md-1">Skilled Workers</th>
                                <th class="col-md-1">Skilled Workers Overtime(Hr)</th>
                                <th class="col-md-1">UnSkilled Workers</th>
                                <th class="col-md-1">UnSkilled Workers Overtime(Hr)</th>
                                <th class="col-md-1">Cement Bags</th>
                                <th class="col-md-1">Images</th>
                            </tr>
                        </thead>

                        <tbody class="appendedRowY">
                            @foreach($entriesY as $k => $entry)
                                {{--dump($entry)--}}
                                @php
                                    $ind = $k;
                                @endphp
                                <tr class="row">
                                    <td style="text-transform: capitalize;" class="col-sm-1">
                                        <select class="form-control" id="selectActivity" name="selectActivity[]" required style="text-transform: capitalize; width:100%;">
                                            @foreach($activity as $k => $v)
                                            <option class="form-control" attunit="{{ $v->getActivity->getunits->title }}" value="{{ $v->getActivity->id }}" {{ ( (int)$v->getActivity->id == (int)$entry->activity_id) ? 'selected' : "" }}> {{ $v->getActivity->activity_name }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-1">
                                        {{ $entry->getActivity->getUnits->title }}
                                    </td>
                                    <td class="col-sm-1">
                                        <input type="number" class="form-control" name="qty[]" required value="{{ $entry->qty }}" min="0" />
                                    </td>
                                    <td class="col-lg-1"><input type="text" class="form-control" name="remark[]" required value="{{ $entry->remark }}"></td>
                                    <td class="col-sm-1">
                                        <select class="form-control" id="field_typeY" name="field_type[]" required style="text-transform: capitalize;">
                                            @foreach($field_types as $k => $v)
                                            <option class="form-control p-1" value="{{ $v->id }}" {{ ($v->id == $entry->field_type_id) ? 'selected' : "" }}> {{ $v->title }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-sm-1">
                                        <select {{($entry->field_type_id != '4') ? 'disabled' : ''}} name="contractor[]" id="selectContractorY" class="form-control selectedContractorY">
                                            @foreach($contractors as $k => $v)
                                            <option value="{{ $v->contractor_id }}" class="form-control" {{ ($v->contractor_id == $entry->contractor_id) ? 'selected' : '' }}>{{ $v->getContractor->business_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-sm-1"><input type="number" class="form-control" name="sw[]" required value="{{ $entry->skilled_workers }}" min="0"/></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="swo[]" value="{{ $entry->skilled_workers_overtime}}" min="0" /></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="usw[]" value="{{ $entry->unskilled_workers}}" min="0" /></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="uswo[]" value="{{ $entry->unskilled_workers_overtime}}" min="0" /></td>

                                    <td class="col-sm-1"><input type="number" class="form-control" name="cement_bags[]" value="{{ $entry->cement_bags ?? 0 }}" min="0" /></td>

                                    <td class="col-sm-1">
                                        
                                        {{-- <input type="file" class="form-control" multiple name="{{'images'.$ind.'[]'}}" value="{{ $entry->images ?? ''}}" >   
                                        <button type="button" class="btn btn-primary btn-images" data-bs-toggle="modal" data-bs-target="#imagesModal" data-entryid="{{$entry->id}}">View Images</button> --}}
                                        @php 
                                            $images = explode(',', $entry->images);
                                        @endphp
                                        @if($entry->images != '')
                                            @foreach ( $images as $img)
                                                <img src="{{'/mittal/storage/app/public/'.$img}}" alt="" style="width:15px;height:15px;">
                                            @endforeach
                                        @endif
                                        
                                        
                                        <input type="file" class="form-control" multiple name="{{'images'.$ind.'[]'}}" value="{{ $entry->images ?? '' }}">
                                        @if($entry->images != '')
                                            <button type="button" class="btn btn-primary btn-images" data-bs-toggle="modal" data-bs-target="#imagesModal" data-entryid="{{$entry->id}}">View Images</button> {{--data-images={{ $entry->images ?? '' }}--}}
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="mb_30" style="float: right">
                        <a href="javascript:void(0)" class="btn btn-info btn-sm addnewrowY">Add New Row</a>
                    </div>
                    <div class="row">
                        <div class="mb_30 col-md-6" style="float: left">
                            <button type="submit" id="saveYesterday" class="btn btn-primary btn-hidden-Y" {{ ($entryCountY == 0) ? 'hidden' : '' }}>Save</button>
                        </div>
                        <div class="mb_30 col-md-6" style="float: right">
                            <button type="button" id="submitYesterday" class="btn btn-primary btn-hidden-Y {{ ($employee->role == '4') ? 'invisible' : '' }}" data-bs-toggle="modal" data-bs-target="#exampleModalY" {{ ($entryCountY == 0) ? 'hidden' : '' }}>{{-- hidden to Data Entry Operator --}}
                                Submit To Admin
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalY" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Yesterday's Site Entries</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-strip">
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>

                                    <tbody class="">
                                        @foreach($entriesY as $entry)
                                        @php
                                        $currActivity = $entry->getActivity;
                                        @endphp
                                        <tr>
                                            <td style="text-transform: capitalize;">
                                                <p>{{ $currActivity->activity_name }}</p>
                                            </td>
                                            <td style="text-transform: capitalize;" class="unitname">
                                                <p>{{ $currActivity->getUnits->title }}</p>
                                            </td>
                                            <td class="">
                                                <p>{{$entry->qty}}</p>
                                            </td>
                                            <td>
                                                <p>{{$entry->remark}}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="{{ $site->id }}/submit/yesterday" type="button" class="btn btn-primary" id="btnToday">Submit To Admin</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{--View Images Modal--}}
        <div class="modal fade" id="imagesModal" tabindex="-1" aria-labelledby="imagesModalLabel" aria-hidden="true" data-images="">        
            <div class="modal-dialog modal-lg"> {{-- style="z-index: 99999" --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="imagesModalLabel">View Your Uploaded images</h1>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <div id="showImages"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <a href="{{ $site->id }}/submit/today" type="button" class="btn btn-primary" id="btnToday">Submit To Admin</a> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('javascript')
    <script>
        let addcount = -1;
        /*const myModal = document.getElementById('myModal')
        const myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', () => {
            myInput.focus()
        });*/

        $(document).ready(function() {
            var entryCountT = parseInt("<?php echo($entryCountT) ?>");
            //console.log(entryCountT);

            var entryCountY = parseInt("<?php echo($entryCountY) ?>");
            //console.log(entryCountY);

            $('select[name=role]').change(function() {
                let roleId = $(this).val();
                $('select[name=employees]').empty();

                $.ajax({
                    url: '/mittal/public/get/users/by/role/' + roleId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        for (let i = 0; i < res.data.length; i++) {
                            $('select[name=employees]').prepend('<option value="' + res.data[i].id + '">' + res.data[i].name + '</option>');
                        }
                    }
                });

            })

            $(document).on('change', '#selectActivity', function() {
                //console.log("kjnik");
                var element = $(this).find('option:selected');
                var selectedUnit = element.attr("attunit");

                $(this).parents('tr').find('.unitname').html(selectedUnit);
            });

            /*$(document).on('change', '#progress_date', function() {
                var element = $(this).find('option:selected').attr('selectedDay');
                var submittedToday = ("<?php echo ($submitted['today']); ?>");
                var submittedYesterday = ("<?php echo ($submitted['yesterday']); ?>");
                //console.log(submittedToday, submittedYesterday)

                document.getElementById('submitToday').disabled = false;
                document.getElementById('saveToday').disabled = false;
                document.getElementById('submitYesterday').disabled = false;
                document.getElementById('saveYesterday').disabled = false;

                if(element == 'today') {
                    document.getElementById('btnToday').style.display = '';
                    document.getElementById('btnYesterday').style.display = 'none';

                    document.getElementById('saveYesterday').style.display = 'none';
                    document.getElementById('submitYesterday').style.display = 'none';
                    document.getElementById('saveToday').style.display = '';
                    document.getElementById('submitToday').style.display = '';

                    if(submittedToday == 'true') {
                        document.getElementById('saveToday').disabled =  true;
                        document.getElementById('submitToday').disabled =  true;
                    } else {
                        document.getElementById('saveToday').disabled = false;
                        document.getElementById('submitToday').disabled = false;
                    }
                } else if(element == 'yesterday') {
                    document.getElementById('btnToday').style.display = 'none';
                    document.getElementById('btnYesterday').style.display = '';

                    document.getElementById('saveYesterday').style.display = '';
                    document.getElementById('submitYesterday').style.display = '';
                    document.getElementById('saveToday').style.display = 'none';
                    document.getElementById('submitToday').style.display = 'none';

                    if(submittedYesterday == 'true') {
                        document.getElementById('saveYesterday').disabled = true;
                        document.getElementById('submitYesterday').disabled = true;
                    } else {
                        document.getElementById('saveYesterday').disabled = false;
                        document.getElementById('submitYesterday').disabled = false;
                    }
                }

            });*/

            $('.addnewrowT').click(function() {
                var appendedRow = $('.defaultRow').html();
                //console.log(appendedRow)
                var btnHidden = document.getElementsByClassName('btn-hidden-T');
                for (let i = 0; i < btnHidden.length; i++) {
                    btnHidden[i].removeAttribute('hidden');
                };
                var c = entryCountT.toString();
                var name= "images" + c + "[]";

                $('.appendedRowT').append(`<tr class="row"><td style="text-transform: capitalize;" class="col-sm-1"> <select class="form-control select2" id="selectActivity" name="selectActivity[]" required style="text-transform: capitalize; width:100%;"> <option value="0" attunit="">Select</option>@foreach($activity as $k => $v) <option class="form-control" attunit="{{ $v->getActivity->getunits->title }}" value="{{ $v->getActivity->id }}"> {{ $v->getActivity->activity_name }} </option> @endforeach </select> </td> <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-1"> </td> <td class="col-sm-1"> <input type="number" class="form-control" name="qty[]" required min="0"/> </td> <td class="col-lg-1"><input type="text" class="form-control" name="remark[]" required></td> <td class="col-sm-1"> <select class="form-control select2" id="field_typeT" name="field_type[]" required style="text-transform: capitalize;"> @foreach($field_types as $k => $v) <option class="form-control p-1" value="{{ $v->id }}"> {{ $v->title }} </option> @endforeach </select> </td> <td class="col-sm-1"> <select  name="contractor[]" id="selectContractorT" class="form-control selectedContractorT select2"> @foreach($contractors as $k => $v) <option value="{{ $v->contractor_id }}" {{($v->contractor_id == "4") ? "selected" : ""}} class="form-control">{{ $v->getContractor->business_name }}</option> @endforeach </select> </td> <td class="col-sm-1"><input type="number" class="form-control" name="sw[]" required min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="swo[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="usw[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="uswo[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="cement_bags[]" value="0" min="0"/></td> <td class="col-sm-1"> <input type="file" class="form-control" multiple name="${name}" value=""></tr>`);

                {{-- $("select").select2(); --}}
                $('.select2').select2();

                entryCountT += 1

            });

            $('.addnewrowY').click(function() {
                var appendedRow = $('.defaultRow').html();
                //console.log(appendedRow)
                var btnHidden = document.getElementsByClassName('btn-hidden-Y');
                for (let i = 0; i < btnHidden.length; i++) {
                    btnHidden[i].removeAttribute('hidden');
                };
                var c = entryCountY.toString();
                var name= "images" + c + "[]";

                $('.appendedRowY').append('<tr class="row"><td style="text-transform: capitalize;" class="col-sm-1"> <select class="form-control select2" id="selectActivity" name="selectActivity[]" required style="text-transform: capitalize; width:100%;"> <option value="0" attunit="">Select</option>@foreach($activity as $k => $v) <option class="form-control" attunit="{{ $v->getActivity->getunits->title }}" value="{{ $v->getActivity->id }}"> {{ $v->getActivity->activity_name }} </option> @endforeach </select> </td> <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-1"> </td> <td class="col-sm-1"> <input type="number" class="form-control" name="qty[]" required min="0"/> </td> <td class="col-lg-1"><input type="text" class="form-control" name="remark[]" required></td> <td class="col-sm-1"> <select class="form-control select2" id="field_typeY" name="field_type[]" required style="text-transform: capitalize;"> @foreach($field_types as $k => $v) <option class="form-control p-1" value="{{ $v->id }}"> {{ $v->title }} </option> @endforeach </select> </td> <td class="col-sm-1"> <select name="contractor[]" id="selectContractorY" class="form-control selectedContractorY select2"> @foreach($contractors as $k => $v) <option value="{{ $v->contractor_id }}" {{($v->contractor_id == "4") ? "selected" : ""}}class="form-control">{{ $v->getContractor->business_name }}</option> @endforeach </select> </td> <td class="col-sm-1"><input type="number" class="form-control" name="sw[]" required min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="swo[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="usw[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="uswo[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="cement_bags[]" value="0" min="0"/></td> <td class="col-sm-1"> <input type="file" class="form-control" multiple name="${name}" value="" > </td></tr>');

                {{-- $("select").select2(); --}}
                $('.select2').select2();
                entryCountY += 1
            });

            $(document).on('change', '#field_typeT', function() {
                //console.log($(this))
                var element = $(this).find('option:selected');
                if (element.val() == 4) { // 'by Contractor'
                    //console.log($(this).parent().parent().find('.selectedContractorT').attr('disabled'));
                    if ($(this).parent().parent().find('.selectedContractorT').attr('disabled') == undefined || $(this).parent().parent().find('.selectedContractorT').attr('disabled') == 'undefined') {
                        $(this).parent().parent().find('.selectedContractorT').attr('disabled', 'disabled');
                    } else {
                        $(this).parent().parent().find('.selectedContractorT').removeAttr('disabled');
                    }
                } else {
                    $(this).parent().parent().find('.selectedContractorT').attr('disabled', 'disabled');

                }
            });

            $(document).on('change', '#field_typeY', function() {
                //console.log($(this))
                var element = $(this).find('option:selected');
                //console.log($(this).parent())
                //console.log(element)
                //console.log(element.val());
                if (element.val() == 4) { // 'by Contractor'
                    //console.log($(this).parent().parent().find('.selectedContractorY').attr('disabled'));
                    if ($(this).parent().parent().find('.selectedContractorY').attr('disabled') == undefined || $(this).parent().parent().find('.selectedContractorY').attr('disabled') == 'undefined') {
                        $(this).parent().parent().find('.selectedContractorY').attr('disabled', 'disabled');
                    } else {
                        $(this).parent().parent().find('.selectedContractorY').removeAttr('disabled');
                    }
                } else {
                    $(this).parent().parent().find('.selectedContractorY').attr('disabled', 'disabled');

                }
            });

            $('.btn-images').click(function(){
                console.log('btn', $(this))
                input = $(this).parent().find('input[type=file]');
                //console.log(input);
                defaultImages = input.prop('defaultValue');
                images = defaultImages.split(',');
                imagesDiv = '';
                entryId = $(this).data('entryid');
                images.forEach( (img,ind) => {
                    //console.log(ind);
                    imagesDiv += `<div><img src="/mittal/storage/app/public/${img}" style="width:50px;height:50px;"> <a href="javascript:void(0)" id="${ind}" class="removeImage" data-entryid="${entryId}"><i class="fa fa-trash"></i></a> </div>`
                })
                //newImages = input.prop('files');
                //if(newImages.length){
                    //for (const file of newImages) {
                        //console.log(file);
                    //}
                //}
                //console.log(newImages)
                //{newImages.forEach(img => {
                  //  console.log(img);
                //});
                $('#showImages').html('');
                $('#showImages').append('<div>' + imagesDiv + '</div>');
            })

            $(document).on('click', '.removeImage', function(){
                img = $(this);
                console.log(img.parent().index())
                console.log('clicked')
                //console.log($(this))
                id = img.prop('id');
                entryId = img.data('entryid');
                console.log(entryId);
                $.ajax({
                    url: `/mittal/public/delete/siteEntry/${entryId}/${id}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        //console.log(res);
                        //console.log($(this))
                        img.parent().remove();
                    }
                });
            })
        });
    </script>
@endsection