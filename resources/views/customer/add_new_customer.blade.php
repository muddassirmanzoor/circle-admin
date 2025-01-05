@extends('layouts.main')
@section('title') Add New Customer @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-th-list"></i>Add New Customer
                    </div>
                </div>
                <div class="portlet-body form">
                    @if( \Session::has('success_message'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('success_message') }} </span>
                        </div>
                    @endif
                    @if( \Session::has('error_message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ \session::get('error_message') }} </span>
                        </div>
                    @endif
                    <form action="{{ route('createCustomerByAdmin') }}" class="form-horizontal" method="post">
                        @csrf
                        <div class="form-body">
                            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Person Info</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">First Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="first_name" placeholder="First name" value="{{old('first_name')}}" required>
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Last Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="last_name" placeholder="Last name" value="{{old('last_name')}}" required>
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Gender</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Email</label>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email')}}" required>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Country Name</label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="country_name" id="country_name" value="{{old('country_name')}}" autocomplete="off" placeholder="Country Name">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Country Code</label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" name="country_code" id="country_code" value="{{old('country_code')}}" autocomplete="off" placeholder="Country Code">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Phone</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="phone_number" placeholder="Phone" value="{{old('phone_number')}}" required>
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Dob</label>
                                        <div class="col-md-9">
                                            <input type="date" class="form-control" name="dob" placeholder="dob" value="{{old('dob')}}" required>
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Password</label>
                                        <div class="col-md-6">
                                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="password" placeholder="Password" name="password" aria-autocomplete="list" required>
                                            <span class="help-block"> </span>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mt-checkbox mt-checkbox-outline">
                                                <input type="checkbox" id="showCustomerPassword"> show Password
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Password Confirmation</label>
                                        <div class="col-md-9">
                                            <input class="form-control placeholder-no-fix" type="password" name="password_confirmation" id="password_confirmation" autocomplete="off" placeholder="Re-type Your Password">
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green">Submit</button>
                                            <a href="{{ route('getAllCustomers') }}" class="btn red">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
