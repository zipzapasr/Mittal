<!DOCTYPE html>
<html lang="zxx">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Login</title>


<link rel="stylesheet" href="{{asset('admin/css/bootstrap1.min.css')}}" />

<link rel="stylesheet" href="{{asset('admin/vendors/themefy_icon/themify-icons.css')}}" />
<link rel="stylesheet" href="{{asset('admin/vendors/font_awesome/css/all.min.css')}}" />


<link rel="stylesheet" href="{{asset('admin/vendors/scroll/scrollable.css')}}" />

<link rel="stylesheet" href="{{asset('admin/css/metisMenu.css')}}">

<link rel="stylesheet" href="{{asset('admin/css/style1.css')}}" />
<link rel="stylesheet" href="{{asset('admin/css/colors/default.css')}}" id="colorSkinCSS">
</head>
<body class="crm_body_bg">


<section class="main_content dashboard_part large_header_bg">

<div class="main_content_iner ">
<div class="container-fluid p-0">
<div class="row justify-content-center">
<div class="col-12">
<div class="dashboard_header mb_50">
<div class="row">
<div class="col-lg-6">
<div class="dashboard_header_title">
<h3>Login for Godown</h3>
</div>
</div>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="white_box mb_30">
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="modal-content cs_modal">
<div class="modal-body">

<div>{{(session('message')) ? session('message') : ""}}</div>

<form method="POST" action="{{ route('authenticate.godown') }}" >
    @csrf 
    <div class="">
        <input type="text" class="form-control" placeholder="Enter your email" name="email">
    </div>
    <div class="">
        <input type="password" class="form-control" placeholder="Password" name="password">
    </div>
    <button type="submit" class="btn_1 full_width text-center">Log in</button>
</form>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


</section>

<script src="{{asset('admin/js/jquery1-3.4.1.min.js')}}"></script>

<script src="{{asset('admin/js/popper1.min.js')}}"></script>

<script src="{{asset('admin/js/bootstrap.min.html')}}"></script>

<script src="{{asset('admin/js/metisMenu.js')}}"></script>

<script src="{{asset('admin/vendors/scroll/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('admin/vendors/scroll/scrollable-custom.js')}}"></script>

<script src="{{asset('admin/js/custom.js')}}"></script>
</body>

<!-- Mirrored from demo.dashboardpack.com/sales-html/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 07 Sep 2022 06:57:24 GMT -->
</html>