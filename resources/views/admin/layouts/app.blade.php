<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
       @yield('title')
    </title>
    <!-- common styles -->
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/theme.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/AdminLTE.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/ionicons.css')}}">
    <!-- plugins -->
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-validator.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-daterangepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/editor/summernote.css')}}">

    <link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/layer.css')}}">
    <!-- 备用 -->
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/AdminLTE.min.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-colorpicker.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/bootstrap-timepicker.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/decorat.min.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/decorate.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/decorate.min.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/plugins.min.css')}}">--}}
{{--<link rel="stylesheet" href="{{ asset('public/topshop/statics/stylesheets/font-awesome.css')}}">--}}
    <!-- common scripts -->
    <script src="{{asset('public/topshop/statics/scripts/lib/jquery.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/bootstrap.js')}}"></script>
    <!-- plugins -->
    <script src="{{asset('public/topshop/statics/scripts/lib/bootstrap-modal.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/bootstrap-validator.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/moment.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/daterangepicker.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/datepicker.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/bootstrap-datetimepicker.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/bootstrap-switch.min.js')}}"></script>
    <!-- input-mask -->
    <script src="{{asset('public/topshop/statics/scripts/lib/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/jquery.flot.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/jquery.flot.time.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/jquery.nestable.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/template.min.js')}}"></script>
    <!-- editor -->
    <script src="{{asset('public/topshop/statics/scripts/lib/editor/summernote.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/lib/editor/summernote-zh-CN.js')}}"></script>
    <!-- main scripts -->
    <script src="{{asset('public/topshop/statics/scripts/tools.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/imageupload.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/goodschoose.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/app.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/dragging.js')}}"></script>
    <!-- 新增 -->
    <script src="{{asset('public/topshop/statics/scripts/layer.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/jquery-catselect.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/jquery.form.js')}}"></script>
    <script src="{{asset('public/topshop/statics/scripts/jquery.jsonlist.min.js')}}"></script>
    {{--上传图片封装JS--}}
    <script src="{{asset('public/js/common.js')}}"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Header -->
@include('admin.common.header')

<!-- Sidebar -->
@include('admin.common.sidebar')


<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
{{--                {{ $page_title or "Page Title" }}
                <small>{{ $page_description or null }}</small>--}}
                @yield('content-header')
            </h1>
            <!-- You can dynamically generate breadcrumbs here -->
{{--            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>--}}
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Footer -->
@include('admin.common.footer')

<!-- Control Sidebar -->
{{--    <aside class="control-sidebar control-sidebar-dark">

        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">

            </div>
            <!-- /.tab-pane -->

            <!-- /.tab-pane -->
        </div>
    </aside>--}}
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    {{--<div class="control-sidebar-bg"></div>--}}
</div>
<!-- ./wrapper -->
</body>
</html>