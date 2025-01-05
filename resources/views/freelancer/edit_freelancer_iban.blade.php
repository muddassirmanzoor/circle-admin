@extends('layouts.main')
@section('content')
    <?php
    $freelancer_bank_detail = $freelancer_detail['bank_detail'] ?? [];
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Freelancer Detail </span>
                    </div>
                    <a href="{{ route('getAllFreelancersWithIban') }}" class="pull-right" style="margin-top: 7px;"> back to list </a>
                </div>
                <div class="portlet-body form">
                    <?php if( \Session::has('success_message')): ?>
                    <div class="alert alert-success">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo e(\session::get('success_message')); ?> </span>
                    </div>
                    <?php endif; ?>
                    <?php if( \Session::has('error_message')): ?>
                    <div class="alert alert-danger">
                        <button class="close" data-close="alert"></button>
                        <span> <?php echo e(\session::get('error_message')); ?> </span>
                    </div>
                    <?php endif; ?>
                    <div class="form-body">
                        <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Person Info</h3>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">First Name:</label>
                                    <div class="col-md-8">
                                        <strong> <a href="{{route('freelancerDetailPage', $freelancer_detail['freelancer_uuid'])}}">{{$freelancer_detail['first_name'] ?? ''}}</a></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Last Name:</label>
                                    <div class="col-md-8">
                                        <strong>{{$freelancer_detail['last_name'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email:</label>
                                    <div class="col-md-8">
                                        <strong> {{$freelancer_detail['email'] ?? ''}} </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Phone:</label>
                                    <div class="col-md-8">
                                        <strong>{{$freelancer_detail['phone'] ?? ''}}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Profession:</label>
                                    <div class="col-md-8">
                                        <strong> {{$freelancer_detail['profession'] ?? ''}} </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('updateFreelancerBankInfo') }}" class="form-horizontal" method="post">
                        @csrf
                        <input type="hidden" name="freelancer_uuid" value="{{$freelancer_detail['freelancer_uuid']}}">
                        <div class="form-body">
                            <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">Bank Info</h3>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Account Name</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" readonly placeholder="Account Name" value="{{ $freelancer_bank_detail['account_name'] ?? '' }}">
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Sort Code</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" readonly placeholder="Sort Code" value="{{ $freelancer_bank_detail['sort_code'] ?? '' }}">
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
                                            <input type="text" class="form-control" readonly placeholder="Account Number" value="{{ $freelancer_bank_detail['account_number'] ?? '' }}">
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Account Iban</label>
                                        <div class="col-md-9">
                                            <input required type="text" class="form-control" name="iban_account_number" placeholder="Account Iban" value="{{ $freelancer_bank_detail['iban_account_number'] ?? '' }}">
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
                                            <a href="{{ route('getAllFreelancersWithIban') }}" type="button" class="btn default">Cancel</a>
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
