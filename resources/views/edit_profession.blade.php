@extends('layouts.main')
@section('title') Edit Profession @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-red">
                        <i class="glyphicon glyphicon-th font-red"></i>
                        <span class="caption-subject bold uppercase"> Edit Profession</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    @if (Session::has('success_message'))
                        <div class="alert alert-success">
                            <button class="close" data-close="alert"></button>
                            <span> {{ session::get('success_message') }} </span>
                        </div>
                    @endif
                    @if (Session::has('error_message'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ session::get('error_message') }} </span>
                        </div>
                    @endif
                    <form role="form" method="post" enctype="multipart/form-data" class="form-horizontal"
                        action="{{ route('updateProfession') }}">
                        @csrf
                        <input type="hidden" name="profession_uuid" value=" {{ $profession['profession_uuid'] }}" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="professionName">Profession Name</label>
                                <div class="col-md-4">
                                    <input class="form-control spinner" id="name" name="name" type="text" placeholder=""
                                        value="{{ isset($profession['name']) ? $profession['name'] : '' }}" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-center">
                            <button type="submit" class="btn blue">Submit</button>
                            <a href="{{ route('getAllProfessions') }}" class="btn red cancelClick">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
