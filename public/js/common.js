/**
 * 后台上传图片公共类
 * Created by Administrator on 2019/4/13 0013.
 */

var Upload = (function(window){
    var Upload = function(info){
        return new Upload.fn.init(info);
    };

    Upload.fn = Upload.prototype = {
        constructor:Upload,
        init:function(info){
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
             * 《注意》每增加一个图片上传div，则创建一个【上传文件模拟form表单】，并修改对应的from的ID值 以及 input的ID值
             */

//          业务处理
            this.form_id = info.form_id;
            this.upload_id = info.upload_id;
            this.input_id = info.input_id;
            this.url = info.url;
            this.input_name = info.input_name;
            this.token = info.token;
            this.type = info.type;

            if( this.type != 'one')
            {
                // 多文件上传
                info.input_name = info.input_name+'[]';
                nums = 10;
            }else
            {
                // 单文件上传
                nums = 1;
            }

            // 触发文件选择窗口
            $('#'+info.upload_id).on('click', function () {
                var item_pic_len = $(this).parent().find(".multiple-item").length;
                if(item_pic_len > (nums-1)){
                    layer.msg('您最多可以添加'+nums+'张图片');
                    return false;
                }else{
                    $('#'+info.input_id).trigger('click');
                }
            });


            // 选择完要上传的文件后, 直接触发表单提交
            $('#'+info.input_id).on('change', function () {
                // 如果确认已经选择了一张图片, 则进行上传操作
                if ($.trim($(this).val())) {
                    $('#'+info.form_id).trigger('submit');
                }
            });

            $('#'+info.form_id).on('submit', function (e) {
                //创建FormData对象
                var data = new FormData();

                // 判断如果是每次添加的最大值
                var length = $('#'+info.input_id)[0].files.length;
                if(length > nums){
                    layer.msg("上传的数量不能超过【"+nums+'】张');
                    return false;
                }

                //为FormData对象添加数据
                $.each( $('#'+info.input_id)[0].files, function (i, file) {
                    data.append('upload_file' + i, file);
                });
                data.append('_token',info.token);
                $.ajax({
                    url: info.url,
                    type: "POST",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    // 显示加载图片
                    beforeSend: function () {
                        $('.loading-shadow').addClass('active');
                    },
                    success: function (data) {
                        // 判断
                        if(data.rsp != 'succ'){
                            layer.msg(data.msg);
                            return false;
                        }

                        console.log(data);
                        // 移除loading加载图片
                        $('.loading-shadow').removeClass('active');

                        var _img = '';
                        var url = info.path;
                        console.log(data.msg);
                        var res = data.msg;
                        if (res.length > 0) {

                            for (i = 0; i < res.length; i++) {
                                _img = '<div class="multiple-item">' +
                                    '<div class="multiple-del glyphicon glyphicon-remove-circle"></div>' +
                                    '<a class="select-image" data-toggle="modal" href="" data-target="#gallery_modal">' +
                                    '<input type="hidden" name='+info.input_name+' value= '+url+res[i]+'>' +
                                    '<div class="img-put"><img src=" '+ url + res[i] + '"></div>' +
                                    '</a>' +
                                    '</div>';
                                $('#'+info.upload_id).before(_img);
                            }
                        }

                        // 提示上传成功
                        layer.msg('上传成功！');
                    },
                    error: function (e) {
                        layer.msg('上传失败：'+e);
                        return false
                    }
                });
            });
        }
    };

    Upload.fn.init.prototype = Upload.fn;

    return Upload;
})();
