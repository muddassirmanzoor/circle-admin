@extends('layouts.main')
@section('title') Add New Freelancer @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="glyphicon glyphicon-th-list"></i>Add New Freelancer
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
                    <form action="{{ route('createFreelancerByAdmin') }}" class="form-horizontal" method="post">
                        @csrf
                        <div class="form-body">
                            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Person Info</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">First Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="first_name" placeholder="First name" value="">
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Last Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="last_name" placeholder="Last name" value="">
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Email</label>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" name="email" placeholder="Email" value="">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Phone</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control phone_number" name="phone_number" placeholder="Phone" value="" required>
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Country Name</label>
                                        <div class="col-md-9">
                                            <input class="form-control placeholder-no-fix" type="text" name="country_name" id="country_name" autocomplete="off" placeholder="Country Name">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Country Code</label>
                                        <div class="col-md-9">
                                            <input class="form-control placeholder-no-fix" type="text" name="country_code" id="country_code" autocomplete="off" placeholder="Country Code">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Profession</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="profession" id="profession">
                                                <option value="">-- Select --</option>
                                                    @foreach($all_professions as $profession)
                                                        <option value="{{ $profession['name'] }}">{{ $profession['name'] }}</option>
                                                    @endforeach

                                            </select>
                                            <!-- <input type="text" class="form-control" name="profession" placeholder="Profession" value=""> -->
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Company</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="company" placeholder="Company" value="">
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
                                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="password" placeholder="Password" name="password" aria-autocomplete="list">
                                            <span class="help-block"> </span>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="mt-checkbox mt-checkbox-outline">
                                                <input type="checkbox" id="showFreelancerPassword"> show Password
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Gender</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="gender">
                                                <option value="male" >Male</option>
                                                <option value="female" >Female</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Primary location</label>
                                        <div class="col-md-9">
                                            <input id="autocomplete" class="form-control controls" type="text" placeholder="Search Box">
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
                                            <button disabled type="submit" class="btn green">Submit</button>
                                            <button type="button" class="btn default">Cancel</button>
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
