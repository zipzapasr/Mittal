@extends('layouts.app')

<style>
    td,th, tr {
        text-align: center;
    }
</style>

@section('content')

<div class="row ">

    <div class="col-lg-12">

        <div class="white_card card_height_100 mb_30">

            <div class="white_card_body">

                <h2 style="padding: 20px 0px" id="siteName">Site Ledger</h2>

                <h4>Filter</h4>

                <!-- <form action="{{route('filter.siteReport')}}" method="post"> -->

                @csrf

                <div class="row">

                    <div class="col-md-3">

                        <label>

                            Site

                            <select type="date" class="form-control" name="site">

                                @foreach($sites as $site)

                                <option value="{{$site->id}}">{{$site->serial_no}}: {{$site->site_name}}</option>

                                @endforeach

                            </select>

                        </label>

                    </div>

                    <div class="col-md-3">

                        <label>

                            From

                            <input type="date" class="form-control" name="from" />

                        </label>

                    </div>

                    <div class="col-md-3">

                        <label>

                            To

                            <input type="date" class="form-control" name="to" />

                        </label>

                    </div>

                    <div class="col-md-2" style="margin-top: 25px">

                        <button type="button" class="btn btn-primary btn-sm" id="formSubmitBtn"> Get Data </button>

                    </div>

                </div>

                <!-- </form> -->

                <div class="mytable mt-3">

                </div>



            </div>

        </div>

    </div>

</div>

@endsection

@section('javascript')

{{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

<script>

    $(document).ready(function() {

        $('#formSubmitBtn').click(function() {

            //console.log('clicked')
            var token = $('input[name=csrf_token]').val();

            var site = $('select[name=site]').find('option:selected').val();

            var from = $('input[name=from]').val();

            var to = $('input[name=to]').val();



            $('.white_card_body').find('.mytable').html('');

            $('.white_card_body').find('.mytable').html('<table id="table" class="table table-strip container"> <thead id="thead"> </thead> <tbody id="tbody"> </tbody> </table>')



            if (site && from && to) {

                $.ajax({

                    url: "{{route('filter.siteLedger')}}",

                    type: 'post',

                    data: {

                        _token: token,

                        site: site,

                        to: to,

                        from: from

                    },

                    success: function(res) {

                        //console.log(res)

                        $('#thead').empty();

                        $('#tbody').empty();

                        $('#siteName').empty();



                        $('#siteName').append('Site Ledger: ' + '<span class="text-success">' + res.site.site_name + '</span>')

                        //console.log(res.data);

                        // $('#thead').html() = '';

                        $('#thead').append('<tr> <th style="font-weight:bold;">Date</th> <th style="font-weight:bold;">Opening Stock</th> <th style="font-weight:bold;">Purchase</th> <th style="font-weight:bold;">Received</th> <th style="font-weight:bold;">Transferred</th> <th style="font-weight:bold;">Transferred To Client</th> <th style="font-weight:bold;">Consumption</th> <th style="font-weight:bold;">Closing Stock</th> <th style="font-weight:bold;">Wastage</th> <th style="font-weight:bold;">Remark</th>  </tr>');


                        var opening_stock = 0

                        var TotalOpeningStock = 0
                        var TotalCementPurchase = 0
                        var TotalCementIn = 0
                        var TotalCementOut = 0
                        var TotalCementConsumption = 0
                        var TotalCementTransfer = 0
                        var TotalClosingStock = 0
                        var TotalWastage = 0



                        for (var date in res.data) {

                            var cement_purchase = parseInt(res.data[date].cement_purchases)

                            var cement_in = parseInt(res.data[date].cement_ins)

                            var cement_out = parseInt(res.data[date].cement_outs)

                            var cement_consumption = parseInt(res.data[date].cement_consumption)

                            var cement_transfer = parseInt(res.data[date].cement_transfers)

                            var closing_stock = (opening_stock + cement_purchase + cement_in) - (cement_out + cement_consumption + cement_transfer);

                            var wastage = parseInt(res.data[date].cement_wastage)


                            if (date >= res.request.from && date <= res.request.to) {

                                var tbody = '<tr>'

                                tbody += '<td style="font-weight:bold;">' + date + '</td>'

                                tbody += '<td style="font-weight:bold;">' + opening_stock + '</td>'

                                cement_purchase > 0 ?

                                    tbody += '<td style="font-weight: bold">' + cement_purchase + '</td>' :

                                    tbody += '<td style="font-weight: regular">' + cement_purchase + '</td>'



                                cement_in > 0 ?

                                    tbody += '<td style="font-weight: bold">' + cement_in + '</td>' :

                                    tbody += '<td style="font-weight: regular">' + cement_in + '</td>'



                                cement_out > 0 ?

                                    tbody += '<td style="font-weight: bold">' + cement_out + '</td>' :

                                    tbody += '<td style="font-weight: regular">' + cement_out + '</td>'

                                
                                cement_transfer > 0 ?

                                    tbody += '<td style="font-weight:bold' + '">' + cement_transfer + '</td>' :

                                    tbody += '<td style="font-weight:regular' + '">' + cement_transfer + '</td>'



                                cement_consumption > 0 ?

                                    tbody += '<td style="font-weight:bold' + '">' + cement_consumption + '</td>' :

                                    tbody += '<td style="font-weight:regular' + '">' + cement_consumption + '</td>'



                                tbody += '<td style="font-weight:bold;">' + closing_stock + '</td>'

                                wastage > 0 ?

                                    tbody += '<td style="font-weight:bold' + '">' + wastage + '</td>' :

                                    tbody += '<td style="font-weight:regular' + '">' + wastage + '</td>'

                                tbody += '<td>' + res.data[date]['remarks'] + '</td>'

                                tbody += '</tr>'



                                $('#tbody').append(tbody);

                                TotalOpeningStock += opening_stock
                                TotalCementPurchase += cement_purchase
                                TotalCementIn += cement_in
                                TotalCementOut += cement_out
                                TotalCementConsumption += cement_consumption
                                TotalCementTransfer += cement_transfer
                                TotalClosingStock += closing_stock
                                TotalWastage += wastage

                            }

                            
                            opening_stock = closing_stock

                        }

                        $('#tbody').append('<tr> <td style="font-weight:bold;">Total</td> <td style="font-weight:bold;">' + TotalOpeningStock + '</td>' + '<td style="font-weight:bold;">' + TotalCementPurchase + '</td>' + '<td style="font-weight:bold;">' + TotalCementIn + '</td> <td style="font-weight:bold;">' + TotalCementOut+ '</td> <td style="font-weight:bold;">' + TotalCementTransfer+ '</td> <td>' + TotalCementConsumption +'</td> <td style="font-weight:bold;">' + TotalClosingStock + '</td>' + '</td> <td style="font-weight:bold;">' + TotalWastage + '</td>' +  '<td> </td> </tr>')


                        setTimeout(function() {

                            $('#table').dataTable({

                                scrollX: true,

                                dom: 'lBfrtip',

                                //dom: 'lrtip',

                                buttons: [

                                    'copy', 'csv', 'excel', 'pdf'

                                ],

                            });

                        }, 1000)

                    },

                    error: function(err) {

                        console.log(err);

                    }

                })

            }

        })

    })

</script>



@endsection