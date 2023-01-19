<style>

    a {

        text-decoration: none;

    }

</style>

<nav class="sidebar vertical-scroll  ps-container ps-theme-default ps-active-y">

            <div class="logo d-flex justify-content-between">

                <a href="{{ route('godown.home') }}">

                    <img src="{{ asset('SuryaConstructorsLogo.png') }}" alt=""> 

                    {{-- <h3 style="text-align: center ">Surya Contractors</h3> --}}

                </a>

                <div class="sidebar_close_icon d-lg-none">

                    <i class="ti-close"></i>

                </div>

            </div>

            <ul id="sidebar_menu">


                <li class="">

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement Out</span>

                    </a>

                    <ul>

                        <li><a href="{{ route('list.godown.cementOut') }}">List</a></li>

                        <li><a href="{{ route('create.godown.cementOut') }}" >Create</a></li>

                    </ul>

                </li>



                <li class="">

                    <a class="has-arrow" href="#" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Cement Purchase</span>

                    </a>

                    <ul>

                        <li><a href="{{ route('list.godown.cementPurchase') }}">List</a></li>

                        <li><a href="{{ route('create.godown.cementPurchase') }}" >Create</a></li>

                    </ul>

                </li>



            </ul>

        </nav>