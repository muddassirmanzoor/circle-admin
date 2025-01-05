@extends('layouts.main')
@section('title') {{ $title }} Customers @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-th font-dark"></i>
                        <span class="caption-subject bold uppercase">{{ $title }} Customers</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="customers">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th> Name</th>
                            <th> Email</th>
                            <th> Phone</th>
                            <th> DOB</th>
                            <th> Gender</th>
                            <th> Created at</th>
                            <th> Status</th>
                            <th> Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($customers)
                            @foreach($customers as $customer)
                                <tr>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ $customer['id'] }} </td>
                                    <td style="vertical-align: inherit;cursor: pointer;"><a
                                            href="{{ route('customerDetailPage', ['uuid' => $customer['customer_uuid']]) }}">{{ $customer['first_name'] }}</a>
                                    </td>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ $customer['email'] }} </td>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ $customer['phone_number'] }} </td>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ \App\Helpers\CommonHelper::setUserDateFormat($customer['dob']) }} </td>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ $customer['gender'] }} </td>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ \App\Helpers\CommonHelper::setUserDateFormat($customer['created_at']) }} </td>
                                <?php
                                    $status = '';
                                    $active_btn = '';
                                    $block_btn = '';
                                    $delete_btn = '';
                                    if ($customer['is_verified'] == 0 && $customer['is_archive'] == 0) {
                                        $status = "Not Verified";
                                        $active_btn = true;
                                        $block_btn = false;
                                        $delete_btn = true;
                                    } elseif ($customer['is_verified'] == 0 && $customer['is_archive'] == 1) {
                                        $status = "Not Verified and Deleted";
                                        $active_btn = true;
                                        $block_btn = false;
                                        $delete_btn = false;
                                    }if ($customer['is_verified'] == 1 && $customer['is_archive'] == 0) {
                                        if ($customer['is_active'] == 1) {
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
                                    }if ($customer['is_verified'] == 1 && $customer['is_archive'] == 1) {
                                        $status = "Deleted";
                                        $active_btn = true;
                                        $block_btn = false;
                                        $delete_btn = false;
                                    }
                                    ?>
                                    <td style="vertical-align: inherit;cursor: pointer;"> {{ $status }} </td>
                                    <td width="12%">
                                        <div class="btn-group">
                                            @if($active_btn || $block_btn)
                                                <button class="btn btn-xs green dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false"> Actions
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                            @endif

                                            @if($delete_btn)&nbsp;&nbsp;
                                            <button class="btn btn-xs red deleteCustomer"
                                                    data-uuid="{{ $customer['customer_uuid'] }}" type="button"> Delete
                                            </button>
                                            @endif
                                            <ul class="dropdown-menu pull-left" role="menu">
                                                @if($active_btn)
                                                    <li>
                                                        <a href="javascript:void(0);" class="updateCustomer"
                                                           data-uuid="<?= $customer['customer_uuid'] ?>" data-value="1">Active</a>
                                                    </li>
                                                @endif
                                                @if($block_btn)
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="updateCustomer"
                                                           data-uuid="<?= $customer['customer_uuid'] ?>"
                                                           data-value="0">Block </a>
                                                    </li>
                                                @endif
                                            </ul>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="odd">
                                <td valign="top" colspan="9" class="dataTables_empty">No records found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="static2" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Customer</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to delete this Customer? </p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                    <button type="button" data-dismiss="modal" class="btn green" id="yes_delete_customer">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $("#customers").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Record Found"
                }
            });
        })
    </script>
@endsection
