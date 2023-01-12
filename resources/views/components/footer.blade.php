<script src="{{asset('admin/js/jquery1-3.4.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{--<script src="{{asset('admin/js/popper1.min.js') }}"></script>--}}
<script src="{{asset('admin/js/metisMenu.js') }}"></script>
<script src="{{asset('admin/vendors/count_up/jquery.waypoints.min.js') }}"></script>
{{--<script src="{{asset('admin/vendors/chartlist/Chart.min.js') }}"></script>--}}
<script src="{{asset('admin/vendors/count_up/jquery.counterup.min.js') }}"></script>
<script src="{{asset('admin/vendors/niceselect/js/jquery.nice-select.min.js') }}"></script>
<script src="{{asset('admin/vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>
<script src="{{asset('admin/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('admin/vendors/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{asset('admin/vendors/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{asset('admin/vendors/datatable/js/buttons.flash.min.js') }}"></script>
<script src="{{asset('admin/vendors/datatable/js/jszip.min.js') }}"></script>
{{--<script src="{{asset('admin/vendors/datatable/js/pdfmake.min.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/datatable/js/vfs_fonts.js') }}"></script>--}}
<script src="{{asset('admin/vendors/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{asset('admin/vendors/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{asset('admin/vendors/datepicker/datepicker.js') }}"></script>
<script src="{{asset('admin/vendors/datepicker/datepicker.en.js') }}"></script>
<script src="{{asset('admin/vendors/datepicker/datepicker.custom.js') }}"></script>
{{--<script src="{{asset('admin/js/chart.min.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/chartjs/roundedBar.min.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/progressbar/jquery.barfiller.js') }}"></script>--}}
<script src="{{asset('admin/vendors/tagsinput/tagsinput.js') }}"></script>
<script src="{{asset('admin/vendors/text_editor/summernote-bs4.js') }}"></script>
{{--<script src="{{asset('admin/vendors/am_chart/amcharts.js') }}"></script>--}}
<script src="{{asset('admin/vendors/scroll/perfect-scrollbar.min.js') }}"></script>
<script src="{{asset('admin/vendors/scroll/scrollable-custom.js') }}"></script>
<script src="{{asset('admin/vendors/vectormap-home/vectormap-2.0.2.min.js') }}"></script>
<script src="{{asset('admin/vendors/vectormap-home/vectormap-world-mill-en.js') }}"></script>
{{--<script src="{{asset('admin/vendors/apex_chart/apex-chart2.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/apex_chart/apex_dashboard.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/echart/echarts.min.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/chart_am/core.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/chart_am/charts.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/chart_am/animated.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/chart_am/kelly.js') }}"></script>--}}
{{--<script src="{{asset('admin/vendors/chart_am/chart-custom.js') }}"></script>--}}
<script src="{{asset('admin/js/dashboard_init.js') }}"></script>
<script src="{{asset('admin/js/custom.js') }}"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js" integrity="sha512-0wCoO9w07Mu4MnC918HEsFyXhVJVoxeq+RD4XXYukmLswUHMCRbBomZE+NjxBtv88QTU/fImTY+PclhlMpJ4JA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js"></script>

{{-- <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> 
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}


@yield('javascript')
<script>
    var flash = document.getElementById('flashAlert');
    if(flash) {
        setTimeout(() => {
        flash.style.display = 'none';
    }, 1000);
    } 
    $(document).ready(function () {
        //change selectboxes to selectize mode to be searchable
        $("select").select2();
        // $('.select2').select2();
    });
</script>