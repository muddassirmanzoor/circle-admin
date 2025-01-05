@extends('layouts.main')
@section('title') Industries @endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="glyphicon glyphicon-th font-dark"></i>
                    <span class="caption-subject bold uppercase">Industries</span>
                </div>
                <div class="actions">
                    <div class="btn-group btn-group-devided">
                        <a href="{{ route('showCategoryForm') }}" class="btn purple">
                            <i class="fa fa-plus-circle"></i>
                            Add New Industry
                        </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Image </th>
                            <th> Name </th>
                            <th> Status </th>
                            <th> Customer Description </th>
                            <th> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($categories)
                        @foreach($categories as $category)
                        <tr>
                            <td style="vertical-align: inherit;"> {{ $loop->index + 1 }} </td>
                            <?php
                                $image = $category['image'] ? config('paths.s3_cdn_base_url') . \App\Helpers\CommonHelper::$s3_image_paths['category_image'] . $category['image'] : url('public/assets/images/no-image.png');
                            ?>
                            <td class="text-center"> <img class="timeline-body-img" style="object-fit: cover; border-radius: 5px !important" width="100px" height="100px" src="{{ $image }}" alt="Image"> </td>
                            <td style="vertical-align: inherit;"> {{ $category['name'] }} </td>
                            <td style="vertical-align: inherit;"> {{ ($category['is_archive'] == 0) ? 'Active' : 'Inactive' }} </td>
                            <td width='350' style="vertical-align: inherit;"> {{$category['customer_description'] ?? ''}} </td>
                            <td style="vertical-align: inherit;">
                                <a href="{{ route('editCategory', ['id' => $category['category_uuid']]) }}" class="btn btn-sm green btn-outline filter-submit margin-bottom"> Edit </a>
                                <button class="btn btn-sm red btn-outline filter-submit margin-bottom deleteCategory" data-uuid="<?= $category['category_uuid'] ?>" {{ ($category['is_archive'] == 1) ? 'disabled' : '' }}> Delete </button>
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
