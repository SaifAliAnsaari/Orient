@extends('layouts.master')
@section('content')

<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">All <span> Notification</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Notification</span></a></li>
            <li><span>List</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="body">
                @if(!empty($unread_notif))
                @foreach($unread_notif as $notif)
                <a href='/cvr_preview/{{$notif->cvr_id}}'>
                    <div class="alert alert-warning alert-dismissible fade show alert-color _NF-se" role="alert">
                        <img src="{{'/images/profile-img--.jpg'}}" class="NU-img float-none mb-0" alt="">
                        <strong class="notifications_list_all" id="{{$notif->id}}">({{ $notif->approval_by_name }})
                        </strong>
                        {{ "CVR (".$notif->cvr_id.") has been ". ($notif->approval == 1 ? "approved" : "disapproved") .". Reamrks: " .$notif->remarks }}
                    </div>
                </a>
                @endforeach
                @endif
                @if(!empty($all_notif))
                @foreach($all_notif as $notifications)
                <a
                    href="{{ ($notifications->message == 'CVR has been disapproved' ? '/disapproved_detail'.'/'.$notifications->cvr_id  : ($notifications->message == 'SVR has been disapproved' ? '/disapproved_svr_detail'.'/'.$notifications->svr_id : ($notifications->cvr_id ? '/cvr_preview'.'/'.$notifications->cvr_id : ($notifications->customer_id ? '/CustomerProfile'.'/'.$notifications->customer_id : ($notifications->svr_id ? '/svr_preview'.'/'.$notifications->svr_id : '/complaints_list'))))) }}">
                    {{-- src="{{($notifications->cvr_id ? '/images/CVR-icon.svg' : ($notifications->svr_id ? '/images/SVR-icon.svg' : ($notifications->customer_id ? '/images/customer-icon.svg' : '/images/complaint-icon.svg')))}}" --}}
                    <div class="alert alert-warning alert-dismissible fade show alert-color _NF-se" role="alert">
                        <img src="{{($notifications->message == 'New CVR added' ? '/images/CVR-icon.svg' : ($notifications->message == 'CVR Updated' ? '/images/CVR-icon.svg' : ($notifications->message == 'New SVR added' ? '/images/SVR-icon.svg' : ($notifications->message == 'SVR Updated' ? '/images/SVR-icon.svg' : ($notifications->message == 'New Customer Added' ? '/images/customer-icon.svg' : ($notifications->message == 'Customer Updated' ? '/images/customer-icon.svg' : ($notifications->message == 'New Complain Added' ? '/images/complaint-icon.svg' : ($notifications->message == 'Complain Resolved' ? '/images/complaint-resolve.svg' : ($notifications->message == 'CVR has been approved' ? '/images/svr-cvr-approve.svg' : ($notifications->message == 'SVR has been approved' ? '/images/svr-cvr-approve.svg' : '/images/svr-cvr-disapprove.svg'))))))))))}}" class="NU-img float-none mb-0" alt="">
                        <strong class="notifications_list_all"
                            id="{{$notifications->id}}"> </strong><strong>{{($notifications->message == 'CVR has been disapproved' || $notifications->message == 'SVR has been disapproved' || $notifications->message == 'CVR has been approved' || $notifications->message == 'SVR has been approved' ? '' : ($notifications->customer_nameOrCvr ? $notifications->customer_nameOrCvr : '--'))}}</strong> 
                            <?php 
                            if($notifications->message == "New Customer Added"){
                                echo " Created a ";
                            }else if($notifications->message == "New CVR added"){
                                echo " Created a ";
                            }else if($notifications->message == "New Complain Added"){
                                echo " Created a ";
                            }else if($notifications->message == "Complain Resolved"){
                                echo " has Ressolved this ";
                            }else if($notifications->message == "New SVR added"){
                                echo " Created a ";
                            }else if($notifications->message == "CVR has been disapproved" || $notifications->message == 'SVR has been disapproved'){
                                echo "Disapproved ";
                            }else if($notifications->message == 'CVR has been approved' || $notifications->message == 'SVR has been approved'){
                                echo "Approved ";
                            }else{
                                echo " Updated a ";
                            }
                            ?>
                             {{ ($notifications->cvr_id ? "Customer Visit Report" : ($notifications->svr_id ? "Service Visit Report" : ($notifications->customer_id ? 'Customer' : 'Complain'))) }} {{ ($notifications->notif_for ? " For ".$notifications->notif_for : "") }}  
                        <div class="row" style="margin-left:5%;">{{$notifications->created_at}}</div>

                    </div>
                    
                </a>
                @endforeach
                @endif
                @if(!sizeof($all_notif) && !sizeof($unread_notif))
                        <span style="display: block; width:100%; text-align:center; font-weight: normal; font-size: 12pt;
                        color: black;
                        padding: 14px;">No notifications available</span>
                    @endif


            </div>

        </div>

    </div>


</div>

@endsection
