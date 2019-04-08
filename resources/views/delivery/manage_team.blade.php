@extends('layouts.master')


@section('data-sidebar')
<div id="product-cl-sec">
    <a href="#" id="pl-close" class="close-btn-pl"></a>
    <div class="pro-header-text">New <span>Team</span></div>
    <div style="min-height: 400px" id="dataSidebarLoader" style="display: none">
        <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
    </div>
    <div class="pc-cartlist">
        <form style="display: flex; width:100%" id="saveDeliveryForm">
            <input type="text" value="" name="team_id" hidden/>
            {!! Form::hidden('team_updating_id', '') !!}
            {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
            @csrf
            <input type="text" id="operation" hidden>
            <div class="overflow-plist">
                <div class="plist-content">
                    <div class="_left-filter" style="padding-top: 30px">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div id="floating-label" class="card p-20 top_border mb-3">
                                        <h2 class="_head03 PT-10">Team <span> Name</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="form-group">
                                                <label class="control-label mb-10">Team Name*</label>
                                                <input type="text" name="team_name" class="form-control required" placeholder="" required>
                                            </div>
                                        </div>
                                        <h2 class="_head03 PT-10">Vehicle <span> Information</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Vehicle Make & Model*</label>
                                                        <input type="text" name="vehical_make_model" class="form-control required" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Vehicle Type*</label>
                                                        <input type="text" name="vehical_type" class="form-control required" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Vehicle License #*</label>
                                                        <input type="text" name="vehical_license_num" class="form-control required" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Vehicle Capacity for Filled Bottle*</label>
                                                        <input type="number" name="vehical_capicity_filled" class="form-control required" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Vehicle Capacity for Empty Bottles*</label>
                                                        <input type="number" name="vehical_capicity_empty" class="form-control required" placeholder="" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="_head03 PT-10">Team <span> Members</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="form-s2">
                                                <label class="PT-10 font12">Select Team Member*</label>
                                                <div>
                                                    <select class="form-control sd-type required" id = "select_memebr" name="select_memebr" multiple="multiple">
                                                        <option value="0" disabled>Select Team Member</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="font-12 pt-10"> Assign Delivery <span> Team Area*</span></label>
                                        <div class="form-s2">
                                            <div>
                                                <select class="form-control formselect required" name="area_name" placeholder="Select Area">
                                                    <option value="0" disabled selected>Select Area*</option>
                                                    <?php
                                                        if(!$areas->isEmpty()){
                                                            foreach($areas as $area){ ?>
                                                                <option value="<?= $area->id ?>"><?= $area->area_name ?></option>
                                                            <?php }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="_cl-bottom">
        <button type="submit" class="btn btn-primary mr-2" id="saveDeliveryTeam">Save</button>
        <button type="submit" class="btn btn-primary mr-2" id="updateDeliveryTeam" style = "display:none;">Update</button>
        <button id="pl-close" type="submit" class="btn btn-cancel mr-2" id="cancelDeliveryTeam">Cancel</button>
    </div>
</div>
@endsection


@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Delivery Team</h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Delivery Team</span></a></li>
            <li><span>Active</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <a class="btn add_button openDataSidebarForAddingDeliveryTeam"><i class="fa fa-plus"></i> Add Delivery Team</a>
                <h2>Delivery Team List</h2>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div>
                <div class="body" style="display: none">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection