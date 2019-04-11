<?php
namespace App\Http\Controllers\Admin\Image;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 图片管理
 *
 * 1、支持中文名称的文件；2、判断重复文件的；3、取消原先对文件名重命名；4、错误提示返回
 * 创建时间：2019-03-24
 * 制作人：【老弟来了网制工作室】
 */
class ImageController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->ajax())
        {
            $dir = './storage/app/uploads/'; //文件上传根目录
            if(!is_dir($dir)){
                @mkdir($dir);
            }
            $index = 0;		//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
            $filePath =[];  // 定义空数组用来存放图片路径
            foreach($_FILES as $file){
                $upload_file_name = 'upload_file' . $index;		//对应index.html FomData中的文件命名

                // 转码中文
                $filename = $_FILES[$upload_file_name]['name'];

                $encode = mb_detect_encoding($filename,array('ASCII','UTF-8','GB2312','GBK','BIG5'));

                $filename = iconv($encode,"utf-8//ignore",$filename);

                // 如果是传pdf的，则无需转化文件名称
                $sExtension = strrchr($filename, '.');//找到扩展名

                //文件不存在才上传
                $filename_new = '/storage/app/uploads/'.$filename;
                $is_file = "http://$_SERVER[HTTP_HOST]/storage/app/uploads/".$filename;
                if($this->check_file_exists($is_file) == false)
                {

                    $MAXIMUM_FILESIZE = 30 * 1024 * 1024; 	//文件大小限制	30M = 20 * 1024 * 1024 B;
                    $rEFileTypes = "/^\.(jpg|jpeg|gif|png|pdf){1}$/i";
                    if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE &&
                        preg_match($rEFileTypes, $sExtension)) {
                        if(!move_uploaded_file($_FILES[$upload_file_name]['tmp_name'],$dir.$filename))
                        {
                            $res = [
                                'rsp'=>'error',
                                'msg'=>json_decode(json_encode('上传失败',JSON_UNESCAPED_UNICODE)),
                            ];
                            return response()->json($res);
                        }
                    }else
                    {
                        $res = [
                            'rsp'=>'error',
                            'msg'=>json_decode(json_encode('上传失败：文件格式不对或文件大小超过30M',JSON_UNESCAPED_UNICODE)),
                        ];
                        return response()->json($res);
                    }
                }
                $filePath[] = $filename_new;
                $index++;
            }
            // 成功
            $res = [
                'rsp'=>'succ',
                'msg'=>json_decode(json_encode($filePath,JSON_UNESCAPED_UNICODE)),
            ];
            return response()->json($res);
        }else{
            $res = [
                'rsp'=>'error',
                'msg'=>json_decode(json_encode('失败：请求方式为非post！',JSON_UNESCAPED_UNICODE)),
            ];
            return response()->json($res);
        }
    }
    
    // 判断是否存在该文件
    public function check_file_exists($file)
    {
        if(strtolower(substr($file, 0, 4))=='http')
        {
            // 远程
            $header = get_headers($file, true);
            return isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'));
        }else
        {
            // 本地
            return false;
        }
    }
}
