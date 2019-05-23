@extends('admin.layouts.app')
@section('content')
    @include('admin.common.message')
    <a href="{{url('/account/roles/add')}}" class="btn btn-primary margin-bottom"><i class="fa fa-paint-brush" style="margin-right: 6px"></i>添加角色</a>
    <div class="box box-primary">
        <div class="col-xs-14 col-sm-14">
            <div class="nav-tabs-custom" id="tabs">
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <thead>
                        <tr>
                            <th>角色名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userlist as $v)
                            <tr>
                                <td>{{$v->name}}</td>
                                <td>
                                    <a href="{{url('/account/roles/edit',[$v->gid])}}">编辑</a>&nbsp;
                                    <a href="{{url('/account/roles/del',[$v->gid])}}" class="remove" >删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


    <script>

        $('.remove').on('click', function(e) {
            var _this = $(this);
            e.preventDefault();
            Modal.confirm({msg: "确定删除该角色吗？"}).on(function(e) {
                if(e==true) {
                    var href = _this.attr('href');
                    window.location.href=href;
                }
            });
        });

    </script>

@stop