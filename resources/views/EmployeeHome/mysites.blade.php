@extends('./EmployeeHome/layout')



@section('content')

    {{-- {{ dd($mysites9) }} --}}

    <h5 style="color: white;">User: {{ $employee->name }}</h5>

    <h5 style="color: white;">Role: {{ ($employee->role == '3') ? 'Project Manager' : 'Data Entry Operator' }}</h5>

    <div class="row ">

        <div class="col-lg-12">

            <div class="white_card card_height_100 mb_30">

                <div class="white_card_body">

                    <h4 style="padding: 20px 0px" >List Site</h4>

                    <table class="table lms_table_active ">

                        <thead>

                            <tr>

                                <th scope="col">#</th>

                                <th scope="col">Site Name</th>

                                <th scope="col">Site Description</th>

                                <th scope="col">Site Location</th>

                                <th scope="col">Site Address</th>

                                <th scope="col">View</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($mysites as $k => $v)

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

                                        </th>

                                        <th scope="row"> 

                                            <a href="mysites/{{$v->id}}" class="btn btn-primary">View</a>

                                        </th>

                                    </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection