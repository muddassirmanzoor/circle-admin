<?php
$customer_detail = $data['customer_detail'];
?>
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
    <form action="{{ route('updateCustomerProfileByAdmin') }}" class="form-horizontal" method="post">
        @csrf
        <div class="form-body">
            <input type="hidden" name="customer_uuid" id="customer_uuid" value="{{ $customer_detail['customer_uuid'] }}">
            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Person Info</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">First Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="first_name" placeholder="First name" value="{{ $customer_detail['first_name'] }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Last Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="last_name" placeholder="Last name" value="{{ $customer_detail['last_name'] }}">
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
                                <option value="male" {{ ($customer_detail['gender'] == 'male') ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ ($customer_detail['gender'] == 'female') ? 'selected' : '' }}>Female</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Email</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="email" placeholder="Email" value="{{ $customer_detail['email'] }}">
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
                            <input class="form-control" type="text" name="country_name" id="country_name" value="{{ $customer_detail['country_name'] }}" placeholder="">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Country Code</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="country_code" id="country_code" value="{{ $customer_detail['country_code'] }}" placeholder="">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Phone</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="phone_number" placeholder="Phone" value="{{ $customer_detail['phone'] }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
                <!--/span-->
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
