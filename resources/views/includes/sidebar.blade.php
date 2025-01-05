<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- END SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false"
            data-auto-scroll="true" data-slide-speed="200">
            <li class="nav-item start">
                <a href="{{ route('dashboard') }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <!--                    <span class="selected"></span>-->
                    <span class="arrow open"></span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link nav-toggle">
                    <i class="glyphicon glyphicon-th"></i>
                    <span class="title">Industries</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('getAllCategories') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Industries</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getAllSubCategories') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getAllProfessions') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Profession</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item"> --}}
                    {{-- <a href="{{ route('dashboard') }}" class="nav-link "> --}}
                    {{-- <i class="icon-layers"></i> --}}
                    {{-- <span class="title"> Freelancer Categories</span> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link nav-toggle">
                    <i class="glyphicon glyphicon-list-alt"></i>
                    <span class="title">Appointments</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--                    <li class="nav-item">
                        <a href="{{ route('newAppointment') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Add New Appointment</span>
                        </a>
                    </li>-->
                    <li class="nav-item">
                        <a href="{{ route('getPendingAppointments') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Pending</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getConfirmedAppointments') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Confirmed</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getCompletedAppointments') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Completed</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getCancelledAppointments') }}" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">Cancelled</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link nav-toggle">
                    <i class="icon-note"></i>
                    <span class="title">Freelancers</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('getAllFreelancers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">All Freelancers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getActiveFreelancers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Active Freelancers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getNotActiveFreelancers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Not Active Freelancers</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('getNotVerfiedFreelancers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Not Verified Freelancers</span>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{ route('getDeletedFreelancers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Deleted Freelancers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('addNewFreelancerForm') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Add New Freelancer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getAllFreelancersWithIban') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Freelancer IBAN Info</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="icon-users"></i>
                    <span class="title">Customers</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('getAllCustomers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">All Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getActiveCustomers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Active Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getBlockedCustomers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Blocked Customers</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="{{ route('getPendingCustomers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Pending Customers</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a href="{{ route('getDeletedCustomers') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Deleted Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('addNewCustomerForm') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Add New Customer</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="icon-users"></i>
                    <span class="title">Promo Codes</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('addPromoCode') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Add New Promo Code</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getActivePromoCodes') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Active Promo Code</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getExpiredPromoCodes') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Expired Promo Code</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sendPromoCodeForm') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Send Promo Code to Client</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="glyphicon glyphicon-th"></i>
                    <span class="title">Posts</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('getReportedPost') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Reported Post</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('getBlockedPosts') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Blocked Post</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="glyphicon glyphicon-th"></i>
                    <span class="title">App Revenue</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('getAppEarning') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">App Earning</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('paymentFreelancerListing') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Payment Transfer</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="glyphicon glyphicon-th"></i>
                    <span class="title">Payouts</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('availabeEarnings',['type' => 'available']) }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Process Payouts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payouts',['type' => 'in_progress']) }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">In Progress Payouts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payouts',['type' => 'completed']) }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Completed Payouts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payouts',['type' => 'failed']) }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Failed Payouts</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('paymentFreelancerListing') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Payment Transfer</span>
                        </a>
                    </li> --}}
                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                    <i class="glyphicon glyphicon-th"></i>
                    <span class="title">Site</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{ route('getAllMessageCodes') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Message Codes</span>
                        </a>
                    </li>
                    <li class="nav-item" style="display: none">
                        <a href="{{ route('CronJobs') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">Cronjob Logs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('SESBounces') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">SES Bounces</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('SESComplaints') }}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">SES Complaints</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('systemSettings') }}" class="nav-link ">
                    <i class="glyphicon glyphicon-cog"></i>
                    <span class="title">System Settings</span>
                </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->
