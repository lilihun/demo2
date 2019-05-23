@extends('admin.layouts.app')
@section('content')
    @include('admin.common.validate')
    @include('admin.common.message')
    <form class="form-horizontal" action="{{url('/account/user/saves')}}" method="post" name="submit1" role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="panel panel-outter">
            <div class="panel-heading">
                <h4>创建账户</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-11">
                    <div id="floor_1" class="panel panel-default">
                        <div class="panel-heading">
                            基本内容
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >用户名：</label>
                                <div class="col-sm-3">
                                    {{--<input type="text" name="login_account" <{if $seller_id }>disabled<{/if}> value="<{$login_account}>" class="form-control" placeholder="最少4个字符 不能纯数字" required maxlength="50" data-validate-length-min="4" pattern="^(?!\d+$)[^\u4e00-\u9fa5]*$" data-validate-regexp-message="不能用纯数字或中文" data-validate-remote-url="<{url action=topshop_ctl_passport@isExists type=account}>" data-validate-remote-name="login_account" data-validate-remote-message="此帐号已被注册过，请换一个重试">--}}
                                    <input type="text" name="user_name"  value="" class="form-control" placeholder="最少4个字符 不能纯数字" required maxlength="50">
                                </div>
                            </div>
                            {{--<{if !$seller_id }>--}}
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >设置密码：</label>
                                <div class="col-sm-3">
                                    <input type="password" name="user_pass" class="form-control" placeholder="6-20个字符,不能纯数字,字母" required maxlength="20" data-validate-length-min="6" pattern="^(?!\d+$|[a-zA-Z]+$)[^\u4e00-\u9fa5]*$" data-validate-regexp-message="不能纯数字、字母">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >确认密码：</label>
                                <div class="col-sm-3">
                                    <input  name='user_passconfirm' type='password' placeholder="登录密码确认" maxlength="20" required data-validate-equalto-field="user_pass" data-validate-equalto-message="两次密码输入不一致" class="form-control" >
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">选择角色：</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        @foreach($userlist as $k=>$v)
                                            <label><input   @if($k==0) checked @endif type="radio" name="gid" value="{{$v->gid}}" ><b>{{$v->name}}</b>&nbsp;&nbsp;</label>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="submit2" id="submit1" class="btn btn-primary btn-lg btn-block save-action" style="margin-left:1em;">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop