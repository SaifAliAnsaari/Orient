@extends('layouts.master')
@section('data-sidebar')

<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Complaints <span> Management</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Complaints</span></a></li>
            <li><span>Generate</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>Complaint<span> Generate</span></h2>
            </div>
            <div class="body">

                <div class="col-md-12">
                    <div id="floating-label" class="form-wrap pt-0 PB-20">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-s2 pt-19">
                                    <div>
                                        <select class="form-control formselect required" id="customers" placeholder="Customer">
                                            <option disabled selected value="0">Select Customer</option>
                                            @if(!empty($cust))
                                                @foreach ($cust as $customers)
                                                    <option value="{{$customers->id}}">{{$customers->company_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-s2 pt-19">
                                    <div>
                                        <select class="form-control formselect required" id="complain_type" placeholder="Complaint">
                                            <option disabled selected value="0">Complaint Type</option>
                                            @if(!empty($complain_type))
                                                @foreach ($complain_type as $type)
                                                    <option value="{{$type->id}}">{{$type->complain_head}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="PT-10 font12">Remarks</label>
                                <textarea name="description" id="description" class="required" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-btns pt-0">
                        <button type="submit" class="btn btn-cancel mr-2 cancel_complain">Cancel</button>
                        <button type="submit" class="btn btn-primary generate_complain">Generate</button>
                    </div>
                </div>


            </div>



        </div>

    </div>


</div>

@endsection
