@extends('./Godown/layout')
@section('content')
    {{-- {{dd($cementPurchases)}} --}}
    <p style="color: white;">Role: {{ (session('godown')->role  == 5) ? 'Godown' : '' }}</p>
    <div class="row ">
        <div class="col-lg-12">
            <div class="white_card card_height_100 mb_30">
                <div class="white_card_body">
                    <h4 style="padding: 20px 0px" >List Cement Purchases</h4>
                    <div class="table-responsive">
                        <table class="table cell-border compact">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Number of Bags</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cementPurchases as $cementPurchase)
                                    <tr>
                                        <th scope="row">
                                            <div class="question_content">{{ date('d-m-Y', strtotime($cementPurchase->date)) }}</div>
                                        </th>
                                        <td>{{ $cementPurchase->bags }}</td>
                                        <td>{{ $cementPurchase->supplier_id .' ' .$cementPurchase->getSupplier->name }}</td>
                                        <td>{{ $cementPurchase->remark }}</td>
                                        <td>
                                            <a href="{{ route( 'edit.godown.cementPurchase' , ['cement_purchase' => $cementPurchase->id] ) }}" class="btn btn-sm btn-info {{ ($cementPurchase->date < $yesterday) ? 'disabled' : '' }}">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

    <script>

        // Initialize the DataTable

        //console.log('working');

        $(document).ready(function() {

            $('.table').dataTable({

                dom: 'lBfrtip',

                buttons: [

                    'copy', 'csv', 'excel', 'pdf'

               ],
               ordering: false

            });

        });

    </script>

@endsection