@extends('admin.layouts.app')
@section('content-header')
    <link rel="stylesheet" type="text/css" href="{{ asset("public/lineditor/css/editor.css") }}">
    <h1>
        信息发布管理
        <small>海报</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 主页</a></li>
        <li><a href="{{url('/admin/cxma/index')}}">海报管理</a></li>
        <li class="active">编辑海报</li>
    </ol>
@stop

@section('content')
    @include('admin.common.validate')
    @include('admin.common.message')
    <form class="form-horizontal" action="{{ url("/admin/cxma/do_addSecond/$res->id") }}" method="post" name="submit1" role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="panel panel-outter">
            <div class="panel-heading">
                <h4>编辑海报</h4>
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
                                                <option value="{{$k}}" @if($res->category1 == $k) selected @endif>{{$v}}</option>
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
                                                <option value="{{$k}}" @if($res->category2 == $k) selected @endif>{{$v}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span> 标题：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[title]" value="{{ $res->title }}" readonly class="form-control" maxlength="50">
                                </div>
                            </div>

                            {{--上传海报--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>选择海报：</label>
                                <div class="col-sm-3">
                                    <select name="ht[haibao]" required id="s_haibao" class="form-control" required>
                                        <option value="">请选择海报</option>
                                        @if(count($haibao)>0)
                                            @foreach($haibao as $k=>$v)
                                                <option value="{{$v->url}}">{{$v->url}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{--文字水印--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span> 文字水印：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[remark]" value=""  class="form-control" maxlength="50">
                                </div>
                            </div>

                            {{--小程序码长度--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 小程序码宽度
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[width]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--小程序码高度--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 小程序码高度
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[height]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--左边距--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 左边距
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[left]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--右边距--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 右边距
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[right]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--上边距--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 上边距
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[top]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--下边距--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 下边距
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[bottom]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--透明度--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">
                                    <span class="txt-required">*</span> 透明度
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" name="ht[opacity]" value="0"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
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



