@extends('layouts.app')

@section('content')

<div class="row ">
    <div class="col-lg-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_body">
                <h4 style="padding: 20px 0px">List Site Transfers</h4>
                <table class="table lms_table_active ">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">From Site</th>
                            <th scope="col">To Site</th>
                            <th scope="col">Num of Bags</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sitetransfer as $k => $v)

                        <tr>
                            <td>{{ $v->date }}</td>
                            <td>{{ ($v->siteFromDetails != null)? $v->siteFromDetails->site_name : "No Site available" }}</td>
                            <td>{{ ($v->siteToDetails != null)? $v->siteToDetails->site_name : "No Site available" }}</td>
                            <td>{{ $v->num_bags}}</td>
                            <td>
                                {!! ($v->status == 0)? 'Deactivated' : 'Active' !!}
                            </td>
                            <td>
                                @if($v->status == 0)
                                <a href="{{ route('change.status.sitetransfer' , $v->id) }}" class="btn btn-success btn-sm">Activate</a>
                                @else
                                <a href="{{ route('change.status.sitetransfer' , $v->id) }}" class="btn btn-sm btn-danger">DeActivate</a>
                                @endif
                                <a href="{{ route('edit.sitetransfer' , $v->id) }}" class="btn btn-sm btn-info">Edit</a>
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