@extends('layouts.master')
@section('data-sidebar')

{{-- Modal --}}
<div class="modal fade competition-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content top_border">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Approve <span> CVR</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row form-wrap p-0 PT-10">
                    <div class="col-3">
                        <div class="custom-control custom-radio">
                            <input checked class="custom-control-input" type="radio" name="approval_radio" id="txt-rateY"
                                value='1'>
                            <label class="custom-control-label" for="txt-rateY">Approve</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" name="approval_radio" id="txt-ratN" value='2'>
                            <label class="custom-control-label" for="txt-ratN">Disapprove</label>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <label class="PT-20 font12">Remarks</label>
                        <textarea name="remarks" rows="5"></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-cancel cancel_modal" data-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" class="btn btn-primary save_approval" id="{{$id}}">Save</button>
            </div>


        </div>
    </div>
</div>


<div class="row mt-2 mb-3">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h2 class="_head01">CUSTOMER <span>VISIT REPORT</span></h2>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6">
            <ol class="breadcrumb">
                <li><a href="#"><span>Customer Visit Report</span></a></li>
                <li><span>Add Report</span></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                    <div id="printResult">

                <div class="body">
                    <div class="report-logo"><img src="/images/oriente-engineering-services.jpg" alt="" /></div>

                    <div class="report-preview">

                        <div class="row">
                            <div class="col-md-6 p-0"><strong>Date Of Report:</strong> {{ $core->report_created_at }}</div>
                            <div class="col-md-6 p-0 _RtextP "><strong>Report Prepared By:</strong> {{ $core->created_by }}</div>

                        </div>

                        <div class="row PT-10">
                            <div class="col-md-6 style-bg"><strong>Date Of Visit: </strong> {{ $core->date_of_visit }}</div>
                            <div class="col-md-6 style-bg _RtextP"><strong>Customer Visited: </strong> {{ $core->customer_name }}</div>
                            <div class="col-md-6 style-bg"><strong>Location: </strong>{{ $core->location }}</div>
                            <div class="col-md-6 style-bg _RtextP"><strong> Time Spent: </strong>{{ $core->time_spent }}</div>

                        </div>

                        <div class="row PT-30">
                            <div class="col-12 p-0">
                                <h3 class="_head03">POC Name</h3>
                                <div class="row">
                                        @if(!empty($poc))
                                        @foreach ($poc as $data)
                                            <div class="col-md-4">{{ $data->poc_name }}</div>
                                        @endforeach
                                        @endif
                                </div>
                            </div>
                        </div>

                        <div class="row PT-30">
                            <div class="col-12 p-0">
                                <h3 class="_head03">Purpose of Visit</h3>
                                <div class="row">
                                        <?php 
                                        if (strpos($core->purpose_of_visit, ',') !== false) {
                                            $myArray = explode(',', $core->purpose_of_visit);
                                            foreach($myArray as $pur){ ?>
                                        <div class="col-md-3"><i class="fa fa-check"></i> {{ $pur }}</div>
                                        <?php }

                                        } else{ ?>
                                        <div class="col-md-3"><i class="fa fa-check"></i> {{ $core->purpose_of_visit }}</div>
                                        <?php }   
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row PT-30">
                            <div class="col-12 p-0">
                                <h3 class="_head03">Products</h3>
                                <div class="row">
                                        @if(!empty($products))
                                        @foreach ($products as $pro)
                                            <div class="col-md-3"><i class="fa fa-check"></i> {{ $pro->cat_name }}</div>
                                        @endforeach
                                        @endif
                                </div>
                            </div>
                        </div>

                        <div class="row MR-S">

                            <div class="col-md-4 PT-30">
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <h3 class="_head03">Opportunity</h3>
                                        <i class="fa fa-check"></i> {{ $core->opportunity }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 PT-30">
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <h3 class="_head03">Annual Business Value</h3>
                                        <i class="fa fa-check"></i> {{ ($core->bussiness_value == '2500K+' ? '> 2500K' : $core->bussiness_value) }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 PT-30">
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <h3 class="_head03 mr-0">Relationship With Customer</h3>
                                        <i class="fa fa-check"></i> {{ $core->relationship }}
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row PT-30">
                            <div class="col-12 p-0">
                                <h3 class="_head03">Competition</h3>
                                <div class="row">
                                       
                                    <?php 
                                    if($competition->isEmpty()){
                                        $strength = 'NA';
                                    }else{ ?>
                                        @foreach ($competition as $comp)
                                        <?php $strength = $comp->strength; ?>
                                            <div class="col-md-6"><strong>Competition Name:</strong> {{ $comp->name }} </div>
                                        @endforeach
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row PT-30">
                            <div class="col-12 p-0">
                                <h3 class="_head03">Competitor’s Strength</h3>
                                <div class="row">
                                    <div class="col-md-12"><i class="fa fa-check"></i> {{ $strength  }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row PT-30">
                            <div class="col-12 p-0">
                                <h3 class="_head03">Visit Summary</h3>
                                <p>{{ $core->description }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 p-0 PT-20 order-2 order-md-1">
                                {{-- @if($core->is_approved != 1)
                                    @if(Auth::user()->designation == '1' || Auth::user()->designation == '2')
                                        <button type="button" class="btn btn-primary mb-10 mr-2" data-toggle="modal"
                                            data-target=".competition-lg">Approve CVR</button>
                                    @endif
                                @endif --}}
                                @if($approval_able == 1)
                                <button type="button" class="btn btn-primary mb-10 mr-2" data-toggle="modal"
                                data-target=".competition-lg">Approve CVR</button>
                                @endif
                                
                                <button type="button" class="btn btn-cancel mb-10 cancel_cvr">Cancel</button></div>

                                <a href="/download_pdf/{{$id}}"><div class="col-md-6 p-0 _RtextP PT-20 order-1 order-md-2"><button type="button"
                                    class="btn btn-primary L_btn-line mr-2 mb-10">Save PDF</button></a>
                                <a href="/send_mail/{{$id}}"><button type="button" class="btn btn-primary L_btn-line mr-2 mb-10">Send Email</button></a>
                                <button type="button" class="btn btn-primary L_btn-line mr-2 mb-10 print_page">Print</button></div>
                        </div>




                    </div>

                </div>
            </div>
            </div>


        </div>

@endsection
