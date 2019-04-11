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
    <form class="form-horizontal" action="{{ url("/admin/cxma/store") }}" method="post" name="submit1" role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
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

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span> 标题：</label>
                                <div class="col-sm-3">
                                    <input type="text" name="ht[title]" value="" required class="form-control" maxlength="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">简介：</label>
                                <div class="col-sm-3">
                                    <textarea name="ht[introduction]" class="form-control" maxlength="150" rows="4"></textarea>
                                </div>
                                <span class="label label-info">限200字符或者100汉字。</span>
                            </div>

                            {{--小图片--}}
                            <div class="form-group has-feedback">
                                <label for="" class="col-sm-2 control-label"><span class="txt-required">*</span>图片：</label>
                                <div class="col-sm-10"  style="width: auto">
                                    <div class="multiple-upload pro-thumb" >
                                            <div class="multiple-item">
                                                <div class="multiple-del glyphicon glyphicon-remove-circle"></div>
                                                <a class="select-image" data-toggle="modal" href="" data-target="#gallery_modal">
                                                    <input type="hidden" name="ht[pic]" value="">
                                                    <div class="img-put"><img src=""></div>
                                                </a>
                                            </div>
                                        <div class="multiple-add"  data-isMultiple="true" id="sub_uploads1">
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
                                    <input type="number" name="ht[order]" value=""  required  class="form-control" maxlength="5" min="0" placeholder="数值越大，位置越靠前">
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

                    <div id="floor_2" class="panel panel-default">
                        <div class="panel-heading">
                            小程序码详情
                        </div>
                        {{--概况详情--}}
                        <div id="myTabContent" class="tab-content">
                            @include('UEditor::head')
                            <textarea id="container" name="ht[desc]" class="content">

                            </textarea>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="submit1" id="submit1" onclick="return toSub()" class="btn btn-primary btn-lg btn-block save-action" style="margin-left:1em;">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{--概况小图片集--}}
    <form id="uploadForm1" action="{{url("/admin/infonews/create")}}" method="post">
        {{csrf_field()}}
        <input style="display: none;" name="ht[pic]" type="file"  class="inputFile" id="inputfile1" />
    </form>
    {{--概况详情banner图片--}}
    <form id="uploadForm4" action="{{url("/admin/infonews/create")}}" method="post">
        {{csrf_field()}}
        <input style="display: none;" name="ht[banner]" type="file" class="inputFile" id="inputfile4" wi />
    </form>

    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function() {
            ue.setHeight(400);
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });
    </script>

    {{--概况小图片集--多图片上传--}}
    <script type="text/javascript">
        $(function (e) {
            $("#uploadForm1").on('submit', function (e) {
                // e.preventDefault();
                //创建FormData对象
                var data = new FormData();
                //为FormData对象添加数据
                $.each($('#inputfile1')[0].files, function (i, file) {
                    data.append('upload_file' + i, file);
                });
                console.log($('#inputfile1')[0].files);
                $.ajax({
                    url: "{{url("/admin/infonews/create")}}",
                    type: "POST",
                    // data:  new FormData(this),
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    // 显示加载图片
                    beforeSend: function () {
                        $('.loading-shadow').addClass('active');
                    },
                    success: function (data) {
                        console.log(data);
                        // 移除loading加载图片
                        $('.loading-shadow').removeClass('active');

                        var _img = '';
                        var url = 'http://{{ $_SERVER['HTTP_HOST'] }}/storage/app/uploads/info/';
                        console.log(data.msg);
                        var res = data.msg;
                        if (res.length > 0) {
                            for (i = 0; i < res.length; i++) {
                                _img = '<div class="multiple-item">' +
                                        '<div class="multiple-del glyphicon glyphicon-remove-circle"></div>' +
                                        '<a class="select-image" data-toggle="modal" href="" data-target="#gallery_modal">' +
                                        '<input type="hidden" name="ht[pic]" value= '+ url + ''+res[i]+'>' +
                                        '<div class="img-put"><img src=" '+ url + res[i] + '"></div>' +
                                        '</a>' +
                                        '</div>';
                                $("#sub_uploads1").before(_img);
                            }
                        }
                    },
                    error: function () {
                    }
                });
            });
            // 选择完要上传的文件后, 直接触发表单提交
            $("#inputfile1").on('change', function () {
                // 如果确认已经选择了一张图片, 则进行上传操作
                if ($.trim($(this).val())) {
                    $("#uploadForm1").trigger('submit');
                }
            });

            // 触发文件选择窗口
            $("#sub_uploads1").on('click', function () {
                var item_pic_len = $(".multiple-upload.pro-thumb").find("input[name='ht[pic]']").length;
                if(item_pic_len>0){
                    layer.msg('您最多可以添加1张图片', {icon: 6});
                }else{
                    $("#inputfile1").trigger('click');
                }
            });
        });
    </script>

    {{--/编辑器--}}
    <script>

        //提交前验证
        function toSub() {
            document.getElementById("specification").submit();
        }

    </script>
@stop



