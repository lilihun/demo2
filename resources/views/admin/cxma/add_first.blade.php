@extends('admin.layouts.app')
@section('content-header')
    <link rel="stylesheet" type="text/css" href="{{ asset("public/lineditor/css/editor.css") }}">
    <h1>
        信息发布管理
        <small>小程序码</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 主页</a></li>
        <li><a href="{{url('/admin/cxma/index')}}">小程序码管理</a></li>
        <li class="active">编辑小程序码</li>
    </ol>
@stop

@section('content')
    @include('admin.common.validate')
    @include('admin.common.message')
    <form class="form-horizontal" action="{{ url("/admin/cxma/do_addFirst") }}" method="post" name="submit1" role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="panel panel-outter">
            <div class="panel-heading">
                <h4>编辑小程序码</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-11">
                    <div id="floor_1" class="panel panel-default">
                        <div class="panel-heading">
                            基本内容
                        </div>

                        <div class="panel-body">
                            {{--选中一级分类--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>一级分类：</label>
                                <div class="col-sm-3">
                                    <select name="ht[category1]" required id="act-selectshopcat" class="form-control" required>
                                        <option value="">请选择一级分类</option>
                                        @if(count($category1)>0)
                                            @foreach($category1 as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{--选中二级分类--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>二级分类：</label>
                                <div class="col-sm-3">
                                    <select name="ht[category2]" required id="act-selectshopcat" class="form-control" required>
                                        <option value="">请选择二级分类</option>
                                        @if(count($category2)>0)
                                            @foreach($category2 as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span> 标题：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[title]" value="" required class="form-control" maxlength="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">酒店信息：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[hotel_msg]" value="" required class="form-control" maxlength="30">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">销售人员信息：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[sales_mag]" value="" required class="form-control" maxlength="30">
                                </div>
                            </div>

                            {{--排序--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 排序
                                    <label  data-toggle="tooltip" data-placement="top" title="数值越大，位置越靠前"><i class="fa fa-question-circle text-aqua"></i></label>
                                    ：
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[order]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--是否显示--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">是否显示：</label>
                                <div class="radio">
                                    <label  class="form-inline">
                                        <input type="radio" name="ht[disabled]" value='0' checked>
                                        是&nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                    <label  class="form-inline">
                                        <input type="radio" name="ht[disabled]" value='1'>
                                        否
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" name="submit1" id="submit1" onclick="return toSub()" class="btn btn-primary btn-lg btn-block save-action" style="margin-left:1em;">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{--/编辑器--}}
    <script>

        //提交前验证
        function toSub() {
            document.getElementById("specification").submit();
        }

    </script>
@stop



