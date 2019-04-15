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

                    <table class="report-preview" border="0" align="center" cellpadding="3" cellspacing="3">
                        <tbody>
                            <tr>
                                <td width="50%"><strong>Date Of Report: </strong>{{ $core->report_created_at }}</td>
                                <td width="50%" align="right"><strong>Report Prepared By:
                                    </strong>{{ $core->created_by }}</td>
                            </tr>
                            <tr>
                                <td height="10"></td>
                                <td height="10"></td>
                            </tr>
                            <tr>
                                <td class="style-bg" valign="top"><strong>Date Of Visit: </strong>{{ $core->date_of_visit
                                }}</td>
                                <td class="style-bg" align="right" valign="top"><strong>Customer Visited: </strong>{{
                                $core->customer_name }}</td>
                            </tr>
                            <tr>
                                <td class="style-bg" valign="top"><strong>Location:</strong>{{ $core->location }}</td>
                                <td class="style-bg" align="right" valign="top"><strong>Time Spent: </strong>{{
                                $core->time_spent }}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3 class="_head03">POC Name</h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                @if(!empty($poc))
                                                @foreach ($poc as $data)
                                                <td>{{ $data->poc_name }}</td>
                                                @endforeach
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3 class="_head03">Purpose of Visit</h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <?php 
                                                if (strpos($core->purpose_of_visit, ',') !== false) {
                                                    $myArray = explode(',', $core->purpose_of_visit);
                                                    foreach($myArray as $pur){ ?>
                                                <td><i class="fa fa-check"></i> {{ $pur }}</td>
                                                <?php }

                                                } else{ ?>
                                                <td><i class="fa fa-check"></i> {{ $core->purpose_of_visit }}</td>
                                                <?php }   
                                            ?>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3 class="_head03">Products</h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                @if(!empty($products))
                                                @foreach ($products as $pro)
                                                <td><i class="fa fa-check"></i> {{ $pro->cat_name }}</td>
                                                @endforeach
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="6">
                                        <tbody>
                                            <tr>
                                                <td width="33%">
                                                    <h3 class="_head03">Opportunity</h3>
                                                    <i class="fa fa-check"></i> {{ $core->opportunity }}
                                                </td>
                                                <td width="33%">
                                                    <h3 class="_head03">Annual Business Value</h3>
                                                    <i class="fa fa-check"></i> {{ ($core->bussiness_value == '2500K+' ? '> 2500K' : $core->bussiness_value) }}
                                                </td>
                                                <td width="33%">
                                                    <h3 class="_head03">Relationship With Customer</h3>
                                                    <i class="fa fa-check"></i> {{ $core->relationship }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3 class="_head03">Competition</h3>
                                </td>
                            </tr>
                            @if(!empty($competition))
                            @foreach ($competition as $comp)
                            <tr>
                                <td><strong>Competition Name:</strong> {{ $comp->name }}</td>
                                <td><strong>Competitor’s Strength:</strong> {{ $comp->strength }}</td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3 class="_head03">Visit Summary</h3>
                                    <p>{{ $core->description }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>

                            </tr>
                        </tbody>
                    </table>






                </div>
            </div>
           

            <table class="report-preview" border="0" align="center" cellpadding="3" cellspacing="3">
                <tbody>
                    <tr>
                        <td align="left">
                            @if($core->is_approved != 1)
                                @if(Auth::user()->designation == '1' || Auth::user()->designation == '2')
                                    <button type="button" class="btn btn-primary mb-10" data-toggle="modal"
                                        data-target=".competition-lg">Approve CVR</button>
                                @endif
                            @endif
                            <button type="submit" class="btn btn-cancel mb-10" style="margin-left:5px;">Cancel</button>
                        </td>
                        <td align="right">
                            <a href="/download_pdf/{{$id}}"><button type="button" class="btn btn-primary L_btn-line mr-2 mb-10">Save PDF</button></a>
                            <a href="/send_mail/{{$id}}"><button type="button" class="btn btn-primary L_btn-line mr-2 mb-10">Send Email</button></a> 
                            <button type="button" class="btn btn-primary L_btn-line mr-2 mb-10 print_page">Print</button></td>
                    </tr>

                </tbody>
            </table>
            {{-- <div align="center">
                <a href="/download_pdf/{{$id}}"><button type="submit" class="btn btn-primary mr-2 mb-10">Save
                PDF</button></a>
            <a href="/send_mail/{{$id}}"><button type="submit" class="btn btn-primary mr-2 mb-10 ">Send
                    Email</button></a>
            <button type="submit" class="btn btn-primary mr-2 mb-10 print_page">Print</button>
            <button type="submit" class="btn btn-cancel mb-10">Cancel</button>
        </div> --}}

    </div>


</div>

@endsection
