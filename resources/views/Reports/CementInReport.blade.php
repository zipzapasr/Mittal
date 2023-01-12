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
                <h2 style="padding: 20px 0px" id="">Cement In Report</h2>
                <h4>Filter</h4>
                <!-- <form action="{{route('filter.siteReport')}}" method="post"> -->
                @csrf
                <div class="row">
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
                // var site = $('select[name=site]').find('option:selected').val();
                var from = $('input[name=from]').val();
                var to = $('input[name=to]').val();

                $('.white_card_body').find('.mytable').html('');
                $('.white_card_body').find('.mytable').html('<table id="table" class="table table-strip container"> <thead id="thead"> </thead> <tbody id="tbody"> </tbody> </table>')

                if (from && to) {
                    $.ajax({
                        url: "{{route('filter.cementInReport')}}",
                        type: 'post',
                        data: {
                            _token: token,
                            to: to,
                            from: from
                        },
                        success: function(res) {
                            //console.log(res)
                            $('#thead').empty();
                            $('#tbody').empty();

                            // $('#thead').html() = '';
                            $('#thead').append('<tr> <th style="font-weight:bold;">Date</th> <th style="font-weight:bold;">To Site Name</th> <th style="font-weight:bold;">From Site Name</th> <th style="font-weight:bold;">Num of Bags</th> </tr>');

                            res.data.forEach(cement_in => {
                                if(cement_in.get_to_site != null && (cement_in.from_site_id == 0 || cement_in.get_from_site != null)){
                                    $('tbody').append('<tr>' + '<th>' + cement_in.date + '</th>' + '<th>' + cement_in.get_to_site.site_name + '</th>' + '<th>' + (cement_in.from_site_id == 0 ? 'Godown' : cement_in.get_from_site.site_name) + '</th>' + '<th>' + cement_in.bags + '</th>' + '</tr>');
                                }
                            });


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