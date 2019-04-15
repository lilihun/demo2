<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 【公共API】
 *
 * 创建时间：2019-04-11 22:40
 * 制作人：【老弟来了网制工作室】
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class CommentController extends Controller
{
    /**
     * 文件上传（支持单多文件异步上传）
     *  ①：支持中文名称的文件、②：判断重复文件的、③：取消原先对文件名重命名、④：错误提示返回
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        if ($request->ajax()) {
            $index = 0;		//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
            $dir_base = "./storage/app/uploads/info/"; 	//文件上传根目录
            if(!is_file($dir_base)){
                mkdir($dir_base,777,true); // 允许创建多级目录文件夹
            }
            $filePath =[];  // 定义空数组用来存放图片路径
            foreach($_FILES as $file)
            {
                $upload_file_name = 'upload_file' . $index;		//对应index.html FomData中的文件命名

                // 转码中文
                $filename = $_FILES[$upload_file_name]['name'];

                $encode = mb_detect_encoding($filename,array('ASCII','UTF-8','GB2312','GBK','BIG5'));

                $filename = iconv($encode,"utf-8//ignore",$filename);

                // 如果是传pdf的，则无需转化文件名称
//                $filename = $_FILES[$upload_file_name]['name'];
                $sExtension = strrchr($filename, '.');//找到扩展名

                //文件不存在才上传
                $is_file = "http://$_SERVER[HTTP_HOST]/storage/app/uploads/info/".$filename;
                if($this->check_file_exists($is_file) == false)
                {
                    $MAXIMUM_FILESIZE = 30 * 1024 * 1024; 	//文件大小限制	30M = 20 * 1024 * 1024 B;
                    $rEFileTypes = "/^\.(jpg|jpeg|gif|png|pdf){1}$/i";
                    if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE &&
                        preg_match($rEFileTypes, $sExtension)) {
                        if(!@move_uploaded_file($_FILES[$upload_file_name]['tmp_name'],$dir_base.$filename)){
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
                $filePath[] = $filename;
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
        }else{
            // 本地
            return false;
        }
    }

    /*************************************【创建压缩文件】、【压缩文件下载】功能***************************************/

    /**
     *  创建压缩文件
     *
     * @param string $imgList 被压缩的文件集合  文件格式为： ['/file/download/img/1555294555.jpg','/file/download/img/1555294556.png']
     * 注意：【被压缩的文件  不能带有域名的，否则会返回错误】
     * @param string $dir     压缩文件存放地址
     * @return array|bool|string  返回json包 rsp 状态（'err'错误 'succ' 成功） msg 信息  data 数据 {"rsp":"err","msg":"【文件路径】不能为空 或【需要压缩的文件】不能为空、非数组","data":""}
     */
    public function createToZip($imgList='',$dir='')
    {
        // 判断需要压缩的文件是否存在
        if ($dir=='' || $imgList=='' || !is_array($imgList) || empty($imgList))
        {
            $msg = '【文件路径】不能为空 或【需要压缩的文件】不能为空、非数组';
            $data = [
                'rsp'=>'err',
                'msg'=>$this->toUtf8($msg),
                'data'=>'',
            ];
            return json_encode($data,JSON_UNESCAPED_UNICODE); // json不要编码unicode 中文不转码

        } else
        {
            // 判断是否带有$_SERVER['DOCUMENT_ROOT']
            if (strpos($dir, $_SERVER['DOCUMENT_ROOT']) === false)
            {
                // 没有则补上绝对路径
                $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
            }
            // 创建目录
            if (!file_exists($dir))
            {
                @mkdir($dir, 777, true);
            }

            $filename = $dir . date('YmdHis') . ".zip"; // 最终生成的文件名（含路径）

            //生成压缩文件
            $zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
            if ($zip->open($filename, \ZipArchive::OVERWRITE) !== true)
            {  //OVERWRITE 参数会覆写压缩包的文件 文件必须已经存在
                if ($zip->open($filename, \ZipArchive::CREATE) !== true)
                { // 文件不存在则生成一个新的文件 用CREATE打开文件会追加内容至zip
                    $msg = '无法打开文件，或者文件创建失败!';
                    $data = [
                        'rsp'=>'err',
                        'msg'=>$this->toUtf8($msg),
                        'data'=>'',
                    ];
                    return json_encode($data,JSON_UNESCAPED_UNICODE); // json不要编码unicode 中文不转码
                }
            }

            foreach ($imgList as $file)
            {
                // 被压缩的文件改成绝对路径
                if (strpos($file, $_SERVER['DOCUMENT_ROOT']) === false)
                {
                    $file = $_SERVER['DOCUMENT_ROOT'] . $file;// 没有则补上绝对路径
                }

                //这里直接用原文件的名字进行打包，也可以直接命名，需要注意如果文件名字一样会导致后面文件覆盖前面的文件，所以建议重新命名
                if (!file_exists($file))
                {
                    $msg = '需要压缩的文件不存在：'. $file;
                    $data = [
                        'rsp'=>'err',
                        'msg'=>$this->toUtf8($msg),
                        'data'=>'',
                    ];
                    return json_encode($data,JSON_UNESCAPED_UNICODE); // json不要编码unicode 中文不转码
                }
                $zip->addFile($file, basename($file));
            }
            $zip->close(); // 关闭

            // 调用【压缩文件下载】方法
            $res_zip = $this->downToZip($filename);
            return $res_zip;
        }
    }

    /**
     * 压缩文件下载
     *
     * @param string $file_name 压缩文件url
     * @return array|bool|string  返回json包 rsp 状态（'err'错误 'succ' 成功） msg 信息  data 数据 {"rsp":"err","msg":"【文件路径】不能为空 或【需要压缩的文件】不能为空、非数组","data":""}
     */
    public function downToZip($file_name='')
    {
//        $file_name = iconv("UTF-8","gb2312",$file_name);               //如果文件名包含中文，必须先转为GB2312编码
        $file_name = $this->toUtf8($file_name);               //如果文件名包含中文，必须先转为GB2312编码

        if(!file_exists($file_name)){ //检查文件是否存在
            $msg = '压缩文件不存在：'. $file_name;
            $data = [
                'rsp'=>'err',
                'msg'=>$this->toUtf8($msg),
                'data'=>'',
            ];
            return json_encode($data,JSON_UNESCAPED_UNICODE); // json不要编码unicode 中文不转码
        }
        $file_size = filesize($file_name);
        $fp=fopen($file_name,'r'); //打开文件
        //输入文件标签
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".$file_size);
        header("Content-Disposition: attachment; filename=".basename($file_name));
        $buffer = 1024;
        $file_count = 0;
        while (!feof($fp) && $file_size - $file_count > 0){
            $file_data =  fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_data;
        }
        fclose($fp);
        unlink($file_name); //删除文件
        $msg = 'download success';
        $data = [
            'rsp'=>'succ',
            'msg'=>$this->toUtf8($msg),
            'data'=>'',
        ];
        return json_encode($data,JSON_UNESCAPED_UNICODE); // json不要编码unicode 中文不转码
    }

    // 编码格式更换
    public function toUtf8($msg='')
    {
        $encode = mb_detect_encoding($msg,array('ASCII','UTF-8','GB2312','GBK','BIG5'));

        $filename = iconv($encode,"utf-8//ignore",$msg);

        return $filename;
    }

    /**
     * 方法1：检测远程文件是否存在（暂时用不到）
     *
     * 需要开启allow_url_fopen
     * @param string $url
     * @return bool
     */
    public function file_exists_allow($url='')
    {
        $fileExists = @file_get_contents($url, null, null, -1, 1) ? true : false;
        return $fileExists; // 返回1 ， 说明文件存在。
    }

    /**
     * 方法2：检测远程文件是否存在（暂时用不到）
     *
     * 需要服务器支持Curl组件
     * @param string $url 需要检测的文件url
     * @return bool  返回1，说明存在。
     */
    public function check_remote_file_exists($url='')
    {
        $curl = curl_init($url); // 不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // 发送请求
        $result = curl_exec($curl);
        $found = false; // 如果请求没有发送失败
        if ($result !== false) {

            /** 再检查http响应码是否为200 */
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $found = true;
            }
        }
        curl_close($curl);

        return $found;
    }

    /*************************************【创建压缩文件】、【压缩文件下载】功能***************************************/

}
