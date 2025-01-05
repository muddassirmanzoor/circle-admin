@extends('layouts.login')
@section('content')
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="javascript:;">
        <img style="width: 120px;height: 12%;" src="{{asset('assets/img/logo-1.png')}}" alt="" /> </a>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="{{route('postAdminLogin')}}" method="post">
        <h3 class="form-title font-green" style="color: #17C4BB!important;">Admin Sign In</h3>
        @if(Session::has('success_message'))
        <div class="alert alert-success">
            <button class="close" data-close="alert"></button>
            <span> {{session::get('success_message')}} </span>
        </div>
        @endif
        @if(Session::has('error_message'))
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> {{session::get('error_message')}} </span>
        </div>
        @endif
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" placeholder="Username" name="email" value="{{ old('email') }}" /> </div>
        <div class="form-group">
            {{ csrf_field() }}
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" placeholder="Password" name="password" /> </div>
        <div class="form-actions">
            <button type="submit" class="btn green uppercase" style="background-color: #17C4BB!important;">Login</button>
        </div>
    </form>
    <!-- END LOGIN FORM -->
</div>
<div class="copyright" style="color: #ffffff!important;"> CIRCL. Admin Panel. </div>
@endsection