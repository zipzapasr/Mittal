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

                <h2 style="padding: 20px 0px" id="siteInfo">Total Site Report</h2>

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


            var token = $('input[name=csrf_token]').val();

            var site = $('select[name=site]').find('option:selected').val();

            var from = $('input[name=from]').val();

            var to = $('input[name=to]').val();


            $('.white_card_body').find('.mytable').html('');

            $('.white_card_body').find('.mytable').html('<table id="table" class="table table-strip container"> <thead id="thead"> </thead> <tbody id="tbody"> </tbody> </table>')



            if (site && from && to) {

                $.ajax({

                    url: "{{route('filter.siteTotalReport')}}",

                    type: 'post',

                    data: {

                        _token: token,

                        site: site,

                        to: to,

                        from: from

                    },

                    success: function(res) {

                        console.log(res)

                        $('#thead').empty();

                        $('#tbody').empty();

                        $('#siteInfo').empty();



                        $('#siteInfo').append('Total Site Report: ' + '<span class="text-success">' + res.site_name + '</span>' + '<h4>Site Created at: ' + res.site_start_date + '</h4>')

                        //console.log(res.data);

                        $('#thead').append('<tr> <th style="font-weight:bold;">Activity Name</th> <th style="font-weight:bold;">From</th> <th style="font-weight:bold;">Till</th> <th style="font-weight:bold;">Total Qty by Petty Contractors</th> <th style="font-weight:bold;">Total Qty by Self</th> <th style="font-weight:bold;">Estimate Qty</th> <th style="font-weight:bold;">Total Qty</th> <th style="font-weight:bold;">Remaining Qty</th> </tr>');


                        for (var activity in res.data) {

                            var qty = 0

                            var skilled_days = 0

                            var unskilled_days = 0

                            var activity_name = ''

                            var qtyByContractors = 0

                            var qtyBySelf = 0

                            var estQty = 0

                            if(activity in res.siteActivities){
                                estQty = res.siteActivities[activity]
                            }


                            for(var activityInd in res.data[activity]){

                                var currActivity = res.data[activity][activityInd]

                                qty += parseInt(currActivity.qty)

                                skilled_days += parseFloat(parseInt(currActivity.skilled_workers) + parseInt(currActivity.skilled_workers_overtime) / 8)

                                unskilled_days += parseFloat(parseInt(currActivity.unskilled_workers) + parseInt(currActivity.unskilled_workers_overtime) / 8)



                                if(activity_name == '') {

                                    activity_name = currActivity.get_activity.activity_name

                                }


                                if(currActivity.field_type_id  == '4') {

                                    qtyByContractors += parseInt(currActivity.qty)

                                } else {

                                    qtyBySelf += parseInt(currActivity.qty)

                                }

                            }

                            $('#tbody').append('<tr>' + '<td>' + activity_name + '</td>' + '<td>' + res.from + '</td>' + '<td>' + res.to + '</td>' + '<td>' + qtyByContractors + '</td>' + '<td>' + qtyBySelf + '</td>' + '<td style="font-weight:bold;">' + estQty + '</td>' + '<td style="font-weight:bold;">' + qty + '</td>' + '<td style="font-weight:bold;">' + (estQty - qty) + '</td>' + '</tr>');

                        }


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