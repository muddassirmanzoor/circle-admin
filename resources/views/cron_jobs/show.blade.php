@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">Cronjob details</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-10">
                                    <h2>{{$cronJob->name}}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Result:</strong>  {!! $cronJob->getSuccessHTML() !!} </p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Date & Time:</strong> {{ $cronJob->created_at }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Log:</strong>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <pre>{{ ltrim($cronJob->message) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
