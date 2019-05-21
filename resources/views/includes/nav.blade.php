<nav class="navbar navbar-expand  static-top">
    <a class="hamburger" href="#" id="sidebarToggle"><i class="fas fa-bars"></i></a>
    <a class="_logo" href="/"><img src="/images/orichem.jpeg" alt="" /></a>
    <ul class="navbar-nav ml-auto top_nav">
            <li class="nav-item TM_icon dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="Qlinks" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ URL::to('/images/q-link-icon.svg') }}" alt=""/></a>
                    <div class="dropdown-menu dropdown-menu-right Qlinks" aria-labelledby="Qlinks">
                    <h4 class="notiF-title">Quick Actions</h4>
                    <?php 
                    if(!empty($check_rights)){
                        $test_array = array();
                        $counter = 0;
                        foreach($check_rights as $rights){
                            $test_array[$counter] = $rights->access;
                            $counter++;
                        } ?>
                        @if(in_array("/new_cvr", $test_array))
                        <a href="/new_cvr"><img src="{{ URL::to('/images/cr-report-new.svg') }}" alt=""> Add New CVR</a>
                        @endif
                        @if(in_array("/Customer_list", $test_array))
                        <a href="/Customer_list"><img src="{{ URL::to('/images/customer-list.svg') }}" alt=""> Add Customer</a>
                        @endif
                        @if(in_array("/register", $test_array))
                        <a href="/register"><img src="{{ URL::to('/images/add-emp.svg') }}" alt=""> Add Employee</a>
                        @endif
                    <?php } ?>
                    </div>
                </li>


        @csrf
            <li class="nav-item TM_icon dropdown no-arrow"> 
                <a class="nav-link dropdown-toggle" href="#" id="NotiFications" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="badge">
                    @if(!empty($notifications_counts) && !empty($approval_notif))
                        {{ $notifications_counts->counts + sizeof($approval_notif) }}
                    @endif
                    @if(!empty($approval_notif) && empty($notifications_counts))
                        {{ sizeof($approval_notif) }}
                    @endif
                    @if(empty($approval_notif) && !empty($notifications_counts))
                        {{ $notifications_counts->counts }}
                    @endif
                    
                </span> <img src="{{ URL::to('/images/bell-icon.svg') }}" alt=""/></a>
                <div class="dropdown-menu dropdown-menu-right notiF" aria-labelledby="NotiFications">
                <h4 class="notiF-title">Notification </h4>
                    @if(!empty($notif_data))
                        @foreach($notif_data as $notifications)
                        <a href="{{ ($notifications->cvr_id ? '/cvr_preview'.'/'.$notifications->cvr_id : ($notifications->customer_id ? '/CustomerProfile'.'/'.$notifications->customer_id : ($notifications->svr_id ? '/edit_svr'.'/'.$notifications->svr_id : '/complaints_list'))) }}"><img src="{{($notifications->message == 'New CVR added' ? '/images/CVR-icon.svg' : ($notifications->message == 'CVR Updated' ? '/images/CVR-icon.svg' : ($notifications->message == 'New SVR added' ? '/images/SVR-icon.svg' : ($notifications->message == 'SVR Updated' ? '/images/SVR-icon.svg' : ($notifications->message == 'New Customer Added' ? '/images/customer-icon.svg' : ($notifications->message == 'Customer Updated' ? '/images/customer-icon.svg' : ($notifications->message == 'New Complain Added' ? '/images/complaint-icon.svg' : ($notifications->message == 'Complain Resolved' ? '/images/complaint-resolve.svg' : ($notifications->message == 'CVR has been approved' ? '/images/svr-cvr-approve.svg' : ($notifications->message == 'SVR has been approved' ? '/images/svr-cvr-approve.svg' : '/images/svr-cvr-disapprove.svg'))))))))))}} " class="NU-img" alt=""><strong class="notifications_list" id="{{$notifications->id}}">{{$notifications->message}} </strong><p>
                            <?php 
                                    $datetime1 = new DateTime(date('Y-m-d H:i:s'));//start time
                                    $datetime2 = new DateTime($notifications->created_at);//end time
                                    $interval = $datetime1->diff($datetime2);
                                    echo $interval->format('%d days %H hours %i minutes %s seconds ago');
                                ?>
                        </p></a>
                        @endforeach     
                    @endif
                    @if(!empty($approval_notif))
                        @foreach($approval_notif as $notifications)
                        <a href='/cvr_preview/{{$notifications->cvr_id}}'><img src="{{'/images/profile-img--.jpg'}} " class="NU-img" alt=""><strong class="notifications_list" id="{{$notifications->id}}">{{'CVR ('.$notifications->cvr_id.') has been '. ($notifications->approval == 1 ? "approved" : "disapproved") }} </strong><p>
                            <?php 
                                $datetime1 = new DateTime(date('Y-m-d H:i:s'));//start time
                                $datetime2 = new DateTime($notifications->created_at);//end time
                                $interval = $datetime1->diff($datetime2);
                                echo $interval->format('%d days %H hours %i minutes %s seconds ago');
                                ?>
                        </p></a>
                        @endforeach     
                    @endif
                    <a href="/notifications" class="all-NF">View All ( {{ sizeof($all_notif) + sizeof($unread_notif) }} )</a>
                </div> 
            </li>


        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ Auth::user()->picture ? URL::to(Auth::user()->picture) : '/images/avatar.svg' }}" class="user_log" alt="" />
                <span class="uname">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <span class="dropdown-item usernamelab">{{ Auth::user()->name }}</span>
                <a class="dropdown-item" href="/edit_profile/{{ Auth::user()->id }}"><i class="fa fa-user"> </i> Profile</a>
                <a class="dropdown-item" href="#"><i class="fa fa-cogs"> </i> Setting</a>
                <a class="dropdown-item" href="/logout"><i class="fa fa-power-off"> </i> Logout</a>
            </div>
        </li>
    </ul>
</nav>