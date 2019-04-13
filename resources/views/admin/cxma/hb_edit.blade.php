@extends('admin.layouts.app')
@section('content-header')
    <link rel="stylesheet" type="text/css" href="{{ asset("public/lineditor/css/editor.css") }}">
    <h1>
        小程序码管理
        <small>{{ $label }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 主页</a></li>
        <li><a href="{{url('/admin/infonews/index')}}">{{ $label }}管理</a></li>
        <li class="active">{{ $label }}</li>
    </ol>
@stop

@section('content')
    @include('admin.common.message')
    <form class="form-horizontal" action="{{ url("/admin/hb_cxma/store") }}" method="post" name="submit1" role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="id" value="{{  isset($res->id) ? $res->id : '' }}">
        <div class="panel panel-outter">
            <div class="panel-heading">
                <h4>{{ $label }}</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-11">
                    <div id="floor_1" class="panel panel-default">
                        <div class="panel-heading">
                            基本内容
                        </div>

                        <div class="panel-body">

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span> 标题：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[title]" value="{{ isset($res->title) ? $res->title : '' }}" required class="form-control" maxlength="50">
                                </div>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>海报：</label>
                                <div class="col-sm-10"  style="width: auto">
                                    <div class="multiple-upload pro-thumb" >
                                        @if(isset($res->url))
                                        <div class="multiple-item">
                                            <div class="multiple-del glyphicon glyphicon-remove-circle"></div>
                                            <a class="select-image" data-toggle="modal" href="" data-target="#gallery_modal">
                                                <input type="hidden" name="ht[url]" value="{{$res->url}}">
                                                <div class="img-put"><img src="{{$res->url}}"></div>
                                            </a>
                                        </div>
                                        @endif
                                        <div class="multiple-add"  data-isMultiple="true" id="sub_uploads_1">
                                            <i class="glyphicon glyphicon-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10" style="width: auto">
                                    <div class="multiple-upload pro-thumb" >
                                        <span>(本类目您最多可上传1张图片) 图片( jpg | png ) 750px*750px</span>
                                    </div>
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
                                    <input type="number" name="ht[order]" value="{{ isset($res->order) ? $res->order : '0' }}"  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
                                </div>
                            </div>
                            {{--是否显示--}}
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">是否显示：</label>
                                <div class="radio">
                                    <label  class="form-inline">
                                        <input type="radio" name="ht[is_show]" value='1' @if(!isset($res->is_show) || (isset($res->is_show) && $res->is_show=='1')) checked @endif>
                                        是&nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                    <label  class="form-inline">
                                        <input type="radio" name="ht[is_show]" value='0' @if(isset($res->is_show) && $res->is_show=='0') checked @endif>
                                        否
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="button" name="submit1" id="submit1" onclick="return toSub()" class="btn btn-primary btn-lg btn-block save-action" style="margin-left:1em;">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{--上传文件模拟form表单--}}
    <form id="uploadForm_1" method="post" hidden>
        <input style="display: none;" type="file" multiple="multiple"  id="inputFile_1" />
    </form>

{{--    --}}{{--上传文件模拟form表单--}}{{--
    <form id="uploadForm_2" method="post" hidden>
        <input style="display: none;" type="file" multiple="multiple"  id="inputFile_2" />
    </form>--}}

{{--上传图片类--}}
    <script type="application/javascript">
        /**
         * 上传文件js
         * 2019-04-13 10:19 by lqy
         *
         * upload_id  文件触发id  如：<div class="multiple-add"  data-isMultiple="true" id="sub_uploads_1">
         * form_id   上传文件模拟form表单 <form>的id值
         * form_id   上传文件模拟form表单 <input>的id值
         * type  上传类型   one代表只允许上传一张  more代表允许上传多张
         * input_name input输入框的name值
         * num 代表序号
         * url 异步上传url
         * path 文件路径
         * token _token值 固定
         *
         * 《注意》每增加一个图片上传DIV，则创建一个【上传文件模拟form表单】，并修改对应的from的ID值 以及 input的ID值
         */

        $(function (e) {
//            配置固定参数
            var num;
            var url  = "{{url("/admin/image/upload")}}";
            var path = "/storage/app/uploads/info/";
            var _token = "{{ csrf_token() }}";

/*上传文件方法*/
            num = '1';
            var img_1 = {
                upload_id:'sub_uploads_'+num,
                form_id:'uploadForm_'+num,
                input_id:'inputFile_'+num,
                type:'one',
                input_name:'ht[url]',
//                以下参数为固定值
                url:url,
                path:path,
                token:_token
            };
            Upload(img_1);

/*上传文件方法*/
/*            num = '2';
            var img_2 = {
                upload_id:'sub_uploads_'+num,
                form_id:'uploadForm_'+num,
                input_id:'inputFile_'+num,
                type:'more',
                input_name:'ht[test]',
//                以下参数为固定值
                url:url,
                path:path,
                token:_token
            };
            Upload(img_2);*/
        });
    </script>

    <script>
        //提交前验证
        function toSub() {
//            检测上传图片是否已上传
            var url = $("input[name='ht[url]']").val();
            var title = $("input[name='ht[title]']").val();
            var old_url = "{{ isset($res->url) ? $res->url : '' }}";
            if(url == '' || url == undefined || title==''){
                layer.msg("标题或海报为必填项");
                return false;
            }

            var is_same = "{{ $is_same }}";
            if(is_same == 'true' && url != old_url){
                layer.confirm('后台检测到该海报已被生成前台码，若继续操作，将对已生成的前台码造成不可逆的影响，确定要继续吗？', {
                    btn: ['确定','取消'] //按钮
                }, function() {
                    document.getElementById("specification").submit();
                });
            }else{
                document.getElementById("specification").submit();
            }
        }
    </script>
@stop



