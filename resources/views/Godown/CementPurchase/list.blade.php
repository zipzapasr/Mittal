@extends('./Godown/layout')
@section('content')
    {{-- {{dd($cementPurchases)}} --}}
    <p style="color: white;">Role: {{ (session('godown')->role  == 5) ? 'Godown' : '' }}</p>
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >List Cement Purchases <p>Only for yesterday and today</p></h4>
                    <table class="table lms_table_active ">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Num. of Bags</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Site</th>
                                <th scope="col">Employee</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cementPurchases as $cementPurchase)
                                <tr>
                                    <th scope="row">
                                        <div class="question_content">{{ $cementPurchase->date }}</div>
                                    </th>
                                    <td>{{ $cementPurchase->bags }}</td>
                                    <td>{{ $cementPurchase->supplier_id .' ' .$cementPurchase->getSupplier->name }}</td>
                                    <td>
                                        {{ $cementPurchase->site_id .' ' .'Godown' }}
                                    </td>
                                    <td>{{$cementPurchase->employee_id .' ' .$cementPurchase->getEmployee->name }}</td>
                                    <td>{{ $cementPurchase->remark }}</td>
                                    <td>
                                        <a href="{{ route('edit.godown.cementPurchase' , ['cement_purchase' => $cementPurchase->id, 'user' => session('godown')]) }}" class="btn btn-sm btn-info">Edit</a>
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