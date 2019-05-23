@extends('admin.layouts.app')
@section('content')
    @include('admin.common.validate')
    @include('admin.common.message')
    <form class="form-horizontal" action="{{url('/account/roles/save')}}" method="post" name="submit1" role="form" id="specification"  data-validate-excluded=":disabled, :not(:visible)" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="panel panel-outter">
            <div class="panel-heading">
                <h4>编辑角色</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-11">
                    <div id="floor_1" class="panel panel-default">
                        <div class="panel-heading">
                            基本内容
                        </div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label" >角色名称：</label>
                                <div class="col-sm-3">
                                    <input  name="role_name" type="text" value="{{$userlist->name}}" placeholder="必填" maxlength="20" required class="form-control" >
                                    <input type="hidden" name="gid" value="{{$gid}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" >权限：</label>
                                <div class="col-sm-10" >
                                    <ul class="list-unstyled">
                                        <li>
                                            <label class="checkbox-inline"><input id="check_all" class="check-all" type="checkbox"><b>全选</b></label>
                                        </li>
                                        @foreach($author as $k=>$v)
                                            <label class="checkbox-inline"><b>{{$aulis[$k]}}</b></label>

                                            @foreach($v as $k1=>$v1)
                                                <li>
                                                    {{-- $userlist->author 的建名存在双引号--}}
                                                    @if(isset($userlist->author[$k][$k1])&&count($userlist->author[$k][$k1])==count($v1))
                                                        <label class="checkbox-inline"> <input type="checkbox" checked="checked"  class="check-this-inline" value=""><b>{{$aulis[$k1]}}</b></label>
                                                    @else
                                                        <label class="checkbox-inline"> <input type="checkbox"  class="check-this-inline" value=""><b>{{$aulis[$k1]}}</b></label>
                                                    @endif

                                                    @foreach($v1 as $k2=>$v2)
                                                        <label class="checkbox-inline"><input type="checkbox"
                                                        @if(isset($userlist->author[$k][$k1]))
                                                        @foreach($userlist->author[$k][$k1] as$key1=>$va1)
                                                        @if($va1==$k2)
                                                        checked="checked"
                                                        @endif
                                                        @endforeach

                                                        @endif
                                                        name="workground[{{$k}}][{{$k1}}][]" class="check-item" value="{{$k2}}">{{$v2}}</label>

                                                    @endforeach

                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
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

    <script>
        var all = $('.check-item');
        $('#check_all').click(function(){
            if($(this).prop('checked')==true){
                $('.check-this-inline').prop('checked',true);
                all.prop('checked',true);
            }else{
                $('.check-this-inline').prop('checked',false);
                all.prop('checked',false);
            }
        })

        all.click(function() {
            if(!$(this).hasClass('check-all') && !$(this).hasClass('check-this-inline')){
                $('#check_all').prop('checked',false);
                $(this).parent().parent().find('.check-this-inline').prop('checked', false);
            }else if($(this).hasClass('check-this-inline')){
                $('#check_all').prop('checked',false);
            }
        });

        $('.check-this-inline').click(function(){
            if($(this).prop('checked')==true){
                $(this).parent().siblings().find('input[type="checkbox"]').prop('checked',true);
            }else{
                $(this).parent().siblings().find('input[type="checkbox"]').prop('checked',false);
            }
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
    </script>

@stop