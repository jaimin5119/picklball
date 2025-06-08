<header class="header">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <div class="navbar-header">
                    <a id="toggle-btn" data-bs-toggle href="#" class="menu-btn">
                        <i class="fa fa-bars fa-2x"> </i>
                    </a>
                    <a href="{{ route('admin.dash') }}" class="navbar-brand">
                        <div class="brand-text d-none d-md-inline-block"><strong class="text-primary">Dynamic Website
                            </strong></div>
                    </a></div>
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">

                    {{--  @if(Auth::user()->role_id == '2') --}}
                        <?php
                        // $notifications = \Illuminate\Support\Facades\DB::table('gym_notification')->where('gym_id',Auth::user()->id)->where('is_seen','0')->orderBy('id','DESC')->get();
                        // $count = count(DB::table('gym_notification')->where('gym_id',Auth::user()->id)->where('is_seen','0')->get());
                        ?>
                        <li class="dropdown">
                            <!-- <a href="#" id="" class="nav-link fa fa-bell">
                                <span class="badge badge-danger badge-counter"></span>
                            </a> -->

                            <ul class="dropdown-menu" style="width: 250px">
                                {{-- @if($count > 0) --}}
                                    {{-- @foreach($notifications as $notif) --}}
                                        <li class="border-bottom">
                                            <a href="#" class="text-danger"></a>
                                                <p style="font-size: 12px">...</p>

                                        </li>
                                    {{-- @endforeach --}}
                                {{-- @else --}}
                                    <li class="border-bottom">
                                        <center><p style="font-size: 14px">No New Notification</p> </center>
                                    </li>
                                {{-- @endif --}}
                            </ul>
                        </li>
                {{-- @endif --}}
                    <li class="nav-item">
                        <a class="dropdown-item" href="{{ route('admin.change_pwd') }}">
                            <span class="d-none d-sm-inline-block">Change Password</span>
                            <i class="fa fa-key"></i>
                        </a>
                    </li>
                <!-- Log out-->
                    <li class="nav-item">
                        <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                            <span class="d-none d-sm-inline-block">Logout</span>
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    {{--    //logout modal--}}
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a href="{{ route('admin.logout') }}"><button type="button" class="btn btn-primary">Logout</button>
                    </a>
                    <!-- <button type="button" class="btn btn-primary" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</button>
                    <form id="logout-form" action="#" method="POST" class="d-none">
                        @csrf

                    </form> -->
                </div>
            </div>
        </div>
    </div>
</header>
