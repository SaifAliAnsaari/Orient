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
                    href="{{ ($notifications->cvr_id ? '/edit_cvr'.'/'.$notifications->cvr_id : ($notifications->customer_id ? '/CustomerProfile'.'/'.$notifications->customer_id : ($notifications->svr_id ? '/edit_svr'.'/'.$notifications->svr_id : '/complaints_list'))) }}">
                    <div class="alert alert-warning alert-dismissible fade show alert-color _NF-se" role="alert">
                        <img src="{{'/images/profile-img--.jpg'}}" class="NU-img float-none mb-0" alt="">
                        <strong class="notifications_list_all"
                            id="{{$notifications->id}}"> </strong><strong>{{$notifications->customer_nameOrCvr}}</strong> 
                            <?php 
                            if($notifications->message == "New Customer Added"){
                                echo " Created a ";
                            }else if($notifications->message == "New CVR added"){
                                echo " Created a ";
                            }else if($notifications->message == "New SVR added"){
                                echo " Created a ";
                            }else{
                                echo " Update a ";
                            }
                            ?>
                             {{ ($notifications->cvr_id ? "Customer Visit Report" : ($notifications->svr_id ? "Service Visit Report" : "Customer")) }} {{ ($notifications->notif_for ? " For ".$notifications->notif_for : "") }}  
                        <div class="row" style="margin-left:5%;">{{$notifications->created_at}}</div>

                    </div>
                    
                </a>
                @endforeach
                @endif


            </div>

        </div>

    </div>


</div>

@endsection
