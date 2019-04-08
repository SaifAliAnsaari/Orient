@extends('layouts.master')
@section('data-sidebar')

<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Access <span> Rights</span></h2>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Dashboard</span></a></li>
            <li><span>Access Rights</span></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form id="saveAccessRights">
                {!! Form::hidden('tokenForAjaxReq', csrf_token()) !!}
                @csrf
                <input name="access_route" hidden type="text" />
                <input name="select_employee" hidden type="text" value="<?= $employee_id ?>">
                {{-- <div style="min-height: 400px" id="dataSidebarLoader" style="">
                    <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
                </div> --}}

                    <div class="header">
                        <h2>Select Rights</h2>
                    </div>

                    <div class="body">
                    <div class="col-md-12">
                        <div class="row">
                          
                            @if (!empty($controllers))
                                @foreach ($controllers as $controller)
                                    <ul class="acc-right">
                                        <h2 class="_head04"> {{ $controller['heading'] }}</h2>
                                        @foreach($controller['detail'] as $detail)
                                        <li>
                                                <div class="custom-control custom-checkbox mr-sm-2">
                                                        <input type="checkbox" class="custom-control-input routes" name="right_boxes" value="<?= $detail['route'] ?>"
                                                            id="<?= $detail['route'] ?>" <?= (!$rights == "")?(array_search($detail['route'], array_column($rights->toArray(), 'access')) !== false && array_search($detail['route'], array_column($rights->toArray(), 'access')) >= 0) ? "checked" : "" : "" ?>>
                                                        <label class="custom-control-label" for="<?= $detail['route'] ?>">
                                                            <?= $detail['name'] ?></label>
                                                    </div>
                                        </li>
                                        @endforeach
                                            
                                    </ul>
                                @endforeach
                            @endif()

                           

                           

                        </div>
                    </div>
                    <div class="col-md-12 pt-20 text-center">
                        <button style="margin-left:10px;" type="button" class="btn btn-primary mr-2 save_rights">Save</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

@endsection
