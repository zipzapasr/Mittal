@extends('layouts.app')

@section('content')

    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >List Activities</h4>
                    <h5 style="padding: 20px 0px" >Productive Activities</h5>
                    <table class="table lms_table_active ">
                        <thead>
                            <tr>
                                <th scope="col">Activity Name</th>
                                <th scope="col">Activity Description</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($activity as $k => $v)
                                @if($v->activity_type == '1')
                                    <tr>
                                        <th scope="row">
                                            <div class="question_content">{{ $v->activity_name }}</div>
                                        </th>
                                        <td>{{ $v->activity_description }}</td>
                                        <td>{{ $v->unit }}</td>
                                        <td>
                                            {!! ($v->status == 0)? 'Deactivated' : 'Active' !!}
                                        </td>
                                        <td>
                                            @if($v->status == 0)
                                                <a href="{{ route('change.status.activity' , $v->id) }}" class="btn btn-success btn-sm">Activate</a>
                                            @else
                                                <a href="{{ route('change.status.activity' , $v->id) }}" class="btn btn-sm btn-danger">Deactive</a>
                                            @endif
                                                <a href="{{ route('edit.activity' , $v->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <h5 style="padding: 20px 0px" >Unproductive Activities</h5>
                    <table class="table lms_table_active mt-2">
                        <thead>
                            <tr>
                                <th scope="col">Activity Name</th>
                                <th scope="col">Activity Description</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activity as $k => $v)
                                @if($v->activity_type == '2')
                                    <tr>
                                        <th scope="row"> 
                                            <div href="#" class="question_content">{{ $v->activity_name }}</div>
                                        </th>
                                        <td>{{ $v->activity_description }}</td>
                                        <td>{{ $v->unit }}</td>
                                        <td>
                                            {!! ($v->status == 0)? 'Deactivated' : 'Active' !!}
                                        </td>
                                        <td>
                                            @if($v->status == 0)
                                                <a href="{{ route('change.status.activity' , $v->id) }}" class="btn btn-success btn-sm">Activate</a>
                                            @else
                                                <a href="{{ route('change.status.activity' , $v->id) }}" class="btn btn-sm btn-danger">Deactive</a>
                                            @endif
                                                <a href="{{ route('edit.activity' , $v->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @section('javascript')
        
    @endsection
@endsection