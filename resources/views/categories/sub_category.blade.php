@extends('layouts.master')

@section('data-sidebar')

    
<div id="product-cl-sec">
    <a href="" id="pl-close" class="close-btn-pl close_customer_form"></a>
    <div class="pro-header-text">New <span>Category</span></div>
    <div style="min-height: 400px" id="dataSidebarLoader" style="display: none">
        <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
    </div>
    <div class="pc-cartlist">
        <form style="display: flex;" id="saveSubCategotyForm" style="width:100%">
            {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
            @csrf
            <input type="text" name="hidden_sub_cat_id" hidden/>
            <input type="text" id="operation" hidden>
            <div class="overflow-plist">
                <div class="plist-content">
                    <div class="_left-filter">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div id="floating-label" class="card p-20 top_border mb-3">
                                        <h2 class="_head03">Category <span>Details</span></h2>
                                        <div class="form-wrap p-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10">Category Name*</label>
                                                        <input type="text" style="font-size: 13px" name="sub_cat_name" class="form-control required">
                                                    </div>
                                                </div>
                                                <div class="form-s2 col-md-12">
                                                    <div>
                                                        <select class="form-control formselect required" name="select_main_cat" placeholder="Select Customer Type">
                                                            <option value="0" selected disabled>Select Main Category*</option>
                                                            @if(!empty($main_cat))
                                                                @foreach ($main_cat as $cat)
                                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                                @endforeach
                                                            @endif
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
        <button type="button" class="btn btn-primary mr-2" id="saveSubCat">Save</button>
        <button id="pl-close" type="button" class="btn btn-cancel mr-2" id="cancelSubCat">Cancel</button>
    </div>
</div>
@endsection

@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Sub Categories</h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Sub Categories</span></a></li>
            <li><span>Active</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <a class="btn add_button openDataSidebarForAddingSubCat"><i class="fa fa-plus"></i> Add Category</a>
                <h2>Categories List</h2>
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
