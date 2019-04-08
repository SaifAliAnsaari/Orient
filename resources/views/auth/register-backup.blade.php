@extends('layouts.master')

@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Employee Management</h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Employee</span></a></li>
            <li><span>Add Employee</span></li>
        </ol>
    </div>
</div>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h2>Add New Employee</h2>
                </div>
                <div class="body">
                    <h2 class="_head03">Profile</h2>
                    <div class="form-wrap _w90 PT-10 PB-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Full Name*</label>
                                    <input type="text" value="{{ old('name') }}" name="name" class="form-control" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Phone No</label>
                                    <input type="text" value="{{ old('phone') }}" name="phone" class="form-control" placeholder="">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Email ID*</label>
                                    <input type="email" value="{{ old('email') }}" name="email" class="form-control" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">CNIC No</label>
                                    <input type="text" value="{{ old('cnic') }}" name="cnic" class="form-control" placeholder="">

                                    @if ($errors->has('cnic'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('cnic') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">City</label>
                                    <input type="text" value="{{ old('city') }}" name="city" class="form-control" placeholder="">

                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">State</label>
                                    <input type="text" value="{{ old('state') }}" name="state" class="form-control" placeholder="">

                                    @if ($errors->has('state'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Country</label>
                                    <input type="text" value="{{ old('country') }}" name="country" class="form-control" placeholder="">

                                    @if ($errors->has('country'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Address</label>
                                    <input type="text" value="{{ old('address') }}" name="address" class="form-control" placeholder="">

                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="_head03">Employee Detail</h2>
                    <div class="form-wrap _w90 PT-10 PB-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Designation</label>
                                    <div class="form-s2">
                                        <select class="form-control formselect" name="designation" placeholder="select Country">
                                            <option value="admin">Admin</option>
                                            <option value="manager">Manager</option>
                                            <option value="salesman">Salesman</option>
                                            <option value="rider">Rider</option>
                                            <option value="cashier">Cashier</option>  
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Hiring Date</label>
                                    <input type="text" id="datepicker" name="hiring" class="form-control" placeholder="">

                                    @if ($errors->has('hiring'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('hiring') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Salary</label>
                                    <input type="text" value="{{ old('salary') }}" name="salary" class="form-control" placeholder="">

                                    @if ($errors->has('salary'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('salary') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label mb-10">Reporting To*</label>
                                    <div class="form-s2">
                                        <select class="form-control formselect" name="reportingTo" placeholder="select Country">
                                            <option value="1">Admin</option>
                                            <option value="1">Manager</option>
                                            <option value="1">Salesman</option>
                                            <option value="1">Rider</option>
                                            <option value="1">Cashier</option>  
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="_head03 pt-2">Access Rights</h2>
                    <div class="form-wrap _w90 pt-2 PB-10 _emp_dep">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="_head-sm">Employee Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-01">
                                    <label class="custom-control-label" for="ar-01">Add Employee</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-02">
                                    <label class="custom-control-label" for="ar-02">View/Update Employee</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">POS Management </h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-03">
                                    <label class="custom-control-label" for="ar-03">POS Setup</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-04">
                                    <label class="custom-control-label" for="ar-04">Item Selling</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Inventory Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-05">
                                    <label class="custom-control-label" for="ar-05">View Warehouse Inventory</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-06">
                                    <label class="custom-control-label" for="ar-06">View Location Inventory</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-07">
                                    <label class="custom-control-label" for="ar-07">Handle Inventory Requests</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-08">
                                    <label class="custom-control-label" for="ar-08">Location Inventory Gallery</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-09">
                                    <label class="custom-control-label" for="ar-09">Warehouse Inventory Gallery</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-10">
                                    <label class="custom-control-label" for="ar-10">Inventory Tags</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-11">
                                    <label class="custom-control-label" for="ar-11">Centralized Pricing System</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-12">
                                    <label class="custom-control-label" for="ar-12">Centralized Delete Inventory</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Ecommerce Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-13">
                                    <label class="custom-control-label" for="ar-13">Order Confirmation</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-14">
                                    <label class="custom-control-label" for="ar-14">Order Completion</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-15">
                                    <label class="custom-control-label" for="ar-15"> Order Processing</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-16">
                                    <label class="custom-control-label" for="ar-16">Admin Panel</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-17">
                                    <label class="custom-control-label" for="ar-17">Update Main Categories</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-18">
                                    <label class="custom-control-label" for="ar-18">Update Sub Categories</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-19">
                                    <label class="custom-control-label" for="ar-19">Update product Categories</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Warehouse Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-20">
                                    <label class="custom-control-label" for="ar-20">Add Warehouse</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-21">
                                    <label class="custom-control-label" for="ar-21">Dispatch Items from warehouse</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-22">
                                    <label class="custom-control-label" for="ar-22">Add Warehouse Inventory</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-23">
                                    <label class="custom-control-label" for="ar-23">Update Warehouse Inventory</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Location & Warehouse Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-24">
                                    <label class="custom-control-label" for="ar-24">Add Area/Region</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-25">
                                    <label class="custom-control-label" for="ar-25">Update Warehouse</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-26">
                                    <label class="custom-control-label" for="ar-26">Company Structure</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Targets Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-27">
                                    <label class="custom-control-label" for="ar-27">Add Targets</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-28">
                                    <label class="custom-control-label" for="ar-28">Update Targets</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">CX Dashboard</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-29">
                                    <label class="custom-control-label" for="ar-29">Dashboard</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Complains Management</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-30">
                                    <label class="custom-control-label" for="ar-30">Complains Setup</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Misc</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-31">
                                    <label class="custom-control-label" for="ar-31">ARPU Setup</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Access Rights</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-32">
                                    <label class="custom-control-label" for="ar-32">Access Rights</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Loyalty Setup</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-33">
                                    <label class="custom-control-label" for="ar-33">Loyalty Setup</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="_head-sm">Customer Base</h3>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ar-34">
                                    <label class="custom-control-label" for="ar-34">Customer Base</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h2>Create User</h2>
                </div>
                <div class="body">
                    <div class="form-wrap _w100 p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Username*</label>
                                    <input type="text" value="{{ old('username') }}" name="username" class="form-control" required autofocus>

                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Password* <br> <small>(Minimum 6 alphanumeric characters)</small></label>
                                    <input type="password" value="{{ old('password') }}" name="password" class="form-control" required autofocus>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" style="display: block" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label mb-10">Confirm Password*</label>
                                    <input type="password" id="password-confirm" value="{{ old('password') }}" name="password_confirmation" class="form-control" required autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header">
                    <h2>Employee Picture</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group _upload-thumb pt-1">
                                <label for="img-thu" class="form__input form__input--file">
                                    <span id="placeholder-img-thu" class="icon icon-plus placeholder">Upload Thumbnail (250x250)</span>
                                    <input type="file" onchange="readURL(this);" type="file" name="picture" id="img-thu" class="hidden form__input form__input--file" accept=".gif, .jpg, .png">
                                </label>
                            </div>
                            <div class="p-2" align="center"><img id="imgthu" src="" alt="" /></div>

                            @if ($errors->has('img-thu'))
                                <span class="invalid-feedback" style="display: block" role="alert">
                                    <strong>{{ $errors->first('img-thu') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pt-5" align="center">
            <button type="submit" class="btn btn-cancel mr-2">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Employee</button>
        </div>
    </div>
</form>
@endsection
