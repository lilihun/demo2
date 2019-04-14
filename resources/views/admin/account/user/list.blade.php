@extends('admin.layouts.app')
@section('content')
    <div class="box box-primary">
        <div class="mailbox-controls with-border">
            <a href="{{url('/account/user/add')}}" class="btn btn-default btn-sm text-light-blue"><i class="fa fa-plus-square-o"></i> 添加子帐号</a>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>登录账号</th>
                <th>角色</th>
                <th>操作</th>
            </tr>
            </thead>
            {{--<{if $data}>--}}
            {{--<tbody>--}}
            {{--<{foreach from=$data item=item}>--}}
            {{--<tr>--}}
                {{--<td><{$item.login_account}></td>--}}
                {{--<td><{$rolesName.{$item.role_id}}></td>--}}
                {{--<td><{$item.mobile}></td>--}}
                {{--<td><{$item.email}></td>--}}
                {{--<td>--}}
                    {{--<a href="<{url action=topshop_ctl_account_list@edit seller_id=$item.seller_id}>">编辑</a>&nbsp;&nbsp;--}}
                    {{--<a href="#" data-seller-id="<{$item.seller_id}>" data-toggle="modal" data-target="#modifyAccountShopPwd">修改密码</a>&nbsp;&nbsp;--}}
                    {{--<a href="<{url action=topshop_ctl_account_list@delete seller_id=$item.seller_id}>" class="remove text-danger" >删除</a>--}}
                {{--</td>--}}
            {{--</tr>--}}
            {{--<{/foreach}>--}}
            {{--</tbody>--}}
            {{--<{else}>--}}
            <tbody>
            @foreach($userlist as $k=>$v)
            <tr>
                <td>{{$v->user_name}}</td>
                <td>{{$v->gname}}</td>
                <td>
                    <a href="{{url('/account/user/edit',[$v->user_id])}}">编辑</a>&nbsp;&nbsp;
                    <a href="{{url('/account/user/del',[$v->user_id])}}" class="remove text-danger" >删除</a>
                </td>
            </tr>
            @endforeach
            </tbody>

            {{--<tbody id="none_cat">--}}
            {{--<tr class="none-information">--}}
                {{--<td colspan="6"><p class="help-block text-center">暂无数据</p></td>--}}
            {{--</tr>--}}
            {{--</tbody>--}}
            {{--<{/if}>--}}
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modifyAccountShopPwd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改子帐号密码</h4>
                </div>
                <form  action="" method="post" class="form-horizontal" data-validate-onsuccess="ajaxSubmit" id="form_delivery" role="form">
                    <div class="modal-body">
                        <input type="hidden" class='seller-id' name="seller_id" value="">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" >重置密码：</label>
                            <div class="col-sm-6">
                                <input type="password" name="login_password" class="form-control" placeholder="6-20个字符,不能纯数字,字母" required maxlength="20" data-validate-length-min="6" pattern="^(?!\d+$|[a-zA-Z]+$)[^\u4e00-\u9fa5]*$" data-validate-regexp-message="不能纯数字、字母">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" >确认密码：</label>
                            <div class="col-sm-6">
                                <input  name='psw_confirm' type='password' placeholder="确认重置密码" maxlength="20" required data-validate-equalto-field="login_password" data-validate-equalto-message="两次密码输入不一致" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        $('#modifyAccountShopPwd').on('show.bs.modal', function (event) {
            var seller_id = $(event.relatedTarget).attr("data-seller-id");
            $(".seller-id").val(seller_id);
        })

        function ajaxSubmit (e) {
            var form = e.target;
            e.preventDefault();
            $.post(form.action, $(form).serialize(), function(rs) {
                if(rs.error) {
                    $('#messagebox').message(rs.message);
                    return;
                }
                if(rs.success) {
                    $('#messagebox').message(rs.message, 'success');
                }
                if(rs.redirect) {
                    location.href = rs.redirect;
                }
            });
        }


        $('.remove').on('click', function(e) {
            var _this = $(this);
            e.preventDefault();
            Modal.confirm({msg: "确定删除该账号吗？"}).on(function(e) {
                if(e==true) {
                    var href = _this.attr('href');
                  window.location.href=href;
                }
            });
        });

    </script>
@stop