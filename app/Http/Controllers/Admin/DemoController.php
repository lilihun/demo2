<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Demo 控制器模板
 * 创建时间：2019-03-24 12:30
 * 制作人：【老弟来了网制工作室】
 * Class NewsController
 * @package App\Http\Controllers\Admin\Info
 */
class DemoController extends Controller
{
    /**
     * 列表页面
     */
    public function index(Request $request)
    {
        $res = DB::table("demo")
            ->orderBy("id","desc")
            ->paginate(10);
        $data['res'] = $res;
        $data['label'] = 'Demo';
        return view('admin.demo.index')->with($data);
    }

    /**
     * 创建页面
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $index = 0;		//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
            $dir_base = "./storage/app/uploads/info/"; 	//文件上传根目录
            $filePath =[];  // 定义空数组用来存放图片路径
            foreach($_FILES as $file){
                $upload_file_name = 'upload_file' . $index;		//对应index.html FomData中的文件命名
//                $filename = $_FILES[$upload_file_name]['name'];
//                $gb_filename = iconv('utf-8','gb2312',$filename);	//名字转换成gb2312处理
                $sExtension = substr($_FILES[$upload_file_name]['name'], (strrpos($_FILES[$upload_file_name]['name'], '.') + 1));//找到扩展名
                $sExtension = strtolower($sExtension);
                $filename = date("YmdHis").rand(100, 200).'.'.$sExtension;

                //文件不存在才上传
                if(!file_exists($dir_base.$filename)) {
                    $isMoved = false;  //默认上传失败
                    $MAXIMUM_FILESIZE = 1 * 1024 * 1024; 	//文件大小限制	1M = 1 * 1024 * 1024 B;
                    $rEFileTypes = "/^\.(jpg|jpeg|gif|png){1}$/i";
                    if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE &&
                        preg_match($rEFileTypes, strrchr($filename, '.'))) {
                        $isMoved = @move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], $dir_base.$filename);		//上传文件
                    }
                }else{
                    $isMoved = true;	//已存在文件设置为上传成功
                }
                $filePath[] = $filename;
                $index++;
            }
            return response()->json(array('msg' => $filePath));
        }
        $data['label'] = 'Demo';
        return view("admin.demo.create")->with($data);
    }

    /**
     * 修改页面
     */
    public function edit(Request $request,$lm_id)
    {
        if ($request->ajax()) {
            $index = 0;		//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
            $dir_base = "./storage/app/uploads/info/"; 	//文件上传根目录
            $filePath =[];  // 定义空数组用来存放图片路径
            foreach($_FILES as $file){
                $upload_file_name = 'upload_file' . $index;		//对应index.html FomData中的文件命名
//                $filename = $_FILES[$upload_file_name]['name'];
//                $gb_filename = iconv('utf-8','gb2312',$filename);	//名字转换成gb2312处理
                $sExtension = substr($_FILES[$upload_file_name]['name'], (strrpos($_FILES[$upload_file_name]['name'], '.') + 1));//找到扩展名
                $sExtension = strtolower($sExtension);
                $filename = date("YmdHis").rand(100, 200).'.'.$sExtension;

                //文件不存在才上传
                if(!file_exists($dir_base.$filename)) {
                    $isMoved = false;  //默认上传失败
                    $MAXIMUM_FILESIZE = 1 * 1024 * 1024; 	//文件大小限制	1M = 1 * 1024 * 1024 B;
                    $rEFileTypes = "/^\.(jpg|jpeg|gif|png){1}$/i";
                    if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE &&
                        preg_match($rEFileTypes, strrchr($filename, '.'))) {
                        $isMoved = @move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], $dir_base.$filename);		//上传文件
                    }
                }else{
                    $isMoved = true;	//已存在文件设置为上传成功
                }
                $filePath[] = $filename;
                $index++;
            }
            return response()->json(array('msg' => $filePath));
        }

        $res_jies = DB::table("infonews")->where("news_id",$lm_id)->first();

        $data = [
            'res_jies'=>$res_jies,
        ];

        return view("admin.demo.edit")->with($data);
    }

    /**
     * 保存
     */
    public function store(Request $request)
    {
        if($request->isMethod('POST')) {
            $input = $request->except('_token');
            // 校验
            $this->validate($request, [
                'ht.title' => 'required',
//                'ht.pic' => 'required',
                'ht.order' => 'required|min:0|max:999|integer',
                'ht.disabled' => 'required|integer',
            ], [
                'required' => ':attribute 为必填项',
                'min' => ':attribute 长度不能小于0个字符',
                'max' => ':attribute 长度不能大于3个字符',
                'integer' => ':attribute 必须为数字',
            ], [
                'ht.title' => '新闻中心名称',
//                'ht.pic' => '新闻中心图片',
                'ht.order' => '排序',
                'ht.disabled' => '是否显示',
            ]);
            //实例化
            $job = new Infonews();
            $filer = [
                ['title',$input['ht']['title']],
            ];
            $is_same = $job->where($filer)->get();
            if(empty($is_same)){
                //如果添加的名称一致，则不允许保存
                return back()->with('error','添加的新闻中心名称已重复！');
            }else {
                $input['ht']['create_time'] = time();
                DB::beginTransaction();

                $news_id = Infonews::insertGetId($input['ht']);

                if($news_id)
                {
                    DB::commit();
                    return redirect('admin/infonews/index')->with('success', '添加成功！')->withInput();
                } else{
                    DB::rollBack();
                    return back()->with('error', '添加失败！')->withInput();
                }
            }
        }else{
            return back()->with('error', '提交方式有误！')->withInput();
        }
    }


    /**
     * 删除
     */
    public function destroy(Request $request)
    {
        $news_id = $request->input("news_id");
        if(is_numeric($news_id)===false || empty($news_id)){
            $data = [
                'status' => 1,
                'msg' => '该新闻中心ID已不存在或已被删除，请重试！',
            ];
            return $data;
        }
        $re = DB::table("infonews")
            ->where("news_id",$news_id)
            ->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '新闻中心删除成功！',
            ];
            return $data;
        }else{
            $data = [
                'status' => 1,
                'msg' => '新闻中心删除失败，请稍后重试！',
            ];
            return $data;
        }
    }

}
