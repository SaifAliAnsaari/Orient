@extends('layouts.master')
@section('data-sidebar')




<div id="product-cl-sec">
    <a href="#" id="pl-close" class="close-btn-pl"></a>
    <div class="pro-header-text">New <span>Employee</span></div>
    <div style="min-height: 400px; display: none;" id="dataSidebarLoader">
        <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
    </div>
    <div class="pc-cartlist">
        <form style="display: flex; width: 100%" id="saveEmployeeForm" enctype="multipart/form-data">
            {!! Form::hidden('employee_updating_id', '') !!}
            @csrf
            <input type="text" id="operation" hidden>
            <div class="overflow-plist">
                <div class="plist-content">
                    <div class="_left-filter" style="padding-top:30px">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div id="floating-label" class="card p-20 top_border mb-3">
                                        <h2 class="_head03">Profile <span>Details</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Full Name*</label>
                                                        <input type="text" name="name" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Phone No</label>
                                                        <input type="number" name="phone" style="font-size: 13px"
                                                            class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Email ID*</label>
                                                        <input type="email" name="email" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">CNIC No</label>
                                                        <input type="number" name="cnic" style="font-size: 13px"
                                                            maxlength="13" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="form-s2 col-md-6"
                                                    style="margin-top:10px; margin-bottom:10px;">
                                                    <div>
                                                        <input hidden type="text" value="{{$data}}"
                                                            id="full_cities_array" />
                                                        <div>
                                                            <label class=" font12">City*</label>
                                                            <select class="form-control formselect required"
                                                                id="select_city" name="city" placeholder="Select City">
                                                                <option value="0" selected disabled>Select City*
                                                                </option>
                                                                @if(!empty($data))
                                                                @foreach ($data as $city)
                                                                <option name="{{$city->province}}"
                                                                    value="{{$city->id}}">{{$city->city_name}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-6"
                                                    style="margin-top:10px; margin-bottom:10px;">
                                                    <div>
                                                        <label class=" font12">Province*</label>
                                                        <select class="form-control formselect" id="select_province"
                                                            name="province" placeholder="Select Province">
                                                            <option value="0" selected disabled>Select Province</option>
                                                            @if(!empty($provinces))
                                                            @foreach ($provinces as $province)
                                                            <option value="{{$province['province']}}">
                                                                {{$province['province']}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                {{-- <div class="col-md-6">
                                                    <div class="form-s2 pt-19">
                                                        <select name="country" id="country" class="form-control formselect required" placeholder="select Country">
                                                            <option value="0" diabled >Select Country</option>
                                                            <option value="1" selected>Pakistan</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Address</label>
                                                        <input type="text" name="address" style="font-size: 13px"
                                                            class="form-control" placeholder="">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <h2 class="_head03 PT-10">Create <span> User</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Username*</label>
                                                        <input type="text" name="username" style="font-size: 13px"
                                                            class="form-control required" placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Password*</label>
                                                        <input type="password" name="password" style="font-size: 13px"
                                                            class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-wrap pt-19" id="dropifyImgDiv">
                                                        {{-- <div class="upload-pic"></div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="_head03 PT-10">Additional <span> Details</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="font12">Hiring Date*</label>
                                                    <div class="form-group" style="height: auto!important">
                                                        <input type="text" name="hiring" style="font-size: 13px"
                                                            id="datepicker" class="form-control required"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {{-- <div class="form-group">
                                                            <label class="control-label mb-10">Salary</label>
                                                            <input type="number" name="salary" style="font-size: 13px" class="form-control" placeholder="">
                                                        </div> --}}
                                                    <div class="form-s2">
                                                        <div>
                                                            <label class="font12">Division</label>
                                                            <select name="division"
                                                                class="form-control formselect required"
                                                                placeholder="Select Division">
                                                                <option value="0" disabled selected>Select Division*
                                                                </option>
                                                                <option value="1">Chemical </option>
                                                                <option value="2">Equipment</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-s2 pt-10">
                                                        <div>
                                                            <label class=" font12">Designation*</label>
                                                            <select name="designation"
                                                                class="form-control formselect required"
                                                                placeholder="select Designation">
                                                                <option value="0" disabled selected>Select Designation*
                                                                </option>
                                                                @if(!empty($designations))
                                                                @foreach ($designations as $designation)
                                                                <option value="{{$designation->id}}">
                                                                    {{$designation->name}}</option>
                                                                @endforeach
                                                                @endif
                                                                {{-- <option value="1">Admin</option>
                                                                    <option value="2">Manager</option>
                                                                    <option value="3">Salesman</option>
                                                                    <option value="4">Rider</option>
                                                                    <option value="5">Cashier</option>  --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-s2 pt-10">
                                                        <div>
                                                            <label class=" font12">Reporting To*</label>
                                                            <select name="reporting"
                                                                class="form-control formselect required"
                                                                placeholder="Reporting To">
                                                                <option value="0" disabled selected>Reporting To*
                                                                </option>
                                                                @if(!empty($emp))
                                                                @foreach ($emp as $employee)
                                                                <option value="{{$employee->id}}">{{$employee->name}}
                                                                </option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-s2 pt-19 col-md-6">
                                                    <div>
                                                        <label class=" font12">Department*</label>
                                                        <select name="department"
                                                            class="form-control formselect required"
                                                            placeholder="Select Department">
                                                            <option value="0" disabled selected>Select Department*
                                                            </option>
                                                            <option value="1">Sales</option>
                                                            <option value="2">Services</option>
                                                            <option value="3">Technical Support</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-s2 pt-19 col-md-6">
                                                    <div>
                                                        <label class=" font12">Company*</label>
                                                        <select name="company" class="form-control formselect required"
                                                            placeholder="Select Department">
                                                            <option value="0" disabled selected>Select Company*</option>
                                                            <option value="Orient Engineering Services">Orient
                                                                Engineering Services</option>
                                                            <option value="Orient Water Services (Pvt) Ltd">Orient Water
                                                                Services (Pvt) Ltd</option>
                                                            <option value="OriChem Services (Pvt) Ltd">OriChem Services
                                                                (Pvt) Ltd</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="_head03 PT-20">Notification<span> Management</span></h2>

                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-s2">
                                                        <label class="PT-10 font12">Sales Report</label>
                                                        <select class="form-control sd-type" name="select_sales_emp"
                                                            multiple="multiple">
                                                            @if(!empty($emp))
                                                            @foreach ($emp as $employee)
                                                            <option value='{{$employee->id}}'>{{$employee->name}}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <input hidden name="hidden_cvr_emp" value="" type="text" />

                                                    <div class="form-s2">
                                                        <label class="PT-10 font12">Services Report</label>
                                                        <select class="form-control sd-type" name="select_service_emp"
                                                            multiple="multiple">
                                                            @if(!empty($emp))
                                                            @foreach ($emp as $employee)
                                                            <option value='{{$employee->id}}'>{{$employee->name}}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <input hidden name="hidden_svr_emp" value="" type="text" />

                                                    <div class="form-s2">
                                                        <label class="PT-10 font12">Complaint Report</label>
                                                        <select class="form-control sd-type"
                                                            name="select_complaints_emp" multiple="multiple">
                                                            @if(!empty($emp))
                                                            @foreach ($emp as $employee)
                                                            <option value='{{$employee->id}}'>{{$employee->name}}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <input hidden name="hidden_complaint_emp" value="" type="text" />

                                                    <div class="form-s2">
                                                        <label class="PT-10 font12">Customers Report</label>
                                                        <select class="form-control sd-type" name="select_customers_emp"
                                                            multiple="multiple">
                                                            @if(!empty($emp))
                                                            @foreach ($emp as $employee)
                                                            <option value='{{$employee->id}}'>{{$employee->name}}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <input hidden name="hidden_customers_emp" value="" type="text" />

                                                </div>
                                            </div>
                                        </div>

                                        <h2 class="_head03 PT-20">Super<span> Admin</span></h2>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                    <input type="checkbox" value="Proposal"
                                                        class="custom-control-input super_admin" id="super_admin"
                                                        style="font-size:13px">
                                                    <label class="custom-control-label" for="super_admin">Super Admin</label>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="text" name="super_admin" hidden id="hidden_super_admin"/>

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
        <button type="button" class="btn btn-primary mr-2" id="saveEmployee">Save</button>
        <button id="pl-close" type="button" class="btn btn-cancel mr-2" id="cancelEmployee">Cancel</button>
    </div>
</div>



@endsection

@section('content')

{{-- Modal --}}
<div class="modal fade db-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 650px">
        <div class="modal-content top_border">
            <div class="modal-header" style="text-align: center; display: block">
                <h5 class="modal-title" id="exampleModalLongTitle">Employee <span>has been successfully added</span>
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
                    <h1 class="_head05" align="center"><span>Do you want to add </span> Access Rights or Add Another
                        Employee?</h1>

                    <div class="PT-15 PB-10" align="center">
                        <button type="button" data-dismiss="modal" class="btn btn-primary font13 m-0 mr-2 mb-2">Add
                            Another Employee</button>
                        <a href="/" class="access_right_link"><button type="button"
                                class="btn btn-primary font13 m-0 mb-2">Add Access Rights
                            </button></a>
                        <!--<button type="submit" class="btn btn-cancel m-0 mb-2" data-dismiss="modal" aria-label="Close">No</button> -->
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<div class="open_confirmation_modal" data-toggle="modal" data-target=".db-confirmation-modal"></div>


<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Employee <span>Management</span></h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Employee</span></a></li>
            <li><span>List</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <a id="productlist01" class="btn add_button openDataSidebarForAddingEmployee"><i class="fa fa-plus"></i>
                    <span> New Employee</span></a>
                <h2>Employee <span> List</span></h2>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto"
                    style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="body" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection
