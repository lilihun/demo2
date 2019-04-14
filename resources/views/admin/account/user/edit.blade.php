@extends('admin.layouts.app')
@section('content')
    <div class="box box-default">
        <form  action="{{url('/account/user/saves')}}" method="post" class="form-horizontal"  id="form_delivery" role="form">
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{$detail->user_id}}" />
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" >用户名：</label>
                    <div class="col-sm-3">
                        <input type="text" name="user_name"  value="{{$detail->user_name}}" class="form-control" placeholder="最少4个字符 不能纯数字" required maxlength="50">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >设置密码：</label>
                    <div class="col-sm-3">
                        <input type="password" name="user_pass" value="{{$detail->user_pass}}" class="form-control" placeholder="6-20个字符,不能纯数字,字母" required maxlength="100" data-validate-length-min="6" pattern="^(?!\d+$|[a-zA-Z]+$)[^\u4e00-\u9fa5]*$" data-validate-regexp-message="不能纯数字、字母">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" >确认密码：</label>
                    <div class="col-sm-3">
                        <input  name='user_passconfirm' value="{{$detail->user_pass}}" type='password' placeholder="登录密码确认,不填表示不修改" maxlength="100" required data-validate-equalto-field="user_pass" data-validate-equalto-message="两次密码输入不一致" class="form-control" >
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">选择角色：</label>
                    <div class="col-sm-10">
                        <div class="radio">
                           @foreach($userlist as $k=>$v)

                            <label><input   @if($v->gid==$detail->gid) checked @endif type="radio" name="gid" value="{{$v->gid}}" ><b>{{$v->name}}</b>&nbsp;&nbsp;</label>
                          @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
        </form>
    </div>
    <script>

    </script>
@stop