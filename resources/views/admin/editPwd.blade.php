@extends('admin.layouts.app')
@section('content-header')
    <link rel="stylesheet" type="text/css" href="{{ asset("public/lineditor/css/editor.css") }}">
@stop
@section('content')
    @include('admin.common.validate')
    @include('admin.common.message')
    <form class="form-horizontal" action="{{ url("admin/doEditPwd/$user->user_id") }}" method="post"  role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="panel panel-outter">
            <div class="panel-heading">
                <h4>修改密码</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-11">
                    <div id="floor_1" class="panel panel-default">
                        <div class="panel-heading">
                            基本内容
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>旧密码：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="old_pwd" value="" required class="form-control" maxlength="20">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>新密码：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="new_pwd" value="" required class="form-control" maxlength="20">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>确认密码：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="r_new_pwd" value="" required class="form-control" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="submit1"  class="btn btn-primary btn-lg btn-block save-action" style="margin-left:1em;">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop



