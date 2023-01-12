<style>
    a {
        text-decoration: none;
    }
</style>
<nav class="sidebar vertical-scroll  ps-container ps-theme-default ps-active-y">
            <div class="logo d-flex justify-content-between">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('SuryaConstructorsLogo.png') }}" alt="">
                    {{--<h3 style="text-align: center ">Surya Contractors</h3>--}}
                </a>
                <div class="sidebar_close_icon d-lg-none">
                    <i class="ti-close"></i>
                </div>
            </div>
            <ul id="sidebar_menu">
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/2.svg') }}" alt="">
                        </div>
                        <span>Login</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('login.data_entry_operator') }}">Data Entry Operator</a>
                        </li>
                        <li>
                            <a href="{{ route('login.project_manager') }}">Project Manager</a>
                        </li>
                    </ul>
                </li>
                <li class="mm-active">
                    <a class="" href="{{ route('home') }}" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/dashboard.svg') }}" alt="">
                        </div>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/2.svg') }}" alt="">
                        </div>
                        <span>Employee</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.employee') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.employee') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Unit</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.unit') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.unit') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Activities</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.activity') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.activity') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Field Type</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.field_type') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.field_type') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>My Sites</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.site') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.site') }}">Create</a>
                        </li>
                    </ul>
                </li>

                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Petty Contractors</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.contractor') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.contractor') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Reports</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('view.siteReport') }}">Site Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.siteTotalReport') }}">Site Total Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.contractorReport') }}">Petty Contractor Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.cementPurchaseReport') }}">Cement Purchase Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.cementInReport') }}">Cement In Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.cementOutReport') }}">Cement Out Report</a>
                        </li>
                    </ul>
                </li>

                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Godown Reports</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('view.godownPurchaseReport') }}">Godown Purchase Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.godownOutReport') }}">Godown Out Report</a>
                        </li>
                        <li>
                            <a href="{{ route('view.godownInReport') }}">Cement Ins by Godown Report</a>
                        </li>
                    </ul>
                </li>

                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Cement Suppliers</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.cementsupplier') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.cementsupplier') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Site Transfers</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('list.sitetransfer') }}">List</a>
                        </li>
                        <li>
                            <a href="{{ route('create.sitetransfer') }}">Create</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Cement Ledger</span>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('view.siteLedger') }}">Site Ledger</a>
                        </li>
                        <li>
                            <a href="{{ route('view.allLedger') }}">All Ledger</a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a class="has-arrow" href="{{route('giveEditAccessView')}}" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>Give Edit Access</span>
                    </a>
                </li>
                <li class="">
                    <a class="has-arrow" href="{{route('viewEditAccess')}}" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>View Edit Access</span>
                    </a>
                </li>
                <li class="">
                    <a class="has-arrow" href="{{route('logs.viewEditLogs')}}" aria-expanded="false">
                        <div class="icon_menu">
                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">
                        </div>
                        <span>View Edit Logs</span>
                    </a>
                </li>
                <li class="">

                    <a class="has-arrow" href="{{ route('change.password', ['user' => auth()->user()]) }}" aria-expanded="false">

                        <div class="icon_menu">

                            <img src="{{ asset('admin/img/menu-icon/3.svg') }}" alt="">

                        </div>

                        <span>Change Password</span>

                    </a>

                </li>

            </ul>
        </nav>