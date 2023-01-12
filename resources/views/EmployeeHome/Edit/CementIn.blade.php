{{-- {{dd($cement_ins)}} --}}
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
        $cement_insCount = $cement_ins->count();
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
        <div class="row mt-3">
            <h5 style="font: bold;">Edit Cement Ins
                Date: {{ $date }}
            </h5>
            <form method="POST" action="{{route('employee.saveEdits', ['key' => 2, 'site' => $site->id, 'date' => $date])}}" id="form"> {{--action="{{$site->id}}/{{$date}}"--}}
                @csrf
                @if( $errors)
                    <p style="color:red;margin-left: 20px">{{ $errors }}</p>
                @endif
                <table class="table container table-responsive">
                    <thead>
                        <tr>
                            <th>Num. of Bags</th>
                            <th>From Site</th>
                            <th>Receiving Site</th>
                            <th>Remark</th>
                        </tr>
                    </thead>

                    <tbody class="appendedRow">
                        @foreach($cement_ins as $k => $cement_in)
                            @php
                                $ind = $k;
                            @endphp
                            <tr>
                                <td style="text-transform: capitalize;">
                                    <input type="number" min="0" class="form-control" name="bags[]" value="{{ $cement_in->bags}}" id="bags" placeholder="Num. of Bags" required>
                                </td>
                                @if($errors->has('bags'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('bags') }}</p>
                                @endif
                                <td style="text-transform: capitalize;padding-top: 20px;" class="">
                                    <select class="form-control" name="from_site[]">
                                        @foreach($allsites as $site)
                                            <option value="{{ $site->id }} {{($site->id == $cement_in->from_site) ? 'selected' : ''}}">{{ $site->site_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @if($errors->has('from_site'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('from_site') }}</p>
                                @endif
                                <td>
                                    <select class="form-control" name="to_site[]">
                                        @foreach($mysites as $site)
                                            <option value="{{ $site->id }} {{($site->id == $cement_in->to_site) ? 'selected' : ''}}">{{ $site->site_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                @if($errors->has('to_site'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('to_site') }}</p>
                                @endif
                                <td >
                                    <input type="textArea" class="form-control" name="remark[]" required placeholder="Remark" value="{{$cement_in->remark}}" />
                                </td>
                                @if($errors->has('remark'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('remark') }}</p>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="mb_30" style="float: right">
                    <a href="javascript:void(0)" class="btn btn-info btn-sm addnewrow">Add New Row</a>
                </div>
                <div class="row">
                    {{-- <div class="mb_30 col-md-6" style="float: left">
                        <button type="submit" id="save" class="btn btn-primary btn-hidden" {{ ($cement_insCount == 0) ? 'hidden' : '' }}>Save</button>
                    </div> --}}
                    <div class="mb_30 col-md-6" style="float: right">
                        <a id="submit" class="btn btn-primary" href="javascript:void(0)"> 
                            Submit
                        </a>
                    </div>
                </div>
            </form>
                
            
        </div>
    </div>

@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script>
        let addcount = -1;
        /*const myModal = document.getElementById('myModal')
        const myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', () => {
            myInput.focus()
        });*/

        $(document).ready(function() {
            var cement_insCount = parseInt("<?php echo($cement_insCount) ?>");
            //console.log(entryCount);

            /*$(document).on('click', '#submit', function() {
                if(confirm('Do You want to submit these changes?'){
                    $('#form').submit()
                })
            })*/

            $('#submit').click(function(){
                var form = $('#form')
                console.log(form.validate())
                console.log(form.valid())
                if(form.valid()){
                    if(confirm('Do You want to submit these changes?')){
                        form.submit()
                    }
                } else {
                    alert('All Fields are required')
                }
            })

            $('.addnewrow').click(function() {

                $('.appendedRow').append(`<tr> <td style="text-transform: capitalize;"> <input type="number" min="0" class="form-control" name="bags[]" value="0" id="bags" placeholder="Num. of Bags" required> </td> @if($errors->has('bags')) <p style="color:red;margin-left: 20px">{{ $errors->first('bags') }}</p> @endif <td style="text-transform: capitalize;padding-top: 20px;" class=""> <select class="form-control" name="from_site[]"> @foreach($allsites as $site) <option value="{{ $site->id }}">{{ $site->site_name }}</option> @endforeach </select> </td> @if($errors->has('from_site')) <p style="color:red;margin-left: 20px">{{ $errors->first('from_site') }}</p> @endif <td> <select class="form-control" name="to_site[]"> @foreach($mysites as $site) <option value="{{ $site->id }}">{{ $site->site_name }}</option> @endforeach </select> </td> @if($errors->has('to_site')) <p style="color:red;margin-left: 20px">{{ $errors->first('to_site') }}</p> @endif <td > <input type="textArea" class="form-control" name="remark[]" required placeholder="Remark" /> </td> @if($errors->has('remark')) <p style="color:red;margin-left: 20px">{{ $errors->first('remark') }}</p> @endif </tr>`);

                cement_insCount += 1

            });

        });
    </script>
@endsection