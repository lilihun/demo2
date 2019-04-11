<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left info" style="position:static;">
                <p>DEMO</p>
                <a href="#"><i class="fa fa-circle text-success"></i>管理员</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">菜单</li>
            <li class="treeview">
                <a href="{{ url('admin') }}">
                    <i class="fa fa-credit-card"></i> <span>首页</span>
                </a>
            </li>

            <li class="treeview" id="_demo_guanli">
                <a href="{{ url("admin/demo/create") }}">
                    <i class="fa fa-cube"></i> <span>模板</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
{{--模板列表--}}
                <ul class="treeview-menu">
                    <li id="_demo"  class="treeview" >
                        <a href="{{ url('admin/demo/index') }}"><i class="fa fa-circle-o"></i>模板列表</a>
                    </li>
                </ul>
        </ul>
    </section>

</aside>
<script type="text/javascript">
    var server = "{{ $_SERVER['REQUEST_URI'] }}";
/********************************demo管理***************************************/
//【demo管理】js判断
    var demo_index = '/admin/demo';
    if(server.indexOf(demo_index)!= -1){
        $("#_demo").addClass('active');
        $("#_demo >a>i").addClass('text-aqua');
        $("#_demo_guanli").addClass('active');
    }
</script>
