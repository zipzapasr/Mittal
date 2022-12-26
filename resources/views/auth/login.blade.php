<!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Sales</title>
        <link rel="stylesheet" href="{{ asset('admin/css/bootstrap1.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/vendors/themefy_icon/themify-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/vendors/font_awesome/css/all.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/vendors/scroll/scrollable.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/css/metisMenu.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/style1.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/css/colors/default.css') }}" id="colorSkinCSS">
    </head>
    <body class="crm_body_bg">
        <section>
            <div class="row m-2">
                <div class="col-md-4 col-md-offset-2">
                    <a class="btn btn-primary" href="{{ route('login.project_manager') }}">
                        {{ __('Login as Project Manager') }}
                    </a>
                </div>
                <div class="col-md-4 col-md-offset-2">
                    <a class="btn btn-primary" href="{{ route('login.data_entry_operator') }}">
                        {{ __('Login as Data Entry Operator') }}
                    </a>
                </div>
            </div>
        </section>
        <section class=" dashboard_part">
            <div class="main_content_iner ">
                <div class="container-fluid p-0">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="white_box mb_30">
                                <div class="row justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="modal-content cs_modal">
                                            @if (Session::has('invalidLogin'))
                                                <div class="alert alert-info">{{ Session::get('invalidLogin') }}</div>
                                            @endif
                                            <div class="modal-header justify-content-center theme_bg_1">
                                                <h5 class="modal-title text_white">Log in as Super Admin/ Admin</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('login') }}">
                                                    @csrf

                                                    <div class="row mb-3">
                                                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            
                                                        <div class="col-md-6">
                                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                            
                                                    <div class="row m-2">
                                                        <div class="col-md-8 offset-md-4">
                                                            <button type="submit" class="btn btn-primary">
                                                                {{ __('Login') }}
                                                            </button>
                                                        </div>
                                                    </div>
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
       
        <script src="{{ asset('admin/js/jquery1-3.4.1.min.js') }}"></script>
        <script src="{{ asset('admin/js/popper1.min.js') }}"></script>
        <script src="{{ asset('admin/js/bootstrap.min.html') }}"></script>
        <script src="{{ asset('admin/js/metisMenu.js') }}"></script>
        <script src="{{ asset('admin/vendors/scroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/scroll/scrollable-custom.js') }}"></script>
        <script src="{{ asset('admin/js/custom.js') }}"></script>
    </body>
</html>