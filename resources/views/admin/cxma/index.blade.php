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
    <a href="{{url('admin/cxma/create')}}" class="btn btn-primary margin-bottom"><i class="fa fa-paint-brush" style="margin-right: 6px"></i>创建</a>
    <div class="box box-primary">
        <div class="col-xs-14 col-sm-14">
            <div class="nav-tabs-custom" id="tabs">
                <ul class="nav nav-tabs sub-nav-tabs">
                    <li role="presentation" class="active"><a href="{{ url("admin/cxma/index")}}">中文版</a></li>
                    <li class="pull-right header export-filter" data-value="" data-app="" data-model="">
                        <div class="box-header with-border" style="display: none">
                            <div class="box-tools">
                                <form action="" method="get">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm pull-right" name="s_title"
                                               style="width: 150px;" placeholder="搜索标题">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <tbody>
                        <thead>
                        <tr>
                            <th>操作</th>
                            <th>标题</th>
                            <th>所属分类</th>
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
                                        <a style="font-size: 16px" href="{{ url("/admin/cxma/edit/$v->id") }}"><i class="fa fa-fw fa-pencil" title="修改"></i></a>
                                        <a style="font-size: 16px;color: #dd4b39;" href="javascript:;" onclick="delCate({{$v->id}})"><i class="fa fa-fw fa-trash-o" title="删除"></i></a>
                                    </td>
                                    <td class="text-muted">{{ $v->title }}</td>
                                    <td class="text-navy">{{ $v->category2 }}</td>
                                    <td class="text-navy">{{ $v->xc_ma_url }}</td>
                                    <td class="text-navy">{{ $v->haibao_url }}</td>
                                    <td class="text-navy">{{ $v->order }}</td>
                                    <td class="text-red">@if($v->disabled=='0') 显示 @else 不显示 @endif</td>
                                    <td class="text-navy">@if(!empty($v->update_time)) {{ date("Y-m-d H:i:s",$v->update_time) }} @else -- @endif</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" style="text-align: center">暂无数据</td>
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
    <script type="text/javascript">
        //删除数据
        function delCate(news_id) {
            layer.confirm('您确定要删除吗？', {
                btn: ['确定','取消'] //按钮
            }, function() {
                $.ajax({
                    url: "{{ url('admin/cxma/destroy')}}",
                    type: "POST",
                    data: {news_id: news_id,_token : "{{csrf_token()}}"},
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
    </script>
@stop

