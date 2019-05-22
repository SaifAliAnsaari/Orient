@extends('layouts.master')
@section('data-sidebar')


{{-- Modal --}}
<div class="modal fade competition-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
            
        <div class="modal-content top_border" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Complaint <span>Type</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">

                <div class="col-12">
                        <img src="/images/loader.gif" width="30px" id="modal_loader" height="auto" style="position: absolute; left: 50%; top: 45%; display:none">
                    <div id="floating-label" class="form-wrap pt-0 PB-20">
                        <input id="operation" type="text" hidden/>
                        <div class="row pt-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Complaint Head</label>
                                    <input type="text" id="complain_head"  class="form-control required" placeholder=""
                                        style="font-size: 13px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">TAT - Turn Around Time (Days)</label>
                                    <input type="number" id="complain_tat"  class="form-control required" placeholder=""
                                        style="font-size: 13px">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-s2">
                                    <label class="PT-10 font12">Assign To</label>
                                    <div>
                                        <select class="form-control sd-type required" id="complain_assign_to" multiple="multiple">
                                            <option disabled value="0">Select Employee</option>
                                            @if(!empty($employees))
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->name}}</option>
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

            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-cancel cancel_modal" data-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" class="btn btn-primary save_complaint_type" id="">Save</button>
            </div>


        </div>
    </div>
</div>




<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Complaints <span> Management</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Complaints</span></a></li>
            <li><span> Type List</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <a href="#" data-toggle="modal" data-target=".competition-lg" class="btn add_button open_modal_for_add"><i
                        class="fa fa-plus"></i> <span>New Complaint Type</span></a>
                <h2>Complaint<span> Type List</span></h2>
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
