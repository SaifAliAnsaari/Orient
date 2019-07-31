@extends('layouts.master')
@section('data-sidebar')


<div id="product-cl-sec"> <a href="#" id="pl-close" class="close-btn-pl"></a>
    <div class="pro-header-text ml-0">Add <span>Customer</span></div>
    <div class="pc-cartlist">
        <form style="display: flex;" id="saveCustomerForm_CVR">
            {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
            @csrf
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
                                                        <input type="text" name="compName" style="font-size: 13px"
                                                            class="form-control required">
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-12">
                                                    <div class="ADD_Select">
                                                        <div class="form-s2 selpluse">
                                                            <div>
                                                                <select class="form-control formselect" id="parent_company" name="parent_company" placeholder="Select Customer Type">
                                                                    <option value="0" selected disabled>Select Parent Company</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <a href="#" class="btn plus_button po-ab"
                                                            data-toggle="modal" data-target=".competition-lg-company"><i
                                                                class="fa fa-plus"></i></a>
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-12 pt-10">
                                                    <div>
                                                        <select class="form-control formselect required" name="industry"
                                                            placeholder="Select Zone*" required>
                                                            <option value="0" disabled selected>Select Industry*
                                                            </option>
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
                                                            <option value="16">Food & Beverages</option>
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
                                                        <input type="text" name="poc" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Job Title*</label>
                                                        <input type="text" name="jobTitle" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Bussiness Phone*</label>
                                                        <input type="number" name="bussinessPH" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Email Address*</label>
                                                        <input type="email" name="email" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Address*</label>
                                                        <input type="text" name="address" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-6" style="margin-top:10px; margin-bottom:10px;">
                                                    <div>
                                                        <input hidden type="text" value="{{$data}}" id="full_cities_array"/>
                                                        <select class="form-control formselect" id="select_city" name="city" placeholder="Select City">
                                                            <option value="0" selected disabled>Select City</option>
                                                            @if(!empty($data))
                                                                @foreach ($data as $city)
                                                                    <option name="{{$city->province}}" value="{{$city->id}}">{{$city->city_name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-6" style="margin-top:10px; margin-bottom:10px;">
                                                    <div>
                                                        <select class="form-control formselect" id="select_province" name="province" placeholder="Select Province">
                                                            <option value="0" selected disabled>Select Province</option>
                                                            @if(!empty($provinces))
                                                                @foreach ($provinces as $province)
                                                                    <option value="{{$province['province']}}">{{$province['province']}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Country*</label>
                                                        <input type="text" name="country" value="Pakistan"
                                                            class="form-control required" style="font-size: 13px"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Web Page Address</label>
                                                        <input type="text" name="web_address" style="font-size: 13px"
                                                            class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="PT-10 font12">Remarks</label>
                                                    <div class="form-group mb-0">
                                                        <textarea name="description" style="font-size: 13px" class=""
                                                            rows="8" id="description" style="font-size:13px"></textarea>
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
        <button type="submit" class="btn btn-primary mr-2 saveCustomer_CVR">Save</button>
        <button id="pl-close" type="submit" class="btn btn-cancel mr-2">Cancel</button>
    </div>
</div>


{{-- Modal --}}
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content top_border">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">POC <span> Details</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-wrap p-0">

                    <div id="floating-label" class="pl-15 pr-15">
                        <div class="form-wrap p-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">POC Name*</label>
                                        <input type="text" id="poc_name_modal" style="font-size: 13px"
                                            class="form-control required_modal" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Job Title*</label>
                                        <input type="text" id="jobTitle_modal" style="font-size: 13px"
                                            class="form-control required_modal" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Business Phone*</label>
                                        <input type="number" style="font-size: 13px" id="businessPH_modal"
                                            class="form-control required_modal" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Email ID*</label>
                                        <input type="email" style="font-size: 13px" id="email_modal"
                                            class="form-control required_modal" placeholder="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="modal-footer  border-0">
                        <button type="submit" class="btn btn-cancel cancel_modal" data-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="button" class="btn btn-primary add_poc_modal">Add</button>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


{{-- Competition Modal --}}
<div class="modal fade competition-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content top_border">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Competition <span> Details</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-wrap p-0">

                    <div id="floating-label" class="pl-15 pr-15">
                        <div class="form-wrap p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Competition Name</label>
                                        <input type="text" id="competition_name"
                                            class="form-control required_competition" style="font-size:13px">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="modal-footer  border-0">
                        <button type="submit" class="btn btn-cancel cancel_competition_modal" data-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="button" class="btn btn-primary add_competition_modal">Add</button>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


{{-- Parent Company Modal --}}
<div class="modal fade competition-lg-company" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content top_border">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Parent Company <span> Details</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-wrap p-0">

                    <div id="floating-label" class="pl-15 pr-15">
                        <div class="form-wrap p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Company Name</label>
                                        <input type="text" id="company_name"
                                            class="form-control required_company" style="font-size:13px">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="modal-footer  border-0">
                        <button type="submit" class="btn btn-cancel cancel_company_modal" data-dismiss="modal"
                            aria-label="Close">Close</button>
                        <button type="button" class="btn btn-primary add_company_modal">Add</button>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


{{-- Confirmation Modal --}}
<div class="modal fade db-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 650px">
        <div class="modal-content top_border">
            <div class="modal-header" style="text-align: center; display: block">
                <h5 class="modal-title" id="exampleModalLongTitle">SVR <span>has been successfully added</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="check_mark">
                    <div class="sa-icon sa-success animate">
                        <span class="sa-line sa-tip animateSuccessTip"></span>
                        <span class="sa-line sa-long animateSuccessLong"></span>
                        <div class="sa-placeholder"></div>
                        <div class="sa-fix"></div>
                    </div>
                </div>

                <div class="form-wrap p-0">
                    <h1 class="_head05" align="center"><span>Do you want to add </span> another SVR or Redirect to Home?</h1>

                    <div class="PT-15 PB-10" align="center">
                        <a href="/"><button type="submit"
                                class="btn btn-primary font13 m-0 mr-2 mb-2">Rediect To Home</button></a>
                        <button type="button" class="btn btn-primary font13 m-0 mb-2" data-dismiss="modal">Add Another
                            SVR</button>
                        <!--<button type="submit" class="btn btn-cancel m-0 mb-2" data-dismiss="modal" aria-label="Close">No</button> -->
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>



<div class="open_confirmation_modal" data-toggle="modal" data-target=".db-confirmation-modal"></div>






<div id="wrapper">

    <form id="saveSVRForm">
        {!! Form::hidden('product_updating_id', '') !!}
        {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
        @csrf
        <input hidden type="text" name="hidden_poc_list" value="0" id="hidden_poc_list" />
        <input hidden type="text" name="hidden_competition_list" id="hidden_competition_list" />
        <div id="">

            <div class="row mt-2 mb-3">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h2 class="_head01">Service <span>VISIT REPORT</span></h2>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <ol class="breadcrumb">
                        <li><a href="#"><span>Service Visit Report</span></a></li>
                        <li><span>Add Report</span></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header bg-color">

                            <div class="form-wrap _w90 p-0">
                                <div class="row">
                                    <div class="col-md-6 top-date"><strong>Date Of Report: </strong>{{ date('Y-m-d') }}
                                    </div>
                                    <div class="col-md-6 top-date rep-name"><strong>Report Prepared By: </strong>{{
                                        Auth::user()->name }}</div>
                                </div>
                            </div>

                        </div>

                        <div class="body">

                            <div id="floating-label">
                                <div class="form-wrap _w90 pt-0">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="font12">Date of Visit</label>
                                            <div class="position-relative">
                                                <input type="text" name="datepicker" id="datepicker"
                                                    style="font-size: 13px" class="form-control required_core"
                                                    placeholder="" value="Select Date" style="font-size:13px">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="font12">Customer Visited</label>
                                            <div class="position-relative">
                                                <div class="form-s2 selpluse">
                                                    <div>
                                                        <select class="form-control formselect required_core"
                                                            name="cvr_customers_list" id="cvr_customers_list"
                                                            placeholder="Select">

                                                        </select>
                                                    </div>
                                                </div>
                                                <a class="btn plus_button po-ab productlist01 add_new_cust"><i
                                                        class="fa fa-plus"></i></a>
                                            </div>
                                        </div>



                                    </div>

                                    <div class="row xsm-p-10">

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label mb-10">Location*</label>
                                                <input type="text" id="location" name="location" style="font-size: 13px"
                                                    class="form-control required_core" style="font-size:13px">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label mb-10">Time Spent*</label>
                                                <input type="text" id="time_spent" name="time_spent"
                                                    style="font-size: 13px" class="form-control required_core"
                                                    style="font-size:13px">
                                            </div>
                                        </div>

                                        {{-- <a id="open_poc_modal" data-toggle="modal"
                                                            data-target=".db-confirmation-modal">Test</a> --}}

                                        <div class="col-md-12 PT-20">
                                            <h3 class="_head03">POC Name

                                                {{-- <button type="button" class="btn btn-primary add-poc"
                                                    data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i>
                                                    Add POC</button> --}}
                                            </h3>
                                        </div>
                                        <div class="col-md-12 mb-20">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class=" position-relative">
                                                        <div class="form-s2 selpluse">
                                                            <div>
                                                                <select class="form-control formselect cvr_poc_list"
                                                                    placeholder="Select">

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <a class="btn plus_button po-ab productlist01 add_another_poc"
                                                            data-toggle="modal" data-target=".bd-example-modal-lg"><i
                                                                class="fa fa-plus"></i></a>

                                                    </div>
                                                </div>
                                                <div class="col-md-8 poc_show_list">

                                                    {{-- <div class="alert fade show alert-color _add-secon" role="alert">
                                                        Fahad Ali
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>--}}

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 PB-10">
                                            <h3 class="_head03">Purpose of Visit</h3>
                                        </div>

                                        <div class="col-md-12">
                                            <input type="text" name="purpose_hidden_array" id="purpose_hidden_array"
                                                hidden />

                                            <div class="row _checkbox-padd mb-15">

                                                <div class="col-md-3 col-xs-3">
                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input type="checkbox" value="Service"
                                                            class="custom-control-input purpose_checkboxes" id="id001"
                                                            style="font-size:13px">
                                                        <label class="custom-control-label" for="id001">Service</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-xs-3">
                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input type="checkbox" value="Inspection"
                                                            class="custom-control-input purpose_checkboxes" id="id002"
                                                            style="font-size:13px">
                                                        <label class="custom-control-label" for="id002">Inspection</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-xs-3">
                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input type="checkbox" value="Customer Call"
                                                            class="custom-control-input purpose_checkboxes" id="id003"
                                                            style="font-size:13px">
                                                        <label class="custom-control-label" for="id003">Customer Call </label>
                                                    </div>
                                                </div>


                                                <div class="col-md-3 col-xs-3">
                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input type="checkbox" value="Others"
                                                            class="custom-control-input purpose_checkboxes" id="id005"
                                                            style="font-size:13px">
                                                        <label class="custom-control-label" for="id005">Others</label>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-12  PT-10 PB-10">
                                            <h3 class="_head03">Products</h3>
                                        </div>

                                        <div class="col-md-12">

                                            <div class="row _checkbox-padd mb-15">
                                                <input type="text" name="products_hidden_list" id="products_hidden_list"
                                                    hidden />
                                                @if(!empty($categories))
                                                @foreach ($categories as $cat)
                                                <div class="col-md-3 col-xs-3">
                                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input type="checkbox" value="{{ $cat->id }}"
                                                            id="test{{ $cat->id}}" name="{{ $cat->id }}"
                                                            class="custom-control-input checkboxes_products"
                                                            style="font-size:13px">
                                                        <label class="custom-control-label" for="test{{ $cat->id}}">{{
                                                            $cat->name }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif

                                            </div>

                                        </div>



                                            <div class="col-md-12 PB-10">
                                                <h3 class="_head03">Relationship With Customer</h3>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="row _checkbox-padd mb-15">

                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio"
                                                                name="relationship" id="Rb-005" value='Very Good'
                                                                data-id="Rb-005" style="font-size:13px">
                                                            <label class="custom-control-label" for="Rb-005">Very
                                                                Good</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio"
                                                                name="relationship" id="Rb-006" value='Good'
                                                                data-id="Rb-006" style="font-size:13px">
                                                            <label class="custom-control-label"
                                                                for="Rb-006">Good</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio"
                                                                name="relationship" id="Rb-007" value='Need Improvement'
                                                                data-id="Rb-007" style="font-size:13px">
                                                            <label class="custom-control-label" for="Rb-007">Need
                                                                Improvement</label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-12 PT-20">
                                                <h3 class="_head03">Competition
                                                    {{-- <button type="button" class="btn btn-primary add-poc"
                                                        data-toggle="modal" data-target=".competition-lg"><i class="fa fa-plus"></i>
                                                        Add Competition</button> --}}
                                                </h3>
                                            </div>


                                            <div class="col-md-12 mb-5">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="ADD_Select">
                                                            <div class="form-s2 selpluse">
                                                                <select class="form-control formselect"
                                                                    id="comp_dropdwn" placeholder="Select">
                                                                    <option selected disabled value="0">Select
                                                                        Competition</option>
                                                                    @if(!empty($competitions))
                                                                    @foreach ($competitions as $comp)
                                                                    <option value="{{$comp->id}}">{{$comp->name}}
                                                                    </option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <a href="#" class="btn plus_button po-ab"
                                                                data-toggle="modal" data-target=".competition-lg"><i
                                                                    class="fa fa-plus"></i></a>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">

                                                        <div class="row competition_list_div">
                                                            {{-- <div class="col-md-6">
																	<div class="alert fade show alert-color _add-secon w-100 mr-0" role="alert">
																		<div class="row">
																			<div class="col-md-6"><strong>Name: &nbsp;</strong> Name Here</div>
																			<div class="col-md-6"><strong>Strength: &nbsp;</strong> Good</div>
																			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																	</div>
																</div>
																 --}}

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md-12 PB-10 PT-10">
                                                <h3 class="_head03">Competitor’s Strength</h3>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="row _checkbox-padd mb-15">

                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio"
                                                                name="competitions_strength" id="Rb-009"
                                                                value='Very Good' data-id="Rb-009"
                                                                style="font-size:13px">
                                                            <label class="custom-control-label" for="Rb-009">Very
                                                                Good</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio"
                                                                name="competitions_strength" id="Rb-010" value='Good'
                                                                data-id="Rb-010" style="font-size:13px">
                                                            <label class="custom-control-label"
                                                                for="Rb-010">Good</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio"
                                                                name="competitions_strength" id="Rb-011" value='Poor'
                                                                data-id="Rb-011" style="font-size:13px">
                                                            <label class="custom-control-label"
                                                                for="Rb-011">Poor</label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="col-md-12 mt-10">
                                                <h3 class="_head03">Comments</h3>
                                            </div>

                                            <div class="col-md-12 mb-15">
                                                <textarea id="des_svr" class="" style="font-size: 13px" name="des_svr"
                                                    rows="6" style="font-size:13px"></textarea>
                                            </div>

                                            <div class="col-12">
                                                <button type="button"
                                                    class="btn btn-primary mr-2 mb-10 save_svr">Submit</button>
                                                {{-- <button type="button" class="btn btn-primary mr-2 mb-10 preview_cvr">Preview</button> --}}
                                                <button type="button"
                                                    class="btn btn-cancel mb-10 cancel_svr">Cancel</button>
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

    @endsection
