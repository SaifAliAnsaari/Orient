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
                    <a href='/cvr_preview/{{$notif->cvr_id}}'><div class="alert alert-warning alert-dismissible fade show alert-color _NF-se" role="alert">
                            <img src="{{'/images/profile-img--.jpg'}}" class="NU-img float-none mb-0" alt="">
                            <strong class="notifications_list_all" id="{{$notif->id}}">({{ $notif->approval_by_name }}) </strong> {{ "CVR (".$notif->cvr_id.") has been ". ($notif->approval == 1 ? "approved" : "disapproved") .". Reamrks: " .$notif->remarks }}
                        </div>
                    </a>
                    @endforeach
                @endif
                @if(!empty($all_notif))
                    @foreach($all_notif as $notifications)
                    <a href="{{ $notifications->cvr_id ? '/cvr_preview'.'/'.$notifications->cvr_id : '/CustomerProfile'.'/'.$notifications->customer_id }}"> <div class="alert alert-warning alert-dismissible fade show alert-color _NF-se" role="alert">
                           <img src="{{'/images/profile-img--.jpg'}}" class="NU-img float-none mb-0" alt="">
                            <strong class="notifications_list_all" id="{{$notifications->id}}">({{$notifications->customer_nameOrCvr}}) </strong> {{ $notifications->message }}
                           
                        </div></a>
                    @endforeach
                @endif


            </div>

        </div>

    </div>


</div>

@endsection
