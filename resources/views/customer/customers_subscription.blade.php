<?php
$customer_subscriptions = $data['customer_subscriptions'];
?>
<h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">
    Subscription Listing
    <div class="actions pull-right hidden">
        <div class="btn-group">
            <a class="btn red btn-outline sbold" data-toggle="modal" id="addSubscription" href="#subscriptionModel"> Add
                Subscription </a>
        </div>
    </div>
</h3>
<table class="table table-striped table-bordered table-hover" id="subscriptionsTable">
    <thead>
        <tr>
            <th> # </th>
            <th> Freelancer Name</th>
            <th> Type </th>
            <th> Price</th>
            <th style="vertical-align: inherit;text-align: center;"> Subscription Date</th>
            <th style="vertical-align: inherit;text-align: center;"> Action </th>
        </tr>
    </thead>
    <tbody>
        @if ($customer_subscriptions)
            @foreach ($customer_subscriptions as $subscription)
                <tr>
                    <td style="vertical-align: inherit;"> {{ $subscription['id'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $subscription['freelancer_name'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $subscription['type'] }} </td>
                    <td style="vertical-align: inherit;"> {{ $subscription['price'] }} </td>
                    <td style="vertical-align: inherit;text-align: center;"> {{ $subscription['date'] }} </td>
                    <td style="vertical-align: inherit;text-align: center;">
                        <button type="button"
                            class="btn btn-sm green btn-outline filter-submit margin-bottom editCustomerSubscription"
                            data-uuid="{{ $subscription['subscription_uuid'] }}"> Edit </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $(function() {
        $("#subscriptionsTable").dataTable({
            "oLanguage": {
                "sEmptyTable": "No Subscription Found"
            }
        });
    })
</script>

<div class="modal fade" id="subscriptionModel" tabindex="-1" role="basic" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Subscription Setting</h4>
            </div>
            <div class="modal-body">
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
                    <form class="form-horizontal">
                        @csrf
                        <div class="form-body">
                            <input type="hidden" name="customer_uuid" id="customer_uuid"
                                value="{{ request()->route('id') }}">
                            <input type="hidden" name="subscription_uuid" id="subscription_uuid" value="">
                            <div class="form-group">
                                <label class="control-label col-md-3">Freelancers</label>
                                <div class="col-md-8">
                                    <?php
                                    $all_freelancers = $data['all_freelancers'];
                                    ?>
                                    <select class="form-control" name="freelancer_uuid" id="freelancer_uuid" required>
                                        <option value="">-- Select --</option>
                                        @if (count($all_freelancers) > 0)
                                            @foreach ($all_freelancers as $freelancer)
                                                <option value="{{ $freelancer['freelancer_uuid'] }}">
                                                    {{ $freelancer['freelancer_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Subscriptions</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="freelancers_subscriptions"
                                        id="freelancers_subscriptions" required>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="button" class="btn green" id="saveCustomerSubscription">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
