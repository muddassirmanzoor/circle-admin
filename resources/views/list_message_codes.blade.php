@extends('layouts.main')
@section('title')
    Message Codes
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase"> Message Codes </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="message_code_datatable">
                        <thead>
                            <tr>
                                <th> SR# </th>
                                <th> Phone # </th>
                                {{-- <th> Email </th> --}}
                                <th> Code </th>
                                <th> status </th>
                                {{-- <th> Type </th> --}}
                                <th> Date & Time </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($codes))
                                @foreach ($codes as $ind => $code)
                                    <tr>
                                        <td> {{ $ind + 1 }} </td>
                                        <td> {{ $code['phone_number'] ?? '' }} </td>
                                        {{-- <td> {{$code['email']}}</td> --}}
                                        <td> {{ $code['verification_code'] }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ config('arrays.phone_status.' . $code['status'] . '.color') }} text-bold-700">
                                                {{ config('arrays.phone_status.' . $code['status'] . '.text') }}
                                            </span>
                                        </td>
                                        {{-- <td> {{$code['type']}} </td> --}}
                                        <td> {{ $code['created_at'] }} </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $("#message_code_datatable").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Record found"
                }
            });
        })
    </script>

@endsection
