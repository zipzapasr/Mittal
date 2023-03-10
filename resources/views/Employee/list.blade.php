@extends('layouts.app')

@section('content')
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >List Employees</h4>
                    <table class="table lms_table_active ">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Mobile</th>
                                <th scope="col">Role</th>
                                {{-- <th scope="col">Password</th> --}}
                                <th scope="col">Status</th>
                                <th scope="col">Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $k => $v)
                                <tr>
                                    <th scope="row">
                                        <div class="question_content">{{ $v->name }}</div>
                                    </th>
                                    <td>{{ $v->email }}</td>
                                    <td>{{ $v->mobile }}</td>
                                    <td>{{ app('App\Models\User')->roles[$v->role] }}</td>
                                    {{-- <td>{{ $v->raw_password }}</td> --}}
                                    <td>
                                        {!! ($v->status == 0)? 'Deactivated' : 'Active' !!}
                                    </td>
                                    <td>
                                        @if($v->status == 0)
                                            <a href="{{ route('change.status.employee' , $v->id) }}" class="btn btn-success btn-sm">Activate</a>
                                        @else
                                            <a href="{{ route('change.status.employee' , $v->id) }}" class="btn btn-sm btn-danger">Deactive</a>
                                        @endif
                                            <a href="{{ route('edit.employee' , $v->id) }}" class="btn btn-sm btn-info">Edit</a>
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