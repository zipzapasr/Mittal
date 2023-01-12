@extends('./EmployeeHome/layout')

@section('content')

    <p style="color: white;">Role: {{ (session('employee')->role  == 3) ? 'Project Manager' : 'Data Entry Operator' }}</p>

    <div class="row ">

        <div class="col-lg-12">

            <div class="white_card card_height_100 mb_30">

                <div class="white_card_body">

                    <h4 style="padding: 20px 0px" >List Cement Transfers To Clients</h4>

                    <table class="table lms_table_active ">

                        <thead>

                            <tr>
                                {{-- {{dd($cementTransfers)}} --}}

                                <th scope="col">Date</th>

                                <th scope="col">Num. of Bags</th>

                                <th scope="col">Site</th>

                                <th scope="col">Employee</th>

                                <th scope="col">Remarks</th>

                                <th scope="col">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($cementTransfers as $cementTransfer)

                                <tr>

                                    <th scope="row">

                                        <div class="question_content">{{ $cementTransfer->date }}</div>

                                    </th>

                                    <td>{{ $cementTransfer->bags }}</td>

                                    <td>

                                        {{ $cementTransfer->site_id .' ' .$cementTransfer->getSite->site_name }}

                                    </td>

                                    <td>{{
                                        ($cementTransfer->employee_id) ?? 0 
                                    }}</td>

                                    <td>{{ $cementTransfer->remark }}</td>

                                    <td>

                                        <a href="{{ route('edit.cementTransfer' , ['cementTransfer' => $cementTransfer->id, 'user' => session('employee')]) }}" class="btn btn-sm btn-info">Edit</a>

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