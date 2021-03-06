@extends('layouts.master')

@section('content')
<div style="min-height: 400px" id="tblLoader">
    <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
</div>
<input type="text" style="font-size: 13px" id="companyIdForUpdate" value="{{ $update_customer->id }}" hidden>
<div id="contentDiv" style="display: none">
    <div class="row mt-2 mb-3">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h2 class="_head01">Customer <span>Profile</span></h2>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <ol class="breadcrumb">
                <li><a href="#"><span>Customer </span></a></li>
                <li><span>Profile</span></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <input hidden type="text" id="hidden_cust_id" value="{{ $update_customer->id }}" />
        <div class="col-lg-4 col-12 mb-30">
            <div class="card cp-mh">
                <div class="body">
                    <div class="_cut-img"><img src="" alt="" />
                        <div class="nam-title"></div>
                    </div>
                    <div class="con_info">
                        <p><i class="fa fa-user"></i></p>
                        <p><i class="fa fa-map-marked-alt"></i></p>
                        <p><i class="fa fa-map-marked-alt"></i></p>
                        <p><i class="fa fa-globe"></i></p>
                        <a id="{{ $update_customer->id }}" class="btn-primary float-right edit_profile_btn"
                            style="cursor: pointer; color: white !important">Edit</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-12 mb-30">
            <div class="row">
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-pie"></i> </div>
                        <h5 class="text-muted">Visits</h5>
                        <h3 class="cp-stats-value">{{sizeof($cvrs) + sizeof($svrs)}}</h3>
                    </div>
                </div>
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-bar"></i> </div>
                        <h5 class="text-muted">Sales Reports</h5>
                        <h3 class="cp-stats-value">{{sizeof($cvrs)}}</h3>
                    </div>
                </div>
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-area"></i> </div>
                        <h5 class="text-muted">Service Reports</h5>
                        <h3 class="cp-stats-value">{{sizeof($svrs)}}</h3>
                    </div>
                </div>
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-line"></i> </div>
                        <h5 class="text-muted">Complaints</h5>
                        <h3 class="cp-stats-value">{{sizeof($complains)}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1" data-toggle="tab" href="#tab01" role="tab"
                            aria-controls="tab01" aria-selected="true">CVR List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2" data-toggle="tab" href="#tab02" role="tab" aria-controls="tab02"
                            aria-selected="false">POC List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab3" data-toggle="tab" href="#tab03" role="tab" aria-controls="tab03"
                            aria-selected="false">Service Visit List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab4" data-toggle="tab" href="#tab04" role="tab" aria-controls="tab04"
                            aria-selected="false">Complaints List</a>
                    </li>
                </ul>
                <div class="tab-content tab-style" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab01" role="tabpanel" aria-labelledby="tab1">
                        <table class="table table-hover dt-responsive nowrap" id="example" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Date of Visit</th>
                                    <th>Time Spent</th>
                                    <th>Purpose</th>
                                    <th>Opportunity</th>
                                    <th>Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($cvrs))
                                @foreach ($cvrs as $cvr)
                                <tr>
                                    <td>{{$cvr->report_created_at}}</td>
                                    <td>{{$cvr->report_created_by}}</td>
                                    <td>{{$cvr->date_of_visit}}</td>
                                    <td>{{$cvr->time_spent}}</td>
                                    <td>{{$cvr->purpose_of_visit}}</td>
                                    <td>{{$cvr->opportunity}}</td>
                                    <td>{{$cvr->bussiness_value}}</td>
                                    <td>
                                        <a href="/cvr_preview/{{$cvr->id}}" id="' + element['id'] + '" class="btn btn-default">Preview</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="tab02" role="tabpane2" aria-labelledby="tab2">
                        <table class="table table-hover dt-responsive nowrap data" id="example2" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>POC name</th>
                                    <th>Job Title</th>
                                    <th>phone#</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($pocs))
                                @foreach ($pocs as $poc)
                                <tr>
                                    <td>{{$poc->poc_name}}</td>
                                    <td>{{$poc->job_title}}</td>
                                    <td>{{$poc->bussiness_ph}}</td>
                                    <td>{{$poc->email}}</td>
                                    <td>
                                            <a href="/poc_detail/{{$poc->id}}" id="' + element['id'] + '" class="btn btn-default">Detail</a>
                                        {{-- <a href="/poc_detail/{{$poc->id}}"><button class="btn btn-default" data-toggle="modal"
                                            data-target=".modal-order-detail" title="Edit POC"><i
                                                class="fa fa-list"></i></button></a> --}}
                                        {{-- <button class="btn btn-default" data-toggle="modal"
                                            data-target=".modal-cust-detail" title="Customer Detail"><i
                                                class="fa fa-user-check"></i></button>
                                        <button class="btn btn-default bg-success" title="Confirm Order"><i
                                                class="fa fa-check"></i></button>
                                        <button class="btn btn-default bg-danger" title="Cancel"><i
                                                class="fa fa-times"></i></button> --}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="tab03" role="tabpane3" aria-labelledby="tab3">
                        <table class="table table-hover dt-responsive nowrap data" id="example3" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Date of Visit</th>
                                    <th>Time Spent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($svrs))
                                @foreach ($svrs as $svr)
                                <tr>
                                    <td>{{$svr->report_created_at}}</td>
                                    <td>{{$svr->report_created_by}}</td>
                                    <td>{{$svr->date_of_visit}}</td>
                                    <td>{{$svr->time_spent}}</td>
                                    <td>
                                        <a href="/svr_preview/{{$svr->id}}" id="' + element['id'] + '" class="btn btn-default">Preview</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="tab04" role="tabpane4" aria-labelledby="tab4">
                        <table class="table table-hover dt-responsive nowrap data" id="example4" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($complains))
                                @foreach ($complains as $complain)
                                <tr>
                                    <td>{{$complain->created_at}}</td>
                                    <td>{{$complain->created_by}}</td>
                                    <td>{{($complain->remarks ? $complain->remarks : "NA")}}</td>
                                    <td> <span class="{{($complain->resolved == 0 ? 'lab-pending' : 'lab-line') }}">{{($complain->resolved == 0 ? 'Pending' : 'Resolved') }}</span></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
