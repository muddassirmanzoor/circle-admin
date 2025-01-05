@extends('layouts.main')
@section('title') Freelancers IBAN Information @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">Freelancers IBAN Information</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#home"  data-toggle="pill">Freelancers with IBAN ({{count($freelancers['with_iban'])}})</a></li>
                        <li><a href="#menu1" data-toggle="pill">Freelancers don't have IBAN ({{count($freelancers['no_iban'])}})</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <table class="table table-striped table-bordered table-hover freelancer-datatable-iban">
                                <thead>
                                <tr>
                                    <th> #</th>
                                    <th> Name</th>
                                    <th> Email</th>
                                    <th> Phone</th>
                                    <th> Gender</th>
                                    <th> Profession</th>
                                    <th> Company</th>
                                    <th> Status</th>
                                    {{--<th> Actions</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @if($freelancers['with_iban'])
                                    @foreach($freelancers['with_iban'] as $freelancer)
                                        <tr>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['id'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;">
                                                <a
                                                        href="{{ route('freelancerDetailPage', ['uuid' => $freelancer['freelancer_uuid']]) }}">{{ $freelancer['first_name'] }}</a>
                                            </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['email'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['phone_number'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['gender'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['profession'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['company'] }} </td>

                                            <?php
                                            $status = '';
                                            $active_btn = '';
                                            $block_btn = '';
                                            $delete_btn = '';
                                            if ($freelancer['is_verified'] == 0 && $freelancer['is_archive'] == 0) {
                                                $status = "Not Verified";
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = true;
                                            } elseif ($freelancer['is_verified'] == 0 && $freelancer['is_archive'] == 1) {
                                                $status = "Not Verified and Deleted";
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = false;
                                            }if ($freelancer['is_verified'] == 1 && $freelancer['is_archive'] == 0) {
                                                if ($freelancer['is_active'] == 1) {
                                                    $status = 'Active';
                                                    $active_btn = false;
                                                    $block_btn = true;
                                                    $delete_btn = true;
                                                } else {
                                                    $active_btn = true;
                                                    $block_btn = false;
                                                    $delete_btn = true;
                                                    $status = 'Blocked';
                                                }
                                            }if ($freelancer['is_verified'] == 1 && $freelancer['is_archive'] == 1) {
                                                $status = "Deleted";
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = false;
                                            }
                                            ?>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $status }} </td>
{{--                                            <td width="6%">--}}
{{--                                                <div class="btn-group">&nbsp;--}}
{{--                                                    <button class="btn btn-xs red deleteFreelancer"--}}
{{--                                                            data-uuid="{{ $freelancer['freelancer_uuid'] }}" type="button">--}}
{{--                                                        Add Iban--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <table class="table table-striped table-bordered table-hover freelancer-datatable-iban">
                                <thead>
                                <tr>
                                    <th> #</th>
                                    <th> Name</th>
                                    <th> Email</th>
                                    <th> Phone</th>
                                    <th> Gender</th>
                                    <th> Profession</th>
                                    <th> Company</th>
                                    <th> Status</th>
                                    <th> Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($freelancers['no_iban'])
                                    @foreach($freelancers['no_iban'] as $freelancer)
                                        <tr>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['id'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;">
                                                <a
                                                        href="{{ route('freelancerDetailPage', ['uuid' => $freelancer['freelancer_uuid']]) }}">
                                                        {{ $freelancer['first_name'] }}</a>
                                            </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['email'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['phone_number'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['gender'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['profession'] }} </td>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $freelancer['company'] }} </td>

                                            <?php
                                            $status = '';
                                            $active_btn = '';
                                            $block_btn = '';
                                            $delete_btn = '';
                                            if ($freelancer['is_verified'] == 0 && $freelancer['is_archive'] == 0) {
                                                $status = "Not Verified";
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = true;
                                            } elseif ($freelancer['is_verified'] == 0 && $freelancer['is_archive'] == 1) {
                                                $status = "Not Verified and Deleted";
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = false;
                                            }if ($freelancer['is_verified'] == 1 && $freelancer['is_archive'] == 0) {
                                                if ($freelancer['is_active'] == 1) {
                                                    $status = 'Active';
                                                    $active_btn = false;
                                                    $block_btn = true;
                                                    $delete_btn = true;
                                                } else {
                                                    $active_btn = true;
                                                    $block_btn = false;
                                                    $delete_btn = true;
                                                    $status = 'Blocked';
                                                }
                                            }if ($freelancer['is_verified'] == 1 && $freelancer['is_archive'] == 1) {
                                                $status = "Deleted";
                                                $active_btn = true;
                                                $block_btn = false;
                                                $delete_btn = false;
                                            }
                                            ?>
                                            <td style="vertical-align: inherit;cursor: pointer;"> {{ $status }} </td>
                                            <td width="6%">
                                                <div class="btn-group">&nbsp;
                                                    <a class="btn btn-xs green deleteFreelancer" href="{{route('editFreelancerIbanInfo', ['uuid' => $freelancer['freelancer_uuid']])}}">
                                                        Add Iban
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $(".freelancer-datatable-iban").dataTable({
                "pageLength": 25,
                "oLanguage": {
                    "sEmptyTable": "No Record Found"
                }
            });
        })
    </script>

@endsection
