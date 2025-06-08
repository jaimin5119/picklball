<nav class="side-navbar">
    <div class="side-navbar-wrapper">
        <!-- Sidebar Header    -->
        <!-- <div class="sidenav-header d-flex "> -->
        <div class="sidenav-header d-flex ">
            <!-- User Info-->
                                       <a href="https://www.spotplus.fr/admin/dashboard">
                <div class="sidenav-header-inner text-center"><img src="https://www.spotplus.fr/public/uploads/profile/admin-image.jpg" alt="person" class="img-fluid rounded-circle mCS_img_loaded">                          <h2 class="h5">Admin Dynamic Website</h2><span>Administrator</span>
                       </div>
           </a>
                   </div>

        <!-- Sidebar Navigation Menus-->

        <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">

                
                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class=" ">
                        <a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>User Management </a>
                        <ul id="exampledropdownDropdown" class="collapse list-unstyled ">

                            <li>
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span data-i18n="Analytics">User</span>
                                </a>

                            <li>
                            <li>
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span data-i18n="Analytics">Gym</span>
                                </a>

                            <li>

                        </ul>
                    </li> -->

                {{-- @endif --}}


                     <li class="">
                        <a href="{{ route('admin.dash') }}" class="">
                            <i class=""></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                   <!-- <li class="">
                        <a href="{{ route('admin.sample_form') }}" class="">
                            <i class=""></i>
                            <span>Sample form page</span>
                        </a>
                    </li>


                    <li class=" ">
                        <a href="#cmsDropDown" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>User Managment</a>
                        <ul id="cmsDropDown" class="collapse list-unstyled ">

                            <li>
                                <a href="{{ route('admin.list_users') }}" class="">
                                    <i class="material-icons"></i>
                                    <span data-i18n="Analytics">List User Managment</span>
                                </a>

                            <li>
                            <li>
                                <a href="{{ route('admin.bulk_add_user') }}" class="">
                                    <i class="material-icons"></i>
                                    <span data-i18n="Analytics">Bulk Upload Users</span>
                                </a>

                            <li>
                             

                        </ul>
                    </li> -->

                   

                    <li class="">
                        <a href="{{ route('admin.admin_users') }}" class="">
                            <i class=""></i>
                            <span>Users</span>
                        </a>
                    </li>

                    <!-- <li class="">
                        <a href="{{ route('admin.list_faqs') }}" class="">
                            <i class=""></i>
                            <span>FAQs</span>
                        </a>
                    </li> -->

                    <li class="">
                        <a href="{{ route('admin.list_cms_page') }}" class="">
                            <i class=""></i>
                            <span>CMS Pages</span>
                        </a>
                    </li>

                    <!-- <li class="">
                        <a href="{{ route('admin.list_ratings') }}" class="">
                            <i class=""></i>
                            <span>Ratings</span>
                        </a>
                    </li> -->

                    <!-- <li class="">
                        <a href="{{ route('admin.list_contact_us') }}" class="">
                            <i class=""></i>
                            <span>Contact Us</span>
                        </a>
                    </li> -->

                    <!-- <li class="">
                        <a href="{{ route('admin.list_notif') }}" class="">
                            <i class=""></i>
                            <span>Notifications</span>
                        </a>
                    </li> -->

                    <!-- <li class=" ">
                        <a href="#cmsDropDown" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>CMS Pages</a>
                        <ul id="cmsDropDown" class="collapse list-unstyled ">

                            <li>
                                <a href="{{ route('admin.list_cms_page') }}" class="">
                                    <i class="material-icons"></i>
                                    <span data-i18n="Analytics">List CMS Pages</span>
                                </a>

                            <li>
                            <li>
                                <a href="{{ route('admin.add_cms_page') }}" class="">
                                    <i class="material-icons"></i>
                                    <span data-i18n="Analytics">Add CMS Page</span>
                                </a>

                            <li>

                        </ul>
                    </li> -->






                {{-- @if(Auth::user()->role_id == 2) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class=" ">
                        <a href="#exampledropdownDropdowncoach" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>Coach Management </a>
                        <ul id="exampledropdownDropdowncoach" class="collapse list-unstyled ">

                            <li class="">
                                <a href="#" class=" ">
                                    <i class="material-icons"></i>
                                    <span> Coaches</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#" class=" ">
                                    <i class="material-icons"></i>
                                    <span> Schedule</span>
                                </a>
                            </li>

                        </ul>
                    </li> -->

                {{-- @endif --}}
                {{-- @if(Auth::user()->role_id == 2) --}}
                    <!-- <li class=" ">
                        <a href="#exampledropdownDropdownclass" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>Class Management </a>
                        <ul id="exampledropdownDropdownclass" class="collapse list-unstyled ">

                            <li class="">
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span> Classes</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#" class=" ">
                                    <i class="material-icons"></i>
                                    <span> Schedule</span>
                                </a>
                            </li>

                        </ul>
                    </li> -->

                {{-- @endif --}}
                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class="">
                        <a href="{{ url('/sports') }}" class="">
                            <i class=""></i>
                            <span>Sports Management</span>
                        </a>
                    </li> -->
                {{-- @endif --}}


                {{-- @if(Auth::user()->role_id == 1) --}}

                    <!-- <li class=" ">
                        <a href="#exampledropdownDropdownchat" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>
                            Chat</a>
                        <ul id="exampledropdownDropdownchat" class="collapse list-unstyled ">
                            <li>
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span>User Chat</span>
                                </a>
                            <li>
                            <li>
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span>GymOwner Chat</span>
                                </a>
                            <li>
                        </ul>
                    </li> -->

                {{-- @endif --}}

                {{-- @if(Auth::user()->role_id == 2) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Chat</span>
                        </a>
                    </li> -->
                {{-- @endif --}}

                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Gym Wise Bookings</span>
                        </a>
                    </li> -->
                {{-- @else --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Bookings Listing</span>
                        </a>
                    </li> -->
                {{-- @endif --}}

                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Gym Wise Reviews</span>
                        </a>
                    </li> -->

                {{-- @else --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Reviews Management</span>
                        </a>
                    </li> -->
                {{-- @endif --}}

                {{-- @if(Auth::user()->role_id == 1) --}}
                    {{--                    for admin --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Earning Management</span>
                        </a>
                    </li> -->
                {{-- @else --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Earning Management</span>
                        </a>
                    </li> -->
                {{-- @endif --}}


                {{-- @if(Auth::user()->role_id == 1) --}}

                    <!-- <li class=" ">
                        <a href="#exampledropdownDropdownpay" aria-expanded="false" data-toggle="collapse">
                            <i class=""></i>
                            Payment</a>
                        <ul id="exampledropdownDropdownpay" class="collapse list-unstyled ">
                            <li class="">
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span>Gym Loyalty</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span>Gym Comission</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#" class="">
                                    <i class="material-icons"></i>
                                    <span>Gym Minimum Fee</span>
                                </a>
                            </li>

                        </ul>
                    </li>






                    <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Statment</span>
                        </a>
                    </li> -->



                {{-- @endif --}}

                {{-- @if(Auth::user()->role_id == 2) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Statement History</span>
                        </a>
                    </li> -->
                {{-- @endif --}}

                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Cms Page</span>
                        </a>
                    </li> -->
                {{-- @endif --}}
                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Faq</span>
                        </a>
                    </li> -->
                {{-- @endif --}}
                {{-- @if(Auth::user()->role_id == 1) --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Notification</span>
                        </a>
                    </li> -->
                {{-- @else --}}
                    <!-- <li class="">
                        <a href="#" class="">
                            <i class="material-icons"></i>
                            <span>Notifications</span>
                        </a>
                    </li> -->

                {{-- @endif --}}
                


            </ul>

        </div>
    </div>
</nav>
