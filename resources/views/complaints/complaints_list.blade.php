@extends('layouts.master')
@section('data-sidebar')

{{-- Modal --}}
<div class="modal fade competition-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content top_border">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Complaint <span> Resolve</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">

                <div class="col-12">

                    <div id="floating-label" class="form-wrap p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="PT-10 font12">Remarks</label>
                                <textarea name="description" id="remarks_resolve" rows="10"></textarea>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-cancel cancel_modal" data-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" class="btn btn-primary resolve_modal">Resolve</button>
            </div>


        </div>
    </div>
</div>



{{-- Detail Modal --}}
<div class="modal fade competition-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
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
                        
                    <div id="floating-label" class="form-wrap pt-0 PB-20">
                            
                        <input id="operation" type="text" hidden/>
                        <div class="row pt-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Customer</label>
                                    <input type="text" id="customer_detail_modal"  class="form-control required" placeholder=""
                                        style="font-size: 13px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Complain</label>
                                    <input type="text" id="complain_detail_modal"  class="form-control required" placeholder=""
                                        style="font-size: 13px">
                                </div>
                            </div>

                            
                            <div class="col-md-12">
                                <div id="floating-label" class="form-wrap p-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="PT-10 font12">Remarks</label>
                                            <textarea name="description" id="remarks_detail_modal" rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img src="/images/loader.gif" width="30px" id="modal_loader" height="auto" style="position: absolute; left: 50%; top: 45%">
                    </div>

                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-cancel cancel_modal" data-dismiss="modal" aria-label="Close">Cancel</button>
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
            <li><span> List</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>Complaint<span> List</span></h2>
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
