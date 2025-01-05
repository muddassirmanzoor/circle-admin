@extends('layouts.main')
@section('title') Services @endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">Services</span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided">
                        <a href="{{ route('showSubCategoryForm')  }}" class="btn purple">
                            <i class="fa fa-plus-circle"></i>
                            Add New Service
                        </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Industry Name </th>
                            <th> Service Image </th>
                            <th> Service Name </th>
                            <th> Service Status </th>
                            <th> Service Type </th>
                            <th> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sub_categories)
                        @foreach($sub_categories as $sub_category)
                        <tr>
                            <td style="vertical-align: inherit;"> {{ $loop->index + 1 }} </td>
                            <?php
                            $category_detail = $sub_category['category'];
                            $image = $sub_category['image'] ? config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['category_image'] . $sub_category['image'] : url('public/assets/images/no-image.png');
                            ?>
                            <td> {{ $category_detail['name'] }} </td>
                            <td class="text-center"> <img class="timeline-body-img" style="object-fit: cover; border-radius: 5px !important" width="100px" height="100px" src="{{ $image }}" alt="Image"> </td>
                            <td style="vertical-align: inherit;"> {{ $sub_category['name'] }} </td>
                            <td style="vertical-align: inherit;"> {{ ($sub_category['is_archive'] == 0) ? 'Active' : 'Inactive' }} </td>
                            <td style="vertical-align: inherit;"> {{ ($sub_category['is_online'] == 0) ? 'Face to Face' : 'Online' }} </td>
                            <td style="vertical-align: inherit;">
                                <a href="{{ route('editSubCategory', ['id' => $sub_category['sub_category_uuid']]) }}" class="btn btn-sm green btn-outline filter-submit margin-bottom"> Edit </a>
                                <button class="btn btn-sm red btn-outline filter-submit margin-bottom deleteSubCategory" data-uuid="<?= $sub_category['sub_category_uuid'] ?>" {{ ($sub_category['is_archive'] == 1) ? 'disabled' : '' }}> Delete </button>
                            </td>
                        </tr>
                        @endforeach
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
                <h4 class="modal-title">Delete Service</h4>
            </div>
            <div class="modal-body">
                <p> Do you want to delete this Service? </p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline dark">No</button>
                <button type="button" data-dismiss="modal" class="btn green" id="yes_delete_sub_category">Yes</button>
            </div>
        </div>
    </div>
</div>
@endsection
