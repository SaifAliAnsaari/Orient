@extends('layouts.master')
@section('data-sidebar')


<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">POC <span>Profile</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>POC </span></a></li>
            <li><span>Profile</span></li>
        </ol>
    </div>
</div>


<form style="display: flex;" id="edit_poc_form">
    {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
    @csrf
    <input id="hidden_poc" name="hidden_poc" value="{{ $poc->id }}" type="text" hidden value=""/>
<div class="row">
    <div class="col-lg-12 col-12 mb-30">
        <div class="card cp_user-l top_border">
            <div class="body">

                <div class="form-wrap p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">POC Name</label>
                                <input type="text" id="" style="font-size: 13px" class="form-control required" value="{{ $poc->poc_name }}" name="poc_name" placeholder="Name" style="font-size:13px">
                            </div>
                        </div>
                        <div class="col-md-6 form-s2">
                                <div>
                                    <select class="form-control formselect" name="company_name" placeholder="Select Customer Type">
                                        <option value="0" selected disabled>Select Company Name*</option>
                                        @if(!empty($cust))
                                            @foreach ($cust as $customers)
                                                <?php if($poc->company_name == $customers->id){ ?>
                                                    <option selected value="{{$customers->id}}">{{$customers->company_name}}</option> 
                                                <?php }else{ ?>
                                                <option value="{{$customers->id}}">{{$customers->company_name}}</option>
                                                <?php } ?>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            {{-- <div class="form-group">
                                <label class="control-label mb-10">Company Name</label>
                                <input type="text" id="" class="form-control required" value="{{ $poc->company_name }}" name="company_name" placeholder="Company Name" style="font-size:13px">
                            </div> --}}
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">Job Title</label>
                                <input type="text" id="" style="font-size: 13px" class="form-control required" value="{{ $poc->job_title }}" name="job_title" placeholder="Job Title" style="font-size:13px">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">Business Phone</label>
                                <input type="text" id="" style="font-size: 13px" class="form-control required" value="{{ $poc->bussiness_ph }}" name="bussiness_ph" placeholder="000000" style="font-size:13px">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">Email ID</label>
                                <input type="email" id="" style="font-size: 13px" class="form-control required" value="{{ $poc->email }}" name="email" placeholder="" style="font-size:13px">
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">Address</label>
                                <input type="text" id="" class="form-control required" value="{{ $poc->address }}" name="address" placeholder="" style="font-size:13px">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">City</label>
                                <input type="text" id="" class="form-control required" value="{{ $poc->city }}" name="city" placeholder="City" style="font-size:13px">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label mb-10">State/Province</label>
                                <input type="text" id="" class="form-control required" value="{{ $poc->state }}" name="state" placeholder="Punjab" style="font-size:13px">
                            </div>
                        </div> --}}

                        <div class="col-md-12 mt-5">
                            <button type="button" class="btn btn-primary mr-2 edit_poc_btn">Edit</button>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>

</div>
</form>


@endsection
