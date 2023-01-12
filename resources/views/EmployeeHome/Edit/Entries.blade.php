@extends('./EmployeeHome/layout')
    <style>
        .modal-backdrop.show {
            z-index: 9
        }

        table th {
            font-size: 75%;
        }
    </style>

@section('content')
    
    @php
        $entryCount = $entries->count();
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
        <div class="row mt-3"> {{-- that day --}}
            <h5 style="font: bold;">Site Entries
                {{-- <select name="progress_date" id="progress_date" class="form-control"></select> --}}
                Date: {{ $date }}
            </h5>
            <form method="POST" action="{{route('employee.savesiteentry', ['site' => $site->id, 'day' => $date])}}" enctype="multipart/form-data"> {{--action="{{$site->id}}/{{$date}}"--}}
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
                            <th class="col-sm-1" id="contractor">Petty Contractor</th>
                            <th class="col-md-1">Skilled Workers</th>
                            <th class="col-md-1">Skilled Workers Ot(Hr)</th>
                            <th class="col-md-1">UnSkilled Workers</th>
                            <th class="col-md-1">UnSkilled Workers Ot(Hr)</th>
                            <th class="col-md-1">Cement Bags</th>
                            <th class="col-md-1">Images</th>
                        </tr>
                    </thead>

                    <tbody class="appendedRow">
                        @foreach($entries as $k => $entry)
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
                                    <select class="form-control" id="field_type" name="field_type[]" required style="text-transform: capitalize;">
                                        @foreach($field_types as $k => $v)
                                        <option class="form-control p-1" value="{{ $v->id }}" {{ ($v->id == $entry->field_type_id) ? 'selected' : "" }}> {{ $v->title }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="col-sm-1">
                                    <select {{($entry->field_type_id != '4') ? 'disabled' : ''}} name="contractor[]" id="selectContractor" class="form-control selectedContractor">
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

                                {{-- <td class="col-sm-1">
                                    <input type="file" class="form-control" multiple name="{{'images'.$ind.'[]'}}" value="{{ $entry->images ?? '' }}">
                                </td> --}}
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
                    <a href="javascript:void(0)" class="btn btn-info btn-sm addnewrow">Add New Row</a>
                </div>
                <div class="row">
                    <div class="mb_30 col-md-6" style="float: left">
                        <button type="submit" id="save" class="btn btn-primary btn-hidden" {{ ($entryCount == 0) ? 'hidden' : '' }}>Save</button>
                    </div>
                    <div class="mb_30 col-md-6" style="float: right">
                        <button type="button" id="submit" class="btn btn-primary btn-hidden {{ ($employee->role == '4') ? 'invisible' : '' }}" data-bs-toggle="modal" data-bs-target="#exampleModal" {{ ($entryCount== 0) ? 'hidden' : '' }}>{{-- hidden to Data Entry Operator --}}
                            Submit To Admin
                        </button>
                    </div>
                </div>
            </form>
                
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    @foreach($entries as $entry)
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
                            <a href="{{route('employee.submitsiteentry', ['site' => $site->id, 'day' => $date])}}" type="button" class="btn btn-primary" id="btn">Submit To Admin</a>{{--href="{{ $site->id }}/submit/{{$date}}"--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            var entryCount = parseInt("<?php echo($entryCount) ?>");
            //console.log(entryCount);

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

            $('.addnewrow').click(function() {
                var appendedRow = $('.defaultRow').html();
                //console.log(appendedRow)
                var btnHidden = document.getElementsByClassName('btn-hidden');
                for (let i = 0; i < btnHidden.length; i++) {
                    btnHidden[i].removeAttribute('hidden');
                };
                var c = entryCount.toString();
                var name= "images" + c + "[]";

                $('.appendedRow').append(`<tr class="row"><td style="text-transform: capitalize;" class="col-sm-1"> <select class="form-control" id="selectActivity" name="selectActivity[]" required style="text-transform: capitalize; width:100%;"> <option value="0" attunit="">Select</option>@foreach($activity as $k => $v) <option class="form-control" attunit="{{ $v->getActivity->getunits->title }}" value="{{ $v->getActivity->id }}"> {{ $v->getActivity->activity_name }} </option> @endforeach </select> </td> <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-1"> </td> <td class="col-sm-1"> <input type="number" class="form-control" name="qty[]" required min="0"/> </td> <td class="col-lg-1"><input type="text" class="form-control" name="remark[]" required></td> <td class="col-sm-1"> <select class="form-control" id="field_type" name="field_type[]" required style="text-transform: capitalize;"> @foreach($field_types as $k => $v) <option class="form-control p-1" value="{{ $v->id }}"> {{ $v->title }} </option> @endforeach </select> </td> <td class="col-sm-1"> <select  name="contractor[]" id="selectContractor" class="form-control selectedContractor"> @foreach($contractors as $k => $v) <option value="{{ $v->contractor_id }}" {{($v->contractor_id == "4") ? "selected" : ""}} class="form-control">{{ $v->getContractor->business_name }}</option> @endforeach </select> </td> <td class="col-sm-1"><input type="number" class="form-control" name="sw[]" required min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="swo[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="usw[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="uswo[]" value="0" min="0"/></td> <td class="col-sm-1"><input type="number" class="form-control" name="cement_bags[]" value="0" min="0"/></td> <td class="col-sm-1"> <input type="file" class="form-control" multiple name="${name}" value=""></td></tr>`);

                entryCount += 1

            });

            

            $(document).on('change', '#field_type', function() {
                //console.log($(this))
                var element = $(this).find('option:selected');
                if (element.val() == 4) { // 'by Contractor'
                    //console.log($(this).parent().parent().find('.selectedContractor').attr('disabled'));
                    if ($(this).parent().parent().find('.selectedContractor').attr('disabled') == undefined || $(this).parent().parent().find('.selectedContractor').attr('disabled') == 'undefined') {
                        $(this).parent().parent().find('.selectedContractor').attr('disabled', 'disabled');
                    } else {
                        $(this).parent().parent().find('.selectedContractor').removeAttr('disabled');
                    }
                } else {
                    $(this).parent().parent().find('.selectedContractor').attr('disabled', 'disabled');

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