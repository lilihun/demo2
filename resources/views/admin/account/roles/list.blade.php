@extends('admin.layouts.app')
@section('content')
    <div class="box box-primary">
        <div class="mailbox-controls with-border">
            <a href="{{url('/account/roles/add')}}" class="btn btn-default btn-sm text-light-blue"><i class="fa fa-plus-square-o"></i>添加角色</a>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>角色名称</th>
                <th>操作</th>
            </tr>
            </thead>
            {{--<{if $data}>--}}
            {{--<tbody>--}}
            {{--<{foreach from=$data item=item}>--}}
            {{--<tr>--}}
                {{--<td><{$item.role_name}></td>--}}
                {{--<td>--}}
                    {{--<a href="<{url action=topshop_ctl_account_roles@edit role_id=$item.role_id}>">编辑</a>&nbsp;--}}
                    {{--<a href="<{url action=topshop_ctl_account_roles@delete role_id=$item.role_id}>" class="remove" >删除</a>--}}
                {{--</td>--}}
            {{--</tr>--}}
            {{--<{/foreach}>--}}
            {{--</tbody>--}}
            {{--<{else}>--}}
            {{--<tbody id="none_cat">--}}
            {{--<tr class="none-information">--}}
                {{--<td colspan="5"><p class="help-block text-center">暂无数据</p></td>--}}
            {{--</tr>--}}
            {{--</tbody>--}}
            {{--<{/if}>--}}

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