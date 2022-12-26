@extends('layouts.app')

@section('content')
<div class="row ">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_body">

                <h4 style="padding: 20px 0px">List Site</h4>

                <table class="table lms_table_active ">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Site Name</th>
                            <th scope="col">Site Description</th>
                            <th scope="col">Site Location</th>
                            <th scope="col">Site Address</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($site as $k => $v)
                        <tr>
                            <th scope="row">
                                {{ $v->serial_no }}
                            </th>
                            <th scope="row">
                                {{ $v->site_name }}
                            </th>
                            <th scope="row">
                                {{ $v->site_description }}
                            </th>
                            <th scope="row">
                                {{ $v->site_location }}
                            </th>
                            <th scope="row">
                                {{ $v->site_address }}
                            </th>
                            <th scope="row">
                                {{ app('App\Models\User')->roles[$v->site_admin] }}
                            </th>
                            <td>
                                @if($v->status == 0)
                                <a href="{{ route('change.status.site' , $v->id) }}" class="btn btn-success btn-sm">Activate</a>
                                @else
                                <a href="{{ route('change.status.site' , $v->id) }}" class="btn btn-sm btn-danger">Deactive</a>
                                @endif
                                <a href="{{ route('edit.site' , $v->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <a href="{{ route('view.site' , $v->id) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection