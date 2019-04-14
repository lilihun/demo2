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

            @php
            $gisid=session('user.gid');
            if ($gisid !=0){
            $Autorlist = DB::table('group')->where('gid',$gisid)->first();
            if(($Autorlist->author!='') &&($Autorlist->author!=' ')&&($Autorlist->author!='N;') && isset($Autorlist->author)){
            $Autorlists = unserialize($Autorlist->author);
            }else{
            $Autorlists=array();
            }
            $sulis = array(
            '"'.'cxma'.'"'=>"小程序码",
            '"'.'cxma_list'.'"'=>"列表",
            '"'.'cxma_haibao'.'"'=>"海报",
            );
            $sulis_ca = array(
            '"'.'cxma'.'"'=>"fa fa-cube",
            '"'.'cxma_list'.'"'=>"fa fa-cube",
            '"'.'cxma_haibao'.'"'=>"fa fa-cube",
            );
            $au=Config::get("Authority.AuthorList");
            }

            @endphp

            @if($gisid==0)

                {{--超级管理员,读取权限--}}
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
                </li>
                {{--小程序码--}}
                <li class="treeview" id="cxma">
                    <a href="{{ url("admin/cxma/index") }}">
                        <i class="fa fa-cube"></i> <span>小程序码</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li id="cxma_list"  class="treeview" >
                            <a href="{{ url('admin/cxma/index') }}"><i class="fa fa-circle-o"></i>列表</a>
                        </li>
                    </ul>
                    <ul class="treeview-menu">
                        <li id="cxma_haibao"  class="treeview" >
                            <a href="{{ url('admin/hb_cxma/index') }}"><i class="fa fa-circle-o"></i>海报</a>
                        </li>
                    </ul>
                </li>
{{--角色以及权限管理--}}

                <li class="treeview" id="_user">
                    <a href="{{ url("/account/user/list") }}">
                        <i class="fa fa-cube"></i> <span>账号</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <li id="_user_index"  class="treeview" >
                            <a href="{{ url('/account/user/list') }}"><i class="fa fa-circle-o"></i>账号管理</a>
                        </li>
                    </ul>
                    <ul class="treeview-menu">
                        <li id="_roles_index"  class="treeview" >
                            <a href="{{ url('/account/roles/list') }}"><i class="fa fa-circle-o"></i>角色管理</a>
                        </li>
                    </ul>
                </li>

            @else
                {{--次级管理员,读取权限--}}
                @foreach($Autorlists as $k=>$v)
                    <li class="treeview" id="{{str_replace('"','',$k)}}">
                        <a href="javascript:void(0);">
                            <i class="fa fa-cube"></i> <span>{{$sulis[$k]}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">
                            @foreach($v as $k1=>$v1)
                            <li id="{{str_replace('"','',$k1)}}"  class="treeview" >
                                <a href="{{ url($v1[0]) }}"><i class="fa fa-circle-o"></i>{{$sulis[$k1]}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @endif
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

    //【小程序码管理】js判断
    var cxma_index = '/admin/cxma';
    if(server.indexOf(cxma_index)!= -1){
        $("#cxma_list").addClass('active');
        $("#cxma_list >a>i").addClass('text-aqua');
        $("#cxma").addClass('active');
    }
    // 海报
    var hb_cxma_index = '/admin/hb_cxma';
    if(server.indexOf(hb_cxma_index)!= -1){
        $("#cxma_haibao").addClass('active');
        $("#cxma_haibao >a>i").addClass('text-aqua');
        $("#cxma").addClass('active');
    }

    // 角色管理
    var user_index = '/account/user';
    if(server.indexOf(user_index)!= -1){
        $("#_user_index").addClass('active');
        $("#_user_index >a>i").addClass('text-aqua');
        $("#_user").addClass('active');
    }
    // 权限管理
    var roles_index = '/account/roles';
    if(server.indexOf(roles_index)!= -1){
        $("#_roles_index").addClass('active');
        $("#_roles_index >a>i").addClass('text-aqua');
        $("#_user").addClass('active');
    }
</script>
