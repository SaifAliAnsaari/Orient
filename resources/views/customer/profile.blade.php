@extends('layouts.master')

@section('content')
<div style="min-height: 400px" id="tblLoader">
    <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
</div>
<input type="text" id="companyIdForUpdate" value="{{ $update_customer->id }}" hidden>
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
        <input hidden type="text" id="hidden_cust_id" value="{{ $update_customer->id }}"/>
        <div class="col-lg-4 col-12 mb-30">
            <div class="card cp-mh">
                <div class="body">
                    <div class="_cut-img"><img src="" alt="" />
                        <div class="nam-title"></div>
                    </div>
                    <div class="con_info">
                        <p><i class="fa fa-user"></i></p>
                        <p><i class="fa fa-phone-square"></i></p>
                        <p><i class="fa fa-map-marked-alt"></i></p>
                        <p><i class="fa fa-globe"></i></p>
                        <a id="{{ $update_customer->id }}" class="btn-primary float-right edit_profile_btn" style="cursor: pointer; color: white !important">Edit</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-12 mb-30">
            <div class="row">
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-pie"></i> </div>
                        <h5 class="text-muted">Orders</h5>
                        <h3 class="cp-stats-value">36,254</h3>
                        <p class="mb-0"><span class="weight600 text-success"><i class="fa fa-arrow-up"> </i> 5.27%</span> <span class="bm_text"> Since last month</span> </p>
                    </div>
                </div>
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-bar"></i> </div>
                        <h5 class="text-muted">Growth</h5>
                        <h3 class="cp-stats-value">36,254</h3>
                        <p class="mb-0"><span class="weight600 text-danger"><i class="fa fa-arrow-down"> </i> 5.27%</span> <span class="bm_text"> Since last month</span> </p>
                    </div>
                </div>
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-area"></i> </div>
                        <h5 class="text-muted">Revenue</h5>
                        <h3 class="cp-stats-value">36,254</h3>
                        <p class="mb-0"><span class="weight600 text-danger"><i class="fa fa-arrow-down"> </i> 5.27%</span> <span class="bm_text"> Since last month</span> </p>
                    </div>
                </div>
                <div class="col-md-6 mb-30">
                    <div class="card cp-stats">
                        <div class="cp-stats-icon"> <i class="fa fa-chart-line"></i> </div>
                        <h5 class="text-muted">Customers</h5>
                        <h3 class="cp-stats-value">36,254</h3>
                        <p class="mb-0"><span class="weight600 text-success"><i class="fa fa-arrow-up"> </i> 5.27%</span> <span class="bm_text"> Since last month</span> </p>
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
                        <a class="nav-link active" id="tab1" data-toggle="tab" href="#tab01" role="tab" aria-controls="tab01" aria-selected="true">Order List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2" data-toggle="tab" href="#tab02" role="tab" aria-controls="tab02" aria-selected="false">Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab3" data-toggle="tab" href="#tab03" role="tab" aria-controls="tab03" aria-selected="false">Contact</a>
                    </li>
                </ul>
                <div class="tab-content tab-style" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab01" role="tabpanel" aria-labelledby="tab1">
                        <table class="table table-hover dt-responsive nowrap" id="example" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Order#</th>
                                    <th>Date</th>
                                    <th>Customer Name</th>
                                    <th>Deadline</th>
                                    <th>Order Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1566</td>
                                    <td>11/02/2018</td>
                                    <td>Saeed Khan</td>
                                    <td>Deadline here</td>
                                    <td>Rs: 17500</td>
                                    <td>Complete</td>
                                    <td>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-order-detail" title="Order Detail"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-cust-detail" title="Customer Detail"><i class="fa fa-user-check"></i></button>
                                        <button class="btn btn-default bg-success" title="Confirm Order"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-default bg-danger" title="Cancel"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1566</td>
                                    <td>11/02/2018</td>
                                    <td>Saeed Khan</td>
                                    <td>Deadline here</td>
                                    <td>Rs: 17500</td>
                                    <td><span class="text-danger">Pending</span></td>
                                    <td>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-order-detail" title="Order Detail"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-cust-detail" title="Customer Detail"><i class="fa fa-user-check"></i></button>
                                        <button class="btn btn-default bg-success" title="Confirm Order"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-default bg-danger" title="Cancel"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1566</td>
                                    <td>11/02/2018</td>
                                    <td>Saeed Khan</td>
                                    <td>Deadline here</td>
                                    <td>Rs: 17500</td>
                                    <td>Complete</td>
                                    <td>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-order-detail" title="Order Detail"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-cust-detail" title="Customer Detail"><i class="fa fa-user-check"></i></button>
                                        <button class="btn btn-default bg-success" title="Confirm Order"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-default bg-danger" title="Cancel"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1566</td>
                                    <td>11/02/2018</td>
                                    <td>Saeed Khan</td>
                                    <td>Deadline here</td>
                                    <td>Rs: 17500</td>
                                    <td><span class="text-danger">Pending</span></td>
                                    <td>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-order-detail" title="Order Detail"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-cust-detail" title="Customer Detail"><i class="fa fa-user-check"></i></button>
                                        <button class="btn btn-default bg-success" title="Confirm Order"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-default bg-danger" title="Cancel"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1566</td>
                                    <td>11/02/2018</td>
                                    <td>Saeed Khan</td>
                                    <td>Deadline here</td>
                                    <td>Rs: 17500</td>
                                    <td>Complete</td>
                                    <td>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-order-detail" title="Order Detail"><i class="fa fa-list"></i></button>
                                        <button class="btn btn-default" data-toggle="modal" data-target=".modal-cust-detail" title="Customer Detail"><i class="fa fa-user-check"></i></button>
                                        <button class="btn btn-default bg-success" title="Confirm Order"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-default bg-danger" title="Cancel"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="tab02" role="tabpanel" aria-labelledby="tab2">
                        2
                    </div>
                    <div class="tab-pane fade show" id="tab03" role="tabpanel" aria-labelledby="tab3">
                        3
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
