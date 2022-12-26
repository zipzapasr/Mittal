<!DOCTYPE html>
<html lang="zxx">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    @include('./components/head')
    <body class="crm_body_bg">
        @include('./EmployeeHome/sidebarmenu')
        <section class="main_content dashboard_part large_header_bg">
            @include('./components/header')
            <div style="text-align:right;" class="m-2">
                <a class="btn btn-danger" href="{{ route('employee.logout') }}" style="border: 3px solid black;">
                    <i class="bi bi-door-closed">Logout</i>
                </a>
            </div>
           
            <div class="main_content_iner overly_inner ">
                <div class="container-fluid p-0 ">
                    <div class="row">
                        <div class="col-12">
                            <div class="page_title_box d-flex align-items-center justify-content-between">
                                <div class="page_title_left">
                                    <h3 class="f_s_30 f_w_700 text_white">Dashboard</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Session::has('message'))
                        <p style="position:absolute;z-index:9999;right:10px;" class="alert {{ Session::get('alert-class', 'alert-info') }}" id="flashAlert">{{ Session::get('message') }}</p>
                    @endif
                    @yield('content')
                </div>
            </div>
            <div class="footer_part">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer_iner text-center">
                                <p>2020 © Influence - Designed by <a href="#">
                                        <i class="ti-heart"></i>
                                    </a>
                                    <a href="#"> Dashboard</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
 
        @include('./components/footer')
       
    </body>
</html>
