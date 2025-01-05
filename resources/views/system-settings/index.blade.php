@extends('layouts.main')
@section('title')
    System Settings
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="glyphicon glyphicon-cog font-dark"></i>
                        <span class="caption-subject bold uppercase">System Settings</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-right"> Vat </th>
                                <th class="text-right"> Transaction Charges </th>
                                <th class="text-center"> Schedule Duration </th>
                                <th class="text-center"> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($setting)
                                <tr>
                                    <td class="text-right" style="vertical-align: middle">{{ $setting['vat'] . ' %' ?? 'N-A' }} </td>
                                    <td class="text-right" style="vertical-align: middle">
                                        {{ $setting['transaction_charges'] . ' %' ?? 'N-A' }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        {{ config('arrays.withdraw_schedule.' . $setting['withdraw_scheduled_duration']) ?? 'N-A' }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('editCategory', ['id' => $setting['system_setting_uuid']]) }}"
                                            class="btn btn-sm green btn-outline filter-submit margin-bottom" disabled> Edit
                                        </a>
                                        <button
                                            class="btn btn-sm red btn-outline filter-submit margin-bottom deleteCategory"
                                            data-uuid="<?= $setting['system_setting_uuid'] ?>"
                                            {{ $setting['is_active'] == 1 ? 'disabled' : '' }}> Delete </button>
                                    </td>
                                </tr>
                            @else
                                <tr class="odd">
                                    <td valign="top" colspan="5" class="dataTables_empty">No records found</td>
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
                    <h4 class="modal-title">Delete Industry</h4>
                </div>
                <div class="modal-body">
                    <p> Do you want to delete this Industry? </p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                    <button type="button" data-dismiss="modal" class="btn green" id="yes_delete_category">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
