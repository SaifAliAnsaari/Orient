@extends('layouts.master')
@section('data-sidebar')
<div id="product-cl-sec">
    <a href="#" id="pl-close" class="close-btn-pl"></a>
    <div class="pro-header-text">New <span>Employee</span></div>
    <div style="min-height: 400px" id="dataSidebarLoader" style="display: none">
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
                                                        <input type="text" name="name" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Phone No</label>
                                                        <input type="number" name="phone" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Email ID*</label>
                                                        <input type="email" name="email" class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">CNIC No</label>
                                                        <input type="number" name="cnic" maxlength="13" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">City*</label>
                                                        <input type="text" name="city"  class="form-control required" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">State</label>
                                                        <input type="text" name="state"  class="form-control" placeholder="">
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
                                                        <input type="text" name="address" class="form-control" placeholder="">
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
                                                        <input type="text" name="username" class="form-control required" placeholder="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Password*</label>
                                                        <input type="password" name="password" class="form-control" placeholder="">
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
                                                            <input type="text" name="hiring" id="datepicker" class="form-control required" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10">Salary</label>
                                                            <input type="number" name="salary" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-s2 pt-10">
                                                            <div>
                                                                <select name="designation" class="form-control formselect required" placeholder="select Designation">
                                                                    <option value="0" disabled selected>Select Designation*</option>
                                                                    <option value="1">Admin</option>
                                                                    <option value="2">Manager</option>
                                                                    <option value="3">Salesman</option>
                                                                    <option value="4">Rider</option>
                                                                    <option value="5">Cashier</option> 
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-s2 pt-19">
                                                            <div>
                                                                <select name="reporting" class="form-control formselect required" placeholder="Reporting To">
                                                                    <option value="0" disabled selected>Reporting To*</option>
                                                                    @if(!empty($emp))
                                                                        @foreach ($emp as $employee)
                                                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-s2 pt-19 col-md-6">
                                                        <div>
                                                            <select name="department" class="form-control formselect required" placeholder="Select Department">
                                                                <option value="0" disabled selected>Select Department*</option>
                                                                <option value="1">Sales</option>
                                                                <option value="2">Services</option>
                                                                <option value="3">Technical Support</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-s2 pt-19 col-md-6">
                                                        <div>
                                                            <select name="company" class="form-control formselect required" placeholder="Select Department">
                                                                <option value="0" disabled selected>Select Company*</option>
                                                                <option value="Orient Engineering Services">Orient Engineering Services</option>
                                                                <option value="Orient Water Services (Pvt) Ltd">Orient Water Services (Pvt) Ltd</option>
                                                                <option value="OriChem Services (Pvt) Ltd">OriChem Services (Pvt) Ltd</option>
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
                <a id="productlist01" href="#" class="btn add_button openDataSidebarForAddingEmployee"><i class="fa fa-plus"></i> <span> New Employee</span></a>
                <h2>Employee <span> List</span></h2>
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
