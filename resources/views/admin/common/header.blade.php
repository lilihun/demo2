<header class="main-header">
    <meta charset="utf-8">
    <!-- Logo -->
    <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>De</b>mo</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Demo</b>框架</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">切换导航</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
{{--                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        --}}{{--<i class="fa fa-flag-o"></i>--}}{{--
                        <span class="label label-success"></span>
                    </a>
                </li>--}}
                {{--<!-- Notifications: style can be found in dropdown.less -->--}}
                {{--<li class="dropdown notifications-menu">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-bell-o"></i>--}}
                        {{--<span class="label label-warning"></span>--}}
                    {{--</a>--}}

                {{--</li>--}}
                {{--<!-- Tasks: style can be found in dropdown.less -->--}}
                {{--<li class="dropdown tasks-menu">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-flag-o"></i>--}}
                        {{--<span class="label label-danger"></span>--}}
                    {{--</a>--}}

                {{--</li>--}}
                <li class="dropdown user user-menu">
                    <!--a href="http://www.smjju.com" class="dropdown-toggle" target="_blank">
                      <span>商城首页</span>
                    </a-->
                    <a href="{{url('/wap')}}" class="dropdown-toggle" target="_blank">
                        <span>手机端首页</span>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <!--a href="http://www.smjju.com" class="dropdown-toggle" target="_blank">
                      <span>商城首页</span>
                    </a-->
                    <a href="{{ url("/") }}" class="dropdown-toggle" target="_blank">
                        <span>PC端首页</span>
                    </a>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <img src="{{asset('public/topshop/statics/images/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                        {{--<span class="hidden-xs">{{auth()->user()->uname}}</span>--}}
                        <span class="hidden-xs">{{ Session::get('user')->user_name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('public/topshop/statics/images/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                            <p>
                                {{ Session::get('user')->user_name}}
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
{{--                                <div class="col-xs-6 text-center">
                                    --}}{{--<a href="#">{{auth()->user()->email}}</a>--}}{{--
                                    <a href="#">test3</a>

                                </div>
                                <div class="col-xs-6 text-center">
                                    --}}{{--<a href="#">{{auth()->user()->name}}</a>--}}{{--
                                    <a href="">test4</a>
                                </div>--}}
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url("admin/editPwd/".Session::get('user')->user_id) }}" class="btn btn-success btn-flat">修改密码</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{url('admin/quit')}}" class="btn btn-success btn-flat">退出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                {{--<li>--}}
                    {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
                {{--</li>--}}
            </ul>
        </div>
    </nav>
</header>