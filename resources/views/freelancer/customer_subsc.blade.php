<?php
$freelancer_detail = $data['freelancer_detail'];
$freelancer_subsciptions = $data['freelancer_subsciptions'];
?>

<div class="portlet-body">
    <br>
    <h3 class="form-section" style="margin-top:0 !important; margin-bottom: 20px !important;">
        Subscription Listing
        <div class="actions pull-right">
            <div class="btn-group">
                <a class="btn red btn-outline sbold" data-toggle="modal" id="addSubscription" href="#subscriptionModel"> Add Subscription </a>
            </div>
        </div>
    </h3>

    <table class="table table-striped table-bordered table-hover" id="subScriptionsTable">
        <thead>
            <tr>
                <th> # </th>
                <th> Type</th>
                <th> Price </th>
                <th style="text-align: center"> Action </th>
            </tr>
        </thead>
        <tbody>
            @if($freelancer_subsciptions)
            @foreach($freelancer_subsciptions as $subsciption)
            <tr>
                <td style="vertical-align: inherit;"> {{ $subsciption['id'] }} </td>
                <td style="vertical-align: inherit;"> {{ $subsciption['type'] }} </td>
                <td style="vertical-align: inherit;"> {{ $subsciption['price'] }} </td>
                <td style="vertical-align: inherit;text-align: center">
                    <button type="button" class="btn btn-sm green btn-outline filter-submit margin-bottom editSubscription" data-uuid="{{ $subsciption['subscription_uuid'] }}" > Edit </button>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    <script>
        $(function () {
            $("#subScriptionsTable").dataTable({
                "oLanguage": {
                    "sEmptyTable": "No Subscription found"
                }
            });
        })
    </script>
</div>
<div class="modal fade" id="subscriptionModel" tabindex="-1" role="basic" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Subscription Setting</h4>
            </div>
            <div class="modal-body">
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
                    <form class="form-horizontal">
                        @csrf
                        <div class="form-body">
                            <input type="hidden" name="freelancer_uuid" id="freelancer_uuid" value="{{ request()->route('id') }}">
                            <input type="hidden" name="subscription_uuid" id="subscription_uuid" value="" required>
                            <div class="form-group">
                                <label class="control-label col-md-3">Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="">-- Select --</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="annual">Annual</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Price</label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control"  step="0.01" id="price" name="price" placeholder="Price" value="" required>
                                    <span class="help-block"> </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="button" class="btn green" id="saveSubscription">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
