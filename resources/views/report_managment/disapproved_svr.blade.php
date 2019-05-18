@extends('layouts.master')
@section('data-sidebar')

<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">SVR <span> Management</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>SVR</span></a></li>
            <li><span> Status</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>SVR <span> Detail</span></h2>
            </div>
            <div class="body">

                <div class="col-12 p-0">

                    <div class="row">
                        <div class="col-6">Status:</div>
                        <div class="col-6 Dis-h">Disapproved</div>
                    </div>
                    <hr class="mt-10 mb-10">
                    <div class="row">
                        <div class="col-6">Manager Name: </div>
                        <div class="col-6">{{$data->approval_by}}</div>
                    </div>
                    <hr class="mt-10 mb-10">
                    <div class="row">
                        <div class="col-6">Date: </div>
                        <div class="col-6">{{$data->date}}</div>
                    </div>
                    <hr class="mt-10 mb-20">

                </div>


                <div class="col-12 p-0 form-wrap">
                    <label>Manager Remarks</label>
                    <textarea readonly name="description" rows="7"
                        style="font-size: 14px; line-height: 22px; padding: 10px">{{$data->remarks}}  
            </textarea>

                </div>

                <a href="/svr_preview/{{$data->svr_id}}"><button type="submit" class="btn btn-primary mt-10">SVR Preview</button></a>



            </div>



        </div>

    </div>


</div>

@endsection
