<ul class="sidebar navbar-nav toggled" >
    <?php 
    if(!empty($check_rights)){
        $test_array = array();
        $counter = 0;
        foreach($check_rights as $rights){
            $test_array[$counter] = $rights->access;
            $counter++;
        } ?>
    <li class="nav-item">
        <a class="nav-link" href="/">
            <img src="/images/icon-dash-board.svg" alt="" />
            <span>Dashboard</span>
        </a>
    </li>
    @if(in_array("/register", $test_array) || in_array("/SCFAR", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/icon-organization.svg" alt="" />
                <span>Employee Management</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/register", $test_array))
                    <a class="dropdown-item" href="/register">Employees List</a>
                @endif
                @if(in_array("/SCFAR", $test_array))
                    <a class="dropdown-item" href="/SCFAR">Access Rights</a>
                @endif
                @if(in_array("/pick_up", $test_array))
                    <a class="dropdown-item" href="/pick_up">Pick Up & Delivery Location</a>
                @endif
            </div>
        </li>
    @endif
    @if(in_array("/Customer_list", $test_array) || in_array("/poc_list", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/icon-customer-management.svg" alt="" />
                <span>Customer Management</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/Customer_list", $test_array))
                    <a class="dropdown-item" href="/Customer_list">Customer List</a>
                @endif
                @if(in_array("/poc_list", $test_array))
                    <a class="dropdown-item" href="/poc_list">POC List</a>
                @endif
            </div>
        </li>
    @endif
    @if(in_array("/new_cvr", $test_array) || in_array("/cvr_list", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/report.svg" alt="" />
                <span>Report Management</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/new_cvr", $test_array))
                    <a class="dropdown-item" href="/new_cvr">Add New CVR</a>
                @endif
                @if(in_array("/cvr_list", $test_array))
                    <a class="dropdown-item" href="/cvr_list">CVR List</a>
                @endif
            </div>
        </li>
    @endif
    @if(in_array("/complaints_settings", $test_array) || in_array("/generate_complaints", $test_array) || in_array("/complaints_list", $test_array))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navi-l1" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <img src="/images/comment.svg" alt="" />
                <span>Complaints & Suggestions</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navi-l1">
                @if(in_array("/complaints_settings", $test_array))
                    <a class="dropdown-item" href="/complaints_settings">Complaints Settings</a>
                @endif
                @if(in_array("/generate_complaints", $test_array))
                    <a class="dropdown-item" href="/generate_complaints">Complaints Generate</a>
                @endif
                @if(in_array("/complaints_list", $test_array))
                    <a class="dropdown-item" href="/complaints_list">New Complains</a>
                @endif
                @if(in_array("/resolved_complains", $test_array))
                    <a class="dropdown-item" href="/resolved_complains">Resolved Complains</a>
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
    @if(in_array("/notification_prefrences", $test_array))
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
    @endif
    <?php }
    ?>
</ul>
