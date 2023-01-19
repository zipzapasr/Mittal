@extends('layouts.app')

<style>
    td,th, tr {
        text-align: center;
        justify-items: center;
    }
</style>

@section('content')

<div class="row ">

    <div class="col-lg-12">

        <div class="white_card card_height_100 mb_30">

            <div class="white_card_body">

                <h2 style="padding: 20px 0px" id="">Ledger</h2>

                <h4>Filter</h4>

                @csrf

                <div class="row">

                    {{-- <div class="col-md-3">

                        <label>

                            Site

                            <select type="date" class="form-control" name="site">

                                @foreach($sites as $site)

                                <option value="{{$site->id}}">{{$site->serial_no}}: {{$site->site_name}}</option>

                                @endforeach

                            </select>

                        </label>

                    </div> --}}

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



            var token = $('input[name=csrf_token]').val();

            // var site = $('select[name=site]').find('option:selected').val();

            var from = $('input[name=from]').val();

            var to = $('input[name=to]').val();



            $('.white_card_body').find('.mytable').html('');

            $('.white_card_body').find('.mytable').html('<table id="table" class="table table-strip container"> <thead id="thead"> </thead> <tbody id="tbody"> </tbody> </table>')



            if (from && to) {

                $.ajax({

                    url: "{{route('filter.allLedger')}}",

                    type: 'post',

                    data: {

                        _token: token,

                        // site: site,

                        to: to,

                        from: from

                    },

                    success: function(res) {

                        //console.log(res.data)

                        $('#thead').empty();

                        $('#tbody').empty();

                        var siteNames = ''
                        var openingTotal = '<tr> <th style="font-weight: bold;">Opening stock</th> '
                        var receivedTotal = '<tr> <th style="font-weight: bold;">Received</th>'
                        var purchaseTotal = '<tr> <th style="font-weight: bold;">Purchased</th>'
                        var consumptionTotal = '<tr> <th style="font-weight: bold;">Consumption</th>'
                        var transferTotal = '<tr> <th style="font-weight: bold;">Transferr</th>'
                        var transferredToClientTotal = '<tr> <th style="font-weight: bold;">Transferr To Client</th>'
                        var closingTotal = '<tr> <th style="font-weight: bold;">Closing Stock</th> '
                        var wastageTotal = '<tr> <th style="font-weight: bold;">Wastage</th> '

                        var openingAllSites = 0;
                        var receivedAllSites = 0;
                        var purchaseAllSites = 0;
                        var consumptionAllSites = 0;
                        var transferAllSites = 0;
                        var transferredToClientAllSites = 0;
                        var closingAllSites = 0;
                        var wastageAllSites = 0;


                        var startDate = (res.request.from).toString()
                        var endDate = (res.request.to).toString()
                        // console.log(startDate, endDate)

                        Object.entries(res.data).forEach(entry => {
                            const [site_id, Site] = entry
                            // var wastage = '<td>' + '</td>'
                            siteNames += '<th style="font-weight:bold;">' + Site['site']['site_name'] + '</th>'
                            var opening = 0
                            var receive = 0
                            var purchase = 0
                            var consume = 0
                            var transfer = 0
                            var transferToClient = 0
                            var closing = 0
                            var waste = 0

                            var openingStock = 0
                            var closingStock = 0

                            var consumption = 0
                            var received = 0
                            var transferred = 0
                            var purchased = 0
                            var transferredToClient = 0
                            var wastage = 0

                            Object.entries(Site).forEach(entry => {
                                const [key, value] = entry
                                if(key != 'site' && key > endDate) {
                                    return
                                }
                                // console.log(typeof key, typeof startDate, typeof endDate)
                                
                                if(key != 'site'){
                                    if(key == startDate) {
                                        opening += openingStock
                                    }
                                    consumption = parseInt(value.cement_consumption)
                                    received = parseInt(value.cement_ins)
                                    transferred = parseInt(value.cement_outs)
                                    purchased = parseInt(value.cement_purchases)
                                    transferredToClient = parseInt(value.cement_transfers)
                                    wastage = parseInt(value.cement_wastage)

                                    if(startDate <= key  && key <= endDate){
                                        // console.log(startDate, key, endDate)
                                        receive += received
                                        purchase += purchased
                                        consume += consumption
                                        transfer += transferred
                                        transferToClient += transferredToClient
                                        waste += wastage

                                    }
                                    closingStock = (openingStock + received + purchased) - (consumption + transferred + transferredToClient)
                                    if(key == endDate) {
                                        closing += closingStock
                                    }
                                    openingStock = closingStock
                                }
                            })

                            openingAllSites += opening
                            receivedAllSites += receive
                            purchaseAllSites += purchase
                            consumptionAllSites +=consume
                            transferAllSites += transfer
                            transferredToClientAllSites += transferToClient
                            closingAllSites += closing
                            wastageAllSites += waste

                            openingTotal += '<th>' + opening + '</th>'
                            receivedTotal += '<th>' + receive + '</th>'
                            purchaseTotal += '<th>' + purchase + '</th>'
                            consumptionTotal += '<th>' + consume + '</th>'
                            transferTotal += '<th>' + transfer + '</th>'
                            transferredToClientTotal += '<th>' + transferToClient +'</th>'
                            closingTotal += '<th>' + closing + '</th>'
                            wastageTotal += '<th>' + waste + '</th>'

                        });

                        openingTotal += '<td>' + openingAllSites+'</td>' +  '</tr>'
                        receivedTotal += '<td>' + receivedAllSites + '</td>' +  '</tr>'
                        purchaseTotal += '<td>' + purchaseAllSites+ '</td>' +  '</tr>'
                        consumptionTotal += '<td>' + consumptionAllSites + '</td>' +  '</tr>'
                        transferTotal += '<td>' + transferAllSites + '</td>' +  '</tr>'
                        transferredToClientTotal += '<td>' + transferredToClientAllSites + '</td>' +  '</tr>'
                        closingTotal += '<td>' + closingAllSites + '</td>' +  '</tr>'
                        wastageTotal += '<td>' + wastageAllSites  + '</td>' +  '</tr>'

                        $('#thead').append('<tr> <th style="font-weight:bold;">Description</th>' + siteNames + '<td style="font-weight: bold;">Total</td>' + '</tr>');

                        $('#tbody').append(openingTotal);
                        $('#tbody').append(receivedTotal);
                        $('#tbody').append(purchaseTotal);
                        $('#tbody').append(consumptionTotal);
                        $('#tbody').append(transferTotal);
                        $('#tbody').append(transferredToClientTotal);
                        $('#tbody').append(closingTotal);
                        $('#tbody').append(wastageTotal)
 
                                
                        setTimeout(function() {

                            $('#table').dataTable({

                                scrollX: true,

                                dom: 'lBfrtip',

                                //dom: 'lrtip',

                                buttons: [

                                    'copy', 'csv', 'excel', 'pdf'

                                ],

                                "ordering": false

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