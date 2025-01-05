<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | CIRCL</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
        type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}"
        rel="stylesheet" type="text/css" />

    <link href="{{url('assets/admin/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}"
        rel="stylesheet" type="text/css" />
    {{-- <link href="{{url('assets/admin/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{url('assets/admin/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css" /> --}}

    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">

    <link href="{{url('assets/admin/assets/layouts/layout2/css/custom.min.css')}}" rel="stylesheet"
        type="text/css" />



    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'
        integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/morris/morris.css')}}" rel="stylesheet"
        type="text/css" />
    <!-- Fullcalendar CSS -->
    <link href="{{url('assets/plugins/fullcalendar/packages/core/main.css')}}" rel='stylesheet' />
    <link href="{{url('assets/plugins/fullcalendar/packages/daygrid/main.css')}}" rel='stylesheet' />
    <link href="{{url('assets/plugins/fullcalendar/packages/timegrid/main.css')}}" rel='stylesheet' />
    <link href="{{url('assets/plugins/fullcalendar/packages/list/main.css')}}" rel='stylesheet' />
    {{-- <link href="{{url('assets/admin/assets/global/plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{url('assets/admin/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/datatables/datatables.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/fancybox/source/jquery.fancybox.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/select2/css/select2.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/clockface/css/clockface.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/bootstrap-toastr/toastr.min.css')}}"
        rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{url('assets/admin/assets/global/css/components.min.css')}}" rel="stylesheet"
        id="style_components" type="text/css" />
    <link href="{{url('assets/admin/assets/global/css/plugins.min.css')}}" rel="stylesheet"
        type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{url('assets/admin/assets/layouts/layout2/css/layout.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{url('assets/admin/assets/pages/css/profile.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{url('assets/admin/assets/layouts/layout2/css/themes/blue.min.css')}}" rel="stylesheet"
        type="text/css" id="style_color" />
    <link href="{{url('assets/admin/assets/layouts/layout2/css/custom.min.css')}}" rel="stylesheet"
        type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link
        href="{{url('assets/admin/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/clockface/css/clockface.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/jcrop/css/jquery.Jcrop.min.css')}}"
        rel="stylesheet" type="text/css" />
    <link href="{{url('assets/admin/assets/global/plugins/jcrop/css/jquery.Jcrop.min.css')}}"
        rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <link rel="shortcut icon" href="{{url('assets/img/favicon.ico')}}" />


    <!-- cropper CSS -->
    <link href="{{url('assets/css/confirm.min.css')}}" rel="stylesheet">
    <link href="{{url('assets/css/apprise.min.css')}}" rel="stylesheet" type="text/css" />
    <link href='{{url('assets/admin/assets/global/fonts/googleapi.css')}}' rel='stylesheet' type='text/css'>
    <script src="{{url('assets/admin/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    <link href="{{url('assets/css/custom.css?v=1.0.1')}}" rel="stylesheet">
    <link href="{{url('assets/admin/assets/global/css/sweetalert2.css')}}" rel="stylesheet">
    <style>
        * {
            scroll-behavior: smooth
        }

    </style>
</head>
<!-- END HEAD -->
<script type="text/javascript">
    $(function() {
        siteUrl = '<?php $env = \App::environment() === 'production' ? 'prod' : 'dev'; echo URL::to('/'). '/' . $env; ?>/';
        s3Url = '<?php echo AdminCommonHelper::$images_CDN; ?>';
        itemSlug = '<?php echo AdminCommonHelper::$s3_images_slug; ?>';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });
</script>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
    <div class="ajaxLoader" style="display: none!important;">
        <img src="{{asset('assets/img/ajax-loader.gif') }}">
    </div>
    <!--ajaxLoader-->
