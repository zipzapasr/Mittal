@extends('./EmployeeHome/layout')
@section('content')
    <p style="color: white;">Role: {{ (session('employee')->role  == 3) ? 'Project Manager' : 'Data Entry Operator' }}</p>
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >List Cement In <p>Only for yesterday and today</p> </h4>
                    <table class="table lms_table_active ">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Num. of Bags</th>
                                <th scope="col">From Site</th>
                                <th scope="col">To Site</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cementIns as $cementIn)
                                <tr>
                                    <th scope="row">
                                        <div class="question_content">{{ date('d-m-Y', strtotime($cementIn->date)) }}</div>
                                    </th>
                                    <td>{{ $cementIn->bags }}</td>
                                    <td>
                                        @if ($cementIn->from_site_id == 0)
                                            Godown
                                        @else
                                            {{($cementIn->getFromSite) ? ($cementIn->getFromSite->site_name) : 'None' }}
                                        @endif
                                    </td>
                                    <td>{{ ($cementIn->getToSite) ? ($cementIn->getToSite->site_name) : 'None' }}</td>
                                    <td>{{ $cementIn->remark }}</td>
                                    <td>
                                        <a href="{{ route('edit.cementIn' , ['cement_in' => $cementIn->id, 'user' => session('employee') ] ) }}" class="btn btn-sm btn-info">Edit</a>
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