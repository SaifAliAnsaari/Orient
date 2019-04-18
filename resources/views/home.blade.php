<?php 
if(!empty($check_rights)){
    $test_array = array();
    $counter = 0;
    foreach($check_rights as $rights){
        $test_array[$counter] = $rights->access;
        $counter++;
    } ?>


@extends('layouts.master')

@section('content')
<div class="row PT-20">
    @if(in_array("/new_cvr", $test_array))
    <div class="col-md-4">
        <a href="/new_cvr" class="box-sec">
            <span class="img-svg"><img src="/images/add-report-icon.svg" alt=""></span>
            <strong>Add</strong> CVR
        </a>
    </div>
    @endif

    @if(in_array("/Customer_list", $test_array))
    <div class="col-md-4">
        <a href="/Customer_list" class="box-sec">
            <span class="img-svg"><img src="/images/add-customer.svg" alt=""></span>
            <strong>Add</strong> Customer
        </a>
    </div>
    @endif

    @if(in_array("/poc_list", $test_array))
    <div class="col-md-4">
        <a href="/poc_list" class="box-sec">
            <span class="img-svg"><img src="/images/add-poc.svg" alt=""></span>
            <strong>Add</strong> POC
        </a>
    </div>
    @endif

    @if(in_array("/generate_complaints", $test_array))
    <div class="col-md-4">
        <a href="/generate_complaints" class="box-sec">
            <span class="img-svg"><img src="/images/complain-icon.svg" alt=""></span>
            <strong>Complaints</strong>
        </a>
    </div>
    @endif

    @if(in_array("/edit_profile", $test_array))
    <div class="col-md-4">
        <a href="/edit_profile/{{Auth::user()->id}}" class="box-sec">
            <span class="img-svg"><img src="/images/cont-setting.svg" alt=""></span>
            <strong>Setting</strong>
        </a>
    </div>
    @endif
</div>
@endsection
<?php } ?>
