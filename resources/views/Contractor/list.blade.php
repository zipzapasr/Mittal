@extends('layouts.app')

@section('content')

<div class="row ">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_body">
                <h4 style="padding: 20px 0px">List Petty Contractors</h4>
                <table class="table lms_table_active ">
                    <thead>
                        <tr>
                            <th scope="col">Business Name</th>
                            <th scope="col">Name</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Details</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contractor as $k => $v)
                        <tr>
                            <th scope="row">
                                <div class="question_content">{{ $v->business_name }}</div>
                            </th>
                            <td>{{ $v->name }}</td>
                            <td>{{ $v->mobile }}</td>
                            <td style="width:100px; white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
                                <p>{{ $v->details}}</p>
                            </td>
                            <td>
                                {!! ($v->status == 0)? 'Deactivated' : 'Active' !!}
                            </td>
                            <td>
                                @if($v->status == 0)
                                <a href="{{ route('change.status.contractor' , $v->id) }}" class="btn btn-success btn-sm">Activate</a>
                                @else
                                <a href="{{ route('change.status.contractor' , $v->id) }}" class="btn btn-sm btn-danger">Deactive</a>
                                @endif
                                <a href="{{ route('edit.contractor' , $v->id) }}" class="btn btn-sm btn-info">Edit</a>
                            </td>
                        </tr>
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