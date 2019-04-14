@extends('admin.layouts.app')
@section('content')
    <div class="box box-default">
        <form  action="{{url('/account/roles/save')}}" method="post" class="form-horizontal" id="form_delivery" role="form">
            {!! csrf_field() !!}
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" >角色名称：</label>
                    <div class="col-sm-3">
                        <input  name="role_name" type="text" value="" placeholder="必填" maxlength="20" required class="form-control" >
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
                                        <label class="checkbox-inline"> <input type="checkbox"  class="check-this-inline" value=""><b>{{$aulis[$k1]}}</b></label>
                                        @foreach($v1 as $k2=>$v2)

                                            <label class="checkbox-inline"><input type="checkbox" name='workground["{{$k}}"]["{{$k1}}"][]' class="check-item" value="{{$k2}}">{{$v2}}</label>

                                        @endforeach
                                    </li>
                                @endforeach

                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-info">保存</button>
            </div>

        </form>
    </div>
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