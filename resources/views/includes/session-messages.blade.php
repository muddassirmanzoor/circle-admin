@if (Session::has('success_message'))
    <div class="alert alert-success">
        <button class="close" data-close="alert"></button>
        <strong>Well done!</strong>
        <span> {{ session::get('success_message') }} </span>
    </div>
@endif
@if (Session::has('error_message'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <strong>Error!</strong>
        <span> {{ session::get('error_message') }} </span>
    </div>
@endif
<div class="alert alert-success ajax-alert-suucess" style="display: none" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Well done!</strong>
    <span class="alert-text"></span>
</div>
<div class="alert alert-danger ajax-alert-error" style="display: none" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Error!</strong>
    <span class="alert-text"></span>
</div>
