<?php
$freelancer_detail = $data['freelancer_detail'];
$all_professions = $data['all_professions'];
?>
<div class="portlet-body form">
    @if (\Session::has('success_message'))
        <div class="alert alert-success">
            <button class="close" data-close="alert"></button>
            <span> {{ \session::get('success_message') }} </span>
        </div>
    @endif
    @if (\Session::has('error_message'))
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> {{ \session::get('error_message') }} </span>
        </div>
    @endif
    <form action="{{ route('updateFreelancerProfileByAdmin') }}" class="form-horizontal" method="post">
        @csrf
        <div class="form-body">
            <input type="hidden" name="freelancer_uuid" id="freelancer_uuid"
                value="{{ $freelancer_detail['freelancer_uuid'] }}">
            <input type="hidden" name="profession_id" id="profession_id"
                value="{{ $freelancer_detail['profession_id'] }}">
            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Person Info</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">First Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="first_name" placeholder="First name"
                                value="{{ $freelancer_detail['first_name'] }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Last Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="last_name" placeholder="Last name"
                                value="{{ $freelancer_detail['last_name'] }}">
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
                                <option value="male" {{ $freelancer_detail['gender'] == 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female"
                                    {{ $freelancer_detail['gender'] == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Phone</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control phone_number" name="phone_number" placeholder="Phone"
                                value="{{ $freelancer_detail['phone'] }}" required>
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
                            <input class="form-control" type="text" name="country_name" id="country_name"
                                value="{{ $freelancer_detail['country_name'] }}" placeholder="">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Country Code</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="country_code" id="country_code"
                                value="{{ $freelancer_detail['country_code'] }}" placeholder="">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Email</label>
                        <div class="col-md-9">
                            <input type="email" class="form-control" name="email" placeholder="Email"
                                value="{{ $freelancer_detail['email'] }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Company</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="company" placeholder="Company"
                                value="{{ $freelancer_detail['company'] }}">
                            <span class="help-block"> </span>
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
                                <?php
                                if (count($all_professions) > 0){
                                    foreach ($all_professions as $profession){

                                        $selected = '';
                                        if (isset($freelancer_detail['profession_id']) && $profession['id'] == $freelancer_detail['profession_id']) {
                                            $selected = 'selected';
                                        }?>
                                        <option value="{{ $profession['name'] }}" {{ $selected }}>
                                            {{ $profession['name'] }}</option>
                                <?php
                                    }
                                }
                                 ?>

                            </select>
                            <!-- <input type="text" class="form-control" name="profession" placeholder="Profession" value="{{ $freelancer_detail['profession'] }}"> -->
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Bank Info</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Account Name</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control" placeholder="Account Name"
                                value="{{ $freelancer_bank_detail['account_name'] ?? '' }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Sort Code</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control" placeholder="Sort Code"
                                value="{{ $freelancer_bank_detail['sort_code'] ?? '' }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Account Number</label>
                        <div class="col-md-9">
                            <input type="text" readonly class="form-control" placeholder="Account Number"
                                value="{{ $freelancer_bank_detail['account_number'] ?? '' }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Account Iban</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="bankDetail[iban_account_number]"
                                placeholder="Account Iban"
                                value="{{ $freelancer_bank_detail['iban_account_number'] ?? '' }}">
                            <span class="help-block"> </span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row"> --}}
            {{-- <div class="col-md-6"> --}}
            {{-- <div class="form-group"> --}}
            {{-- <label class="control-label col-md-3">Primary Location</label> --}}
            {{-- <div class="col-md-9"> --}}

            {{-- </div> --}}
            {{-- </div> --}}
            {{-- </div> --}}
            {{-- <div class="col-md-6"> --}}
            {{-- <div class="form-group"> --}}
            {{-- <label class="control-label col-md-3">Secondary Location</label> --}}
            {{-- <div class="col-md-9"> --}}

            {{-- </div> --}}
            {{-- </div> --}}
            {{-- </div> --}}
            {{-- </div> --}}
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">Submit</button>
                            <a href="{{ route('getAllFreelancers') }}" class="btn red">Cancel</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> </div>
            </div>
        </div>
    </form>
</div>
