@extends('admin.layouts.app')
@section('content-header')
    <h1>
        {{ $label }}列表
        <small>{{ $label }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 主页</a></li>
        <li>{{ $label }}管理</li>
        <li class="active">{{ $label }}列表</li>
    </ol>
@stop

@section('content')
    @include('admin.common.message')
    <a href="{{url('admin/cxma/addFirst')}}" class="btn btn-primary margin-bottom"><i class="fa fa-paint-brush" style="margin-right: 6px"></i>创建</a>
    <div class="box box-primary">
        <div class="col-xs-14 col-sm-14">
            <div class="nav-tabs-custom" id="tabs">
                <ul class="nav nav-tabs sub-nav-tabs">
                    <li role="presentation" class="@if($type == '' || $type=='ALL') active @endif"><a href="{{ url("admin/cxma/index/ALL")}}">全部</a></li>
                    <li role="presentation" class="@if($type == 'TYMA') active @endif"><a href="{{ url("admin/cxma/index/TYMA")}}">通用码</a></li>
                    <li role="presentation" class="@if($type == 'QTMA') active @endif"><a href="{{ url("admin/cxma/index/QTMA")}}">前台码</a></li>
                    <li role="presentation" class="@if($type == 'MDMA') active @endif"><a href="{{ url("admin/cxma/index/MDMA")}}">门店码</a></li>
                    <li role="presentation" class="@if($type == 'GRMA') active @endif"><a href="{{ url("admin/cxma/index/GRMA")}}">个人码</a></li>
                </ul>
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <thead>
                        <tr>
                            <th>操作</th>
                            <th>所属分类</th>
                            <th>标题</th>
                            <th>状态</th>
                            <th>通用码</th>
                            <th>前台吗</th>
                            <th>排序</th>
                            <th>是否显示</th>
                            <th>更新时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($res)>0)
                            @foreach($res as $v)
                                <tr>
                                    <td>
                                        @if($v->status == '2')
                                            <button>
                                                <a style="font-size: 16px" href="{{ url("/admin/cxma/addFirst/$v->id") }}">
                                                    编辑
                                                </a>
                                            </button>
                                            &nbsp;&nbsp;

                                            @if($v->category1 == 'TYMA')
                                                <button>
                                                    <a style="font-size: 16px" href="{{ $v->xc_ma_url }}" target="_blank">
                                                        预览
                                                    </a>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button>
                                                    <a style="font-size: 16px" href="{{ $v->xc_ma_url }}" download="{{ $v->title }}">
                                                        下载
                                                    </a>
                                                </button>

                                            @elseif($v->category1 == 'QTMA')
                                                <button>
                                                    <a style="font-size: 16px" href="{{ $v->haibao_url }}" target="_blank">
                                                        预览
                                                    </a>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button>
                                                    <a style="font-size: 16px" href="{{ $v->haibao_url }}" download="{{ $v->title }}">
                                                        下载
                                                    </a>
                                                </button>
                                            @else
                                                <button>
                                                    不支持预览
                                                </button>
                                                &nbsp;&nbsp;
                                                <button>
                                                    不支持下载
                                                </button>
                                            @endif
                                            &nbsp;&nbsp;
                                        @endif
                                        <a style="font-size: 16px;color: #dd4b39;" href="javascript:;" onclick="delCate({{$v->id}})">
                                            <button>删除</button>
                                        </a>
                                    </td>
                                    <td class="text-navy">{{ $category1[$v->category1] }}&nbsp;->&nbsp;{{ $category2[$v->category2] }}</td>
                                    <td class="text-muted">{{ $v->title }}</td>
                                    <td class="text-muted">{{ $cx_status[$v->status] }}</td>
                                    <td class="text-navy">@if($v->category1 == 'TYMA') <img src="{{ $v->xc_ma_url }}" alt="通用码" width="50px" height="50px"> @else -- @endif</td>
                                    <td class="text-navy">@if($v->category1 == 'QTMA') <img src="{{ $v->haibao_url }}" alt="前台吗" width="50px" height="50px"> @else -- @endif</td>
                                    <td class="text-navy">{{ $v->order }}</td>
                                    <td class="text-red">@if($v->disabled=='0') 显示 @else 不显示 @endif</td>
                                    <td class="text-navy">@if(!empty($v->update_time)) {{ date("Y-m-d H:i:s",$v->update_time) }} @else -- @endif</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" style="text-align: center">暂无数据</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {!! $res->render() !!}
                </div>
            </div>
        </div>
    </div>
    <img id="preview" />
    <script type="text/javascript">
        //删除数据
        function delCate(ht_id) {
            layer.confirm('您确定要删除吗？', {
                btn: ['确定','取消'] //按钮
            }, function() {
                $.ajax({
                    url: "{{ url('admin/cxma/destroy')}}",
                    type: "POST",
                    data: {ht_id: ht_id,_token : "{{csrf_token()}}"},
                    success:function(rs){
                        if(rs.status=='0'){
                            layer.msg(rs.msg);
                            location.href = location.href;
                        }else{
                            layer.msg(rs.msg);
                        }
                    },
                    error:function(jqXHR,textStatus,errorThrown){
                        layer.msg("服务器错误！"+textStatus);
                    },
                });
            });
        }

        // 图片预览
        function imgPreview(fileDom) {
            alert(fileDom);
            //判断是否支持FileReader
            if (window.FileReader) {
                var reader = new FileReader();
            } else {
                alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
            }

            //获取文件
            /*            var file = fileDom.files[0];
                        var imageType = /^image\//;
                        //是否是图片
                        if (!imageType.test(file.type)) {
                            alert("请选择图片！");
                            return;
                        }*/
            //读取完成
            reader.onload = function(e) {
                //获取图片dom
                var img = document.getElementById("preview");
                //图片路径设置为读取的图片
                img.src = fileDom;
            };
            reader.readAsDataURL('http://lc.demo.com'+fileDom);
        }
    </script>
@stop

