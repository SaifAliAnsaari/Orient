<ul class="sidebar navbar-nav toggled" >
    <?php 
    if(!empty($check_rights)){
        $test_array = array();
        $counter = 0;
        foreach($check_rights as $rights){
            $test_array[$counter] = $rights->access;
            $counter++;
        } 
       // dd($test_array); die;
        ?>
    <li class="nav-item">
        <a class="nav-link" href="/">
            <img src="/images/icon-dash-board.svg" alt="" />
            <span>Dashboard</span>
        </a>
    </li>

    

    @if(in_array("/register", $test_array) || in_array("/SCFAR", $test_array) || in_array("/cities", $test_array) || in_array("/designations", $test_array) || in_array("/industries", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/icon-organization.svg" alt="" />
                <span>Employees</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/register", $test_array))
                    <a class="dropdown-item" href="/register">Employees List</a>
                @endif
                @if(in_array("/SCFAR", $test_array))
                    <a class="dropdown-item" href="/SCFAR">Access Rights</a>
                @endif
                @if(in_array("/cities", $test_array))
                    <a class="dropdown-item" href="/cities">Cities</a>
                @endif
                @if(in_array("/designations", $test_array))
                    <a class="dropdown-item" href="/designations">Designations</a>
                @endif
                @if(in_array("/industries", $test_array))
                    <a class="dropdown-item" href="/industries">Industry</a>
                @endif
                {{-- @if(in_array("/notification_prefrences", $test_array))
                    <a class="dropdown-item" href="/notification_prefrences">Notification Prefrences</a>
                @endif --}}
            </div>
        </li>
    @endif
    @if(in_array("/Customer_list", $test_array) || in_array("/poc_list", $test_array) || in_array("/delete_customer", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/icon-customer-management.svg" alt="" />
                <span>Customers</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/Customer_list", $test_array))
                    <a class="dropdown-item" href="/Customer_list">Customer List</a>
                @endif
                @if(in_array("/poc_list", $test_array))
                    <a class="dropdown-item" href="/poc_list">POC List</a>
                @endif
                @if(in_array("/delete_customer", $test_array))
                    <a class="dropdown-item" href="/delete_customer">Delete Customer</a>
                @endif
            </div>
        </li>
    @endif
    @if(in_array("/main_category", $test_array) || in_array("/sub_category", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/categ-icon.svg" alt="" />
                <span>Categories</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/main_category", $test_array))
                    <a class="dropdown-item" href="/main_category">Main Category</a>
                @endif
                @if(in_array("/sub_category", $test_array))
                    <a class="dropdown-item" href="/sub_category">Sub Category</a>
                @endif
            </div>
        </li>
    @endif
    @if(in_array("/new_cvr", $test_array) || in_array("/cvr_list", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/report.svg" alt="" />
                <span>Sales Report</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/new_cvr", $test_array))
                    <a class="dropdown-item" href="/new_cvr">Add New CVR</a>
                @endif
                @if(in_array("/cvr_list", $test_array))
                    <a class="dropdown-item" href="/cvr_list/all_cvr">CVR List</a>
                @endif
                @if(in_array("/cvr_list", $test_array))
                    <a class="dropdown-item" href="/cvr_list/approved">Approved CVR List</a>
                @endif
                @if(in_array("/cvr_list", $test_array))
                    <a class="dropdown-item" href="/cvr_list/disapproved">Disapproved CVR List</a>
                @endif
                @if(in_array("/cvr_list", $test_array))
                    <a class="dropdown-item" href="/cvr_list/pending">Pending CVR List</a>
                @endif
            </div>
        </li>
    @endif
    @if(in_array("/new_svr", $test_array) || in_array("/svr_list", $test_array))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <img src="/images/sale-report.svg" alt="" />
            <span>Service Report</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navi-l1">
            @if(in_array("/new_svr", $test_array))
                <a class="dropdown-item" href="/new_svr">New Service Report</a>
            @endif
            @if(in_array("/svr_list", $test_array))
                <a class="dropdown-item" href="/svr_list/all_svr">Service Reports List</a>
            @endif
            @if(in_array("/svr_list", $test_array))
                <a class="dropdown-item" href="/svr_list/approved">Approved Service Reports List</a>
            @endif
            @if(in_array("/svr_list", $test_array))
                <a class="dropdown-item" href="/svr_list/disapproved">Disapproved Service Reports List</a>
            @endif
            @if(in_array("/svr_list", $test_array))
                <a class="dropdown-item" href="/svr_list/pending">Pending Service Reports List</a>
            @endif
        </div>
    </li>
@endif
    @if(in_array("/complaints_settings", $test_array) || in_array("/generate_complaints", $test_array) || in_array("/complaints_list", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/comment.svg" alt="" />
                <span>Complaints</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/generate_complaints", $test_array))
                    <a class="dropdown-item" href="/generate_complaints">Generate Complaint</a>
                @endif
                @if(in_array("/complaints_list", $test_array))
                    <a class="dropdown-item" href="/complaints_list">Pending Complaints</a>
                @endif
                @if(in_array("/resolved_complains", $test_array))
                    <a class="dropdown-item" href="/resolved_complains">Resolved Complaints</a>
                @endif
                @if(in_array("/complaints_settings", $test_array))
                    <a class="dropdown-item" href="/complaints_settings">Settings</a>
                @endif
            </div>
        </li>
    @endif
    {{-- @if(in_array("/notification_prefrences", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/notifications.svg" alt="" />
                <span>Notification Center</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/notification_prefrences", $test_array))
                    <a class="dropdown-item" href="/notification_prefrences">Notification Prefrences</a>
                @endif
            </div>
        </li>
    @endif --}}
    <?php }
    ?>
</ul>
