@extends('./EmployeeHome/layout')
@section('content')
    <p style="color: white;">Role: {{ (session('employee')->role  == 3) ? 'Project Manager' : 'Data Entry Operator' }}</p>
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >List Cement Outs</h4>
                    <table class="table lms_table_active ">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Num. of Bags</th>
                                <th scope="col">From Site</th>
                                <th scope="col">To Site</th>
                                <th scope="col">Remark</th>
                                <th scope="col">Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cementOuts as $cementOut)
                                <tr>
                                    <th scope="row">
                                        <div class="question_content">{{ $cementOut->date }}</div>
                                    </th>
                                    <td>{{ $cementOut->bags }}</td>
                                    <td>{{ $cementOut->getFromSite->site_name }}</td>
                                    <td>{{ $cementOut->getToSite->site_name }}</td>
                                    <td>{{ $cementOut->remark }}</td>
                                    <td>
                                        <a href="{{ route('edit.cementOut' , ['id' => $cementOut->id, 'user' => session('employee')]) }}" class="btn btn-sm btn-info">Edit</a>
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