@extends('layouts.master')

@section('data-sidebar')

    
<div id="product-cl-sec">
    <a href="" id="pl-close" class="close-btn-pl close_customer_form"></a>
    <div class="pro-header-text">New <span>Customer</span></div>
    <div style="min-height: 400px" id="dataSidebarLoader" style="display: none">
        <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
    </div>
    <div class="pc-cartlist">
        <form style="display: flex;" id="saveCustomerForm">
            {!! Form::hidden('product_updating_id', '') !!}
            {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
            @csrf
            <input type="text" id="operation" hidden>
            <div class="overflow-plist">
                <div class="plist-content">
                    <div class="_left-filter">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div id="floating-label" class="card p-20 top_border mb-3">
                                        <h2 class="_head03">Company <span>Details</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Company Name*</label>
                                                        <input type="text" name="compName" style="font-size: 13px" class="form-control required">
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-12">
                                                    <div>
                                                        <select class="form-control formselect" name="parent_company" placeholder="Select Customer Type">
                                                            <option value="0" selected disabled>Select Parent Company</option>
                                                            @if(!empty($parent_comp))
                                                                @foreach ($parent_comp as $comp)
                                                                    <option value="{{$comp->id}}">{{$comp->company_name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-12 pt-10">
                                                    <div>
                                                        <select class="form-control formselect required" name="industry" placeholder="Select Zone*" required>
                                                            <option value="0" disabled selected>Select Industry*</option>
                                                            <option value="1">Refinery</option>
                                                            <option value="2">Fertilizer</option>
                                                            <option value="3">Oil & Gas</option>
                                                            <option value="4">Textile</option>
                                                            <option value="5">Captive Power Plant</option>
                                                            <option value="6">Independent Power Plant</option>
                                                            <option value="7">Pharmaceutical</option>
                                                            <option value="8">Chemical</option>
                                                            <option value="9">Paper</option>
                                                            <option value="10">Sugar</option>
                                                            <option value="11">Engineering</option>
                                                            <option value="12">Manufacturing</option>
                                                            <option value="13">C&I</option>
                                                            <option value="14">Consultant</option>
                                                            <option value="15">Contractor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="_head03 PT-20">Contact <span>Details</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">POC*</label>
                                                        <input type="text" name="poc" style="font-size: 13px" class="form-control required" placeholder="">
                                                    </div>
                                                </div> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Job Title*</label>
                                                        <input type="text" style="font-size: 13px" name="jobTitle" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Bussiness Phone*</label>
                                                        <input type="number" style="font-size: 13px" name="bussinessPH" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Email Address*</label>
                                                        <input type="email" style="font-size: 13px" name="email" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Address*</label>
                                                        <input type="text" style="font-size: 13px" name="address" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">City*</label>
                                                        <input type="text" style="font-size: 13px" name="city" maxlength="13" value="Karachi" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Province*</label>
                                                        <input type="text" style="font-size: 13px" name="state" class="form-control required" value="Sindh" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Country*</label>
                                                        <input type="text" style="font-size: 13px" name="country" value="Pakistan" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Web Page Address*</label>
                                                        <input type="text" style="font-size: 13px" name="web_address" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="PT-10 font12">Remarks*</label>
                                                    <div class="form-group mb-0">							 
                                                    <textarea name="description" class="required" rows="8" id="description" style="font-size:13px"></textarea>
                                                    </div>
                                                </div>
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
        {{-- data-toggle="modal" data-target=".bd-example-modal-lg" --}}
        <button  class="btn btn-default red-bg" data-toggle="modal" data-target="#exampleModal" id="delete_customer_modal" hidden>Delete</button>
        <button type="submit" class="btn btn-primary mr-2" id="saveCustomer">Save</button>
        <button id="pl-close" type="submit" class="btn btn-cancel mr-2" id="cancelCustomer">Cancel</button>
    </div>
</div>
@endsection

@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Customers</h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Customers</span></a></li>
            <li><span>Active</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <a class="btn add_button openDataSidebarForAddingCustomer"><i class="fa fa-plus"></i> Add Customer</a>
                <h2>Customers List</h2>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="body" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection
