@extends('layouts.master')
@section('data-sidebar')

<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Customer <span>Visit Report</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Customer Visit Report</span></a></li>
            <li><span>List</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>CVR <span> List</span></h2>
                <select class="custom-select custom-select-sm select_cvr_type" style="float:right; width:150px;">
                        <option selected disabled>Please Select CVR Type</option>
                        <option value="1" selected>All CVRs</option>
                        <option value="2">Approved</option>
                        <option value="3">Disapproved</option>
                        <option value="4">Pending</option>
                    </select>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="body">

            </div>
        </div>

    </div>


</div>


@endsection
