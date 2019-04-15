@extends('layouts.master')

@section('data-sidebar')

    
<div id="product-cl-sec">
    <a href="" id="pl-close" class="close-btn-pl close_customer_form"></a>
    <div class="pro-header-text">New <span>POC</span></div>
    <div style="min-height: 400px; display: none" id="dataSidebarLoader">
        <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
    </div>
    <div class="pc-cartlist">
        <form style="display: flex;" id="savePOCForm">
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
                                        <h2 class="_head03">POC <span>Details</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">POC Name*</label>
                                                        <input type="text" style="font-size: 13px" name="poc_name" class="form-control required" placeholder="">
                                                    </div>
                                                </div> 
                                                <div class="col-md-6 form-s2 pt-19">
                                                    
                                                        <div>
                                                            <select class="form-control formselect" name="company_name" placeholder="Select Customer Type">
                                                                <option value="0" selected disabled>Select Company Name*</option>
                                                                @if(!empty($cust))
                                                                    @foreach ($cust as $customers)
                                                                        <option value="{{$customers->id}}">{{$customers->company_name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    {{-- <div class="form-group">
                                                        <label class="control-label mb-10">Company Name*</label>
                                                        <input type="text" name="company_name" class="form-control required" placeholder="">
                                                    </div> --}}
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Job Title*</label>
                                                        <input type="text" style="font-size: 13px" name="jobTitle" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Business Phone*</label>
                                                        <input type="number" style="font-size: 13px" name="businessPH" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Email ID*</label>
                                                        <input type="email" style="font-size: 13px" name="email" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Address*</label>
                                                        <input type="text" name="address" maxlength="13" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">City*</label>
                                                        <input type="text" name="city" class="form-control required"  placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">State/Province*</label>
                                                        <input type="text" name="state"  class="form-control required" placeholder="">
                                                    </div>
                                                </div> --}}
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
        <button type="submit" class="btn btn-primary mr-2" id="savePOC">Save</button>
        <button id="pl-close" type="submit" class="btn btn-cancel mr-2" id="cancelPOC">Cancel</button>
    </div>
</div>
@endsection

@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">POC</h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>POC</span></a></li>
            <li><span>Active</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <a class="btn add_button openDataSidebarForAddingPOC"><i class="fa fa-plus"></i> Add POC</a>
                <h2>POC List</h2>
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
