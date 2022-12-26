@extends('layouts.app')

@section('content')
<style>
    .borderDashed {
        border: 2px dashed #ededed;
        padding: 10px 20px;
    }
</style>

<div class="row ">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_body">
                <h4 style="padding: 20px 0px">Create New Site</h4>
                <form method="POST" action="{{ route('save.site') }}">
                    @csrf
                    <div class="mb_30">
                        <label style="width: 100%" for="">
                            <div class="main-title">
                                <h3 class="m-0">Serial no</h3>
                            </div>
                            <div class=" mb-0">
                                <input type="text" class="form-control" name="serial_no" id="serial_no" value="{{ ($lastSiteDetails != null) ? (((int)$lastSiteDetails->serial_no) + 1) : 1000 }}" placeholder="serial no">
                            </div>
                            @if($errors->has('serial_no'))
                                <p style="color:red;margin-left: 20px">{{ $errors->first('serial_no') }}</p>
                            @endif
                        </label>
                    </div>

                    <div class="mb_30">
                        <label style="width: 100%" for="">
                            <div class="main-title">
                                <h3 class="m-0">Site Name</h3>
                            </div>
                            <div class=" mb-0">
                                <input type="text" class="form-control" name="site_name" id="site_name" value="{{ old('site_name') }}" placeholder="site name">
                            </div>
                            @if($errors->has('site_name'))
                                <p style="color:red;margin-left: 20px">{{ $errors->first('site_name') }}</p>
                            @endif
                        </label>
                    </div>

                    <div class="mb_30">
                        <label style="width: 100%" for="">
                            <div class="main-title">
                                <h3 class="m-0">Site Description</h3>
                            </div>
                            <div class=" mb-0">
                                <input type="text" placeholder="Some descriptions about site" class="form-control" name="site_description" value="{{ old('site_description') }}" id="site_description" placeholder="Description of the site">
                            </div>
                            @if($errors->has('site_description'))
                                <p style="color:red;margin-left: 20px">{{ $errors->first('site_description') }}</p>
                            @endif
                        </label>
                    </div>

                    <div class="mb_30">
                        <label style="width: 100%" for="">
                            <div class="main-title">
                                <h3 class="m-0">Site location</h3>
                            </div>
                            <div class=" mb-0">
                                <input type="text" class="form-control" name="site_location" id="site_location" value="{{ old('site_location') }}" placeholder="eg. Amritsar">
                            </div>
                            @if($errors->has('site_location'))
                                <p style="color:red;margin-left: 20px">{{ $errors->first('site_location') }}</p>
                            @endif
                        </label>
                    </div>
                    <div class="mb_30">
                        <label style="width: 100%" for="">
                            <div class="main-title">
                                <h3 class="m-0">Site Address</h3>
                            </div>
                            <div class=" mb-0">
                                <input type="text" class="form-control" name="site_address" id="site_address" value="{{ old('site_address') }}" placeholder="Site Address">
                            </div>
                            @if($errors->has('site_address'))
                                <p style="color:red;margin-left: 20px">{{ $errors->first('site_address') }}</p>
                            @endif
                        </label>
                    </div>

                    <div class="row">
                        <div class="mb_30 col-md-4">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Project Manager</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="site_admin">
                                        @foreach($project_managers as $k => $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('site_admin'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('site_admin') }}</p>
                                @endif
                            </label>
                        </div>


                        <div class="mb_30 col-md-4">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Data Entry Operator</h3>
                                </div>
                                <div class=" mb-0">
                                    <select class="form-control" name="employees">
                                        <option value="0">Select</option>
                                        @foreach($data_entry_operators as $k => $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($errors->has('employees'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('employees') }}</p>
                                @endif
                            </label>
                        </div>

                        <div class="mb_30 col-md-4">
                            <label style="width: 100%" for="">
                                <div class="main-title">
                                    <h3 class="m-0">Petty Contractors</h3>
                                </div>
                                <div class=" mb-0">
                                </div>
                                @if($errors->has('contractors'))
                                    <p style="color:red;margin-left: 20px">{{ $errors->first('contractors') }}</p>
                                @endif
                            </label>
                            <select multiple class="form-control select" name="contractors[]">
                                <option value="0" selected>Select</option>
                                @foreach($contractors as $k => $v)
                                    <option value="{{ $v->id }}">{{ $v->business_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        @if(Session::has('activityRequired'))
                            <p style="color:red;margin-left: 20px">{{ Session::get('activityRequired') }}</p>
                        @endif
                        <table class="table table-strip">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>Unit</th>
                                    <th>Estimate Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="appendedRow">
                                <tr>
                                    <td style="text-transform: capitalize;" class="col-md-3">
                                        <select class="form-control" id="selectActivity" name="selectActivity[]" required style="text-transform: capitalize;">
                                            @foreach($activity as $k => $v)
                                                <option attunit="{{ $v->getunits->title }}" value="{{ $v->id }}"> {{ $v->activity_name }} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-3">
                                    </td>
                                    <td class="col-md-3">
                                        <input type="number" class="form-control" name="estimate[]" />
                                    </td>
                                    <td class="col-md-3">
                                        <a href="javascript:void(0)" class="deleteRow"><i class="fa fa-trash" style="color: red"></i></a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="mb_30" style="float: right">
                        <a href="javascript:void(0)" class="btn btn-info btn-sm addnewrow">Add New Row</a>
                    </div>


                    <div class="mb_30">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('javascript')
<script>
    $(document).ready(function() {
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
            console.log("kjnik");
            var element = $(this).find('option:selected');
            var selectedUnit = element.attr("attunit");

            $(this).parents('tr').find('.unitname').html(selectedUnit);
        });

        $('.addnewrow').click(function() {
            var appendedRow = $('.defaultRow').html();
            console.log(appendedRow)
            $('.appendedRow').append('<tr> <td style="text-transform: capitalize;" class="col-md-3"> <select class="form-control" id="selectActivity" name="selectActivity[]" required  style="text-transform: capitalize;"> @foreach($activity as $k => $v) <option attunit="{{ $v->getunits->title }}" value="{{ $v->id }}"> {{ $v->activity_name }} </option> @endforeach </select> </td> <td style="text-transform: capitalize;padding-top: 20px;" class="unitname col-md-3"> </td> <td class="col-md-3"> <input type="number" class="form-control" name="estimate[]" /> </td> <td class="col-md-3"> <a href="javascript:void(0)" class="deleteRow"><i class="fa fa-trash" style="color: red"></i></a> </td> </tr>');
        })
        $(document).on('click', '.deleteRow', function() {
            if ($('.appendedRow > tr').length > 1) {
                $(this).parents('tr').remove();
            } else {
                alert("Minimum one row required in table");
            }

        })
    });
</script>
@endsection