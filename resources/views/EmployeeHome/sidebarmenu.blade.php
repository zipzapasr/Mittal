<style>

    a {

        text-decoration: none;

    }

</style>

<nav class="sidebar vertical-scroll  ps-container ps-theme-default ps-active-y">{{--style="width: 15%;"--}}

            <div class="logo d-flex justify-content-between">

                <a href="{{ route('employee.home') }}">

                    <img src="{{ asset('SuryaConstructorsLogo.png') }}" alt=""> 

                    {{-- <h3 style="text-align: center ">Surya Contractors</h3> --}}

                </a>

                <div class="sidebar_close_icon d-lg-none">

                    <i class="ti-close"></i>

                </div>

            </div>

            <ul id="sidebar_menu">



                <li class="">

                    <a class="has-arrow" href="{{ route('employee.mysites') }}" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>My Sites</span>

                    </a>

                </li>

                <li class="">

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement In</span>


                    </a>

                    <ul>

                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <span>In House</span>
                            </a>
                            <ul>

                                <li><a href="{{ route('list.cementIn', ['user' => session('employee')]) }}">List</a></li>

                                <li><a href="{{ route('create.cementIn', ['user' => session('employee')]) }}" >Create</a></li>

                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow" href="#">
                                <span>From Supplier</span>
                            </a>
                            <ul>

                                <li><a href="{{ route('list.cementPurchase', ['user' => session('employee')]) }}">List</a></li>

                                <li><a href="{{ route('create.cementPurchase', ['user' => session('employee')]) }}" >Create</a></li>

                            </ul>
                        </li>

                    </ul>

                </li>

                <li class="">

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement Out</span>


                    </a>

                    <ul>

                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <span>In House</span>
                            </a>
                            <ul>

                                <li><a href="{{ route('list.cementOut', ['user' => session('employee')]) }}">List</a></li>

                                <li><a href="{{ route('create.cementOut', ['user' => session('employee')]) }}" >Create</a></li>

                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow" href="#">
                                <span>Transfer To Client</span>
                            </a>
                            <ul>

                                <li><a href="{{ route('list.cementTransfer', ['user' => session('employee')]) }}">List</a></li>

                                <li><a href="{{ route('create.cementTransfer', ['user' => session('employee')]) }}" >Create</a></li>

                            </ul>
                        </li>

                    </ul>

                </li>

                {{-- <li class="">

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement Out</span>

                    </a>

                    <ul>

                        <li><a href="{{ route('list.cementOut', ['user' => session('employee')]) }}">List</a></li>

                        <li><a href="{{ route('create.cementOut', ['user' => session('employee')]) }}" >Create</a></li>

                    </ul>

                </li> --}}



                {{-- <li class="">

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement Purchase</span>

                    </a>

                    <ul>

                        <li><a href="{{ route('list.cementPurchase', ['user' => session('employee')]) }}">List</a></li>

                        <li><a href="{{ route('create.cementPurchase', ['user' => session('employee')]) }}" >Create</a></li>

                    </ul>

                </li>--}}

                {{-- <li class=""> 

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement Transfer to Client</span>

                    </a>

                    <ul>

                        <li><a href="{{ route('list.cementTransfer', ['user' => session('employee')]) }}">List</a></li>

                        <li><a href="{{ route('create.cementTransfer', ['user' => session('employee')]) }}" >Create</a></li>

                    </ul>

                </li> --}}



                {{-- <li class="">

                    <a class="has-arrow" href="{{ route('change.employee.password', ['user' => session('employee')]) }}" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Change Password</span>

                    </a>

                </li> --}}

            </ul>

        </nav>