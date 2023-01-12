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

                <h2 style="padding: 20px 0px" id="siteName">Site Report</h2>

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

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

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

                    url: "{{route('filter.siteReport')}}",

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

                        $('#siteName').empty();

                        $('#siteName').append('Site Report: ' + '<span class="text-success">' + res.site_name + '</span>')

                        //console.log(res.data);

                        var start = new Date(res.request.from);

                        var end = new Date(res.request.to);

                        var date = new Date(start);

                        var dates = ''

                        var appenedDates = '';

                        while (date <= end) {

                            let newDate = '';

                            if (date.getDate() < 10) {

                                newDate += '0' + date.getDate();

                            } else {

                                newDate += date.getDate();

                            }

                            let newMonth = '';

                            let month = date.getMonth() + 1;

                            if (month < 10) {

                                newMonth += '0' + month;

                            } else {

                                newMonth += month;

                            }

                            dates += date.getFullYear() + '-' + newMonth + '-' + newDate + ','

                            appenedDates += ('<th style="font-weight:bold;">' + date.getFullYear() + '/' + newMonth + '/' + newDate + '</th>');

                            date.setDate(date.getDate() + 1);

                        }

                        if (dates != '' && dates[-1] == ',') {

                            dates -= ','

                        }

                        // $('#thead').html() = '';

                        $('#thead').append('<tr> <th style="font-weight:bold;">Activity Name</th> <th style="font-weight:bold;">Field Type</th>' + appenedDates + '<th style="font-weight:bold;">Total Qty</th> <th style="font-weight:bold;">Skilled Workers<small>(Days)</small></th> <th style="font-weight:bold;">UnSkilled Workers<small>(Days)</small></th> </tr>');


                        for (var activity in res.data) {
                            for(var field_type_id in res.data[activity]){
                                var qty = 0

                                var skilled_days = 0

                                var unskilled_days = 0

                                var activity_name = ''

                                var field_type_name = ''


                                dates = dates.toString().split(',')

                                var datesData = ''

                                for (var ind in dates) {

                                    _date = dates[ind]

                                    if (_date) {

                                        if (_date in res.data[activity][field_type_id]) {

                                            _sum = 0

                                            for (let entryInd in res.data[activity][field_type_id][_date]) {

                                                if (activity_name == '') {

                                                    activity_name = res.data[activity][field_type_id][_date][entryInd].get_activity.activity_name

                                                }

                                                if (field_type_name == '') {

                                                    field_type_name = res.data[activity][field_type_id][_date][entryInd].get_field_type.title

                                                }

                                                _sum += parseInt(res.data[activity][field_type_id][_date][entryInd].qty)

                                                skilled_days += parseInt(res.data[activity][field_type_id][_date][entryInd].skilled_workers)

                                                skilled_days += parseInt(res.data[activity][field_type_id][_date][entryInd].skilled_workers_overtime) / 8



                                                unskilled_days += parseInt(res.data[activity][field_type_id][_date][entryInd].unskilled_workers)

                                                unskilled_days += parseInt(res.data[activity][field_type_id][_date][entryInd].unskilled_workers_overtime) / 8

                                            }

                                            qty += parseInt(_sum)

                                            datesData += '<td style="font-weight:bold;">' + _sum + '</td>'

                                        } else {

                                            datesData += '<td>' + 0 + '</td>'

                                        }

                                    }

                                }

                                $('#tbody').append('<tr>' + '<td>' + activity_name + '</td>' + '<td>' + field_type_name + '</td>'  + datesData + '<td style="font-weight:bold;">' + qty + '</td>' + '<td>' + skilled_days + '</td>' + '<td>' + unskilled_days + '</td>');
                            }
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