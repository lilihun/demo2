<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\WxController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

/**
 * 小程序码
 * 创建时间：2019-03-24 12:30
 * 制作人：【老弟来了网制工作室】
 * Class NewsController
 * @package App\Http\Controllers\Admin\Info
 */
class CxmaController extends Controller
{
    public function __construct()
    {
        // 实例化
        $this->Wxapi = new WxController();
        $this->label_name = '小程序码管理';
    }

    // 调试页面
    public function test()
    {
        // 生成小程序码
//        $res = $this->Wxapi->getWxcode();
//        $path = "./storage/app/uploads/cxma/";
//        $res22 = $this->Wxapi->base64_image_content($res,$path);
//        dd($res22);


        // 生成带有二维码的海报
        $config = array(
            'image'=>array(
                array(
                    'url'=>'./weixin/img/cx.png',            //二维码资源
                    'stream'=>0,
                    'left'=>116,
                    'top'=>-216,
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>178,
                    'height'=>178,
                    'opacity'=>100
                )
            ),
            'background'=>'./weixin/img/beijing.png'                    //背景图
        );

        $filename = './weixin/img/new'.time().'.png';
        $res = $this->Wxapi->createPoster($config,$filename);
        echo '<pre>';print_r($res);exit();
    }


    // 列表
    public function index(Request $request)
    {
        $data['type'] = isset($request->type) ? $request->type : '';

        switch($data['type']){
            case 'TYMA':
                $data['res'] = DB::table("cx_ma")
                    ->orderBy("order",'desc')
                    ->where("category1",$data['type'])
                    ->paginate(5);
                break;
            case 'QTMA':
                $data['res'] = DB::table("cx_ma")
                    ->orderBy("order",'desc')
                    ->where("category1",$data['type'])
                    ->paginate(5);
                break;
            case 'MDMA':
                $data['res'] = DB::table("cx_ma")
                    ->orderBy("order",'desc')
                    ->where("category2",$data['type'])
                    ->paginate(5);
                break;
            case 'GRMA':
                $data['res'] = DB::table("cx_ma")
                    ->orderBy("order",'desc')
                    ->where("category2",$data['type'])
                    ->paginate(5);
                break;
            default:
                $data['res'] = DB::table("cx_ma")
                    ->orderBy("order",'desc')
                    ->paginate(5);
                break;
        }
        $data['cx_status'] = Config::get("params.cx_status");
        $data['category1'] = Config::get("params.cx_category1");
        $data['category2'] = Config::get("params.cx_category2");

        $data['label'] = '小程序码';
        return view("admin.cxma.index")->with($data);
    }

//    第一步：生成小程序码页面
    public function addFirst(Request $request)
    {
        if(isset($request->id) && $request->id != ''){
            // 编辑
            $data['res'] = $res = DB::table("cx_ma")->where("id",$request->id)->first();
            if(!isset($data['res']->id)){
                return back()->with("error","编辑失败！【该ID已被删除或不存在,请重试！】");
            }

        }else{
            // 新增
            // 暂无操作
        }
        $data['category1'] = Config::get("params.cx_category1");
        $data['category2'] = Config::get("params.cx_category2");
        //获取海报
        $data['haibao'] = DB::table("cx_haibao")->where("is_show",'1')->get();
        return view("admin.cxma.add_first")->with($data);
    }

//    第一步：处理--生成小程序码
    public function do_addFirst(Request $request)
    {
        $data = $request->input("ht");
//        return back()->withInput($data)->with("error","失败！");
        // 第一步 判断分类属于门店码还是个人码
        switch($data['category2']){
            case 'MDMA':
                if(!isset($data['hotel_msg']) || $data['hotel_msg'] ==''){
                    return back()->withInput($data)->with("error","操作失败！【酒店信息不能为空！】");
                }
                break;
            case 'GRMA':
                if(!isset($data['sales_mag']) || $data['sales_mag'] ==''){
                    return back()->withInput($data)->with("error","操作失败！【销售人员信息不能为空！】");
                }
                break;
            default:
                return back()->withInput($data)->with("error","操作失败！【二级分类数据异常，请重试！】");
                break;
        }

        // 第二步，判断参数的正确性  TODO scene为中文的情况生成的小程序码是不显示的
        // TODO 这一步业务需求待确认中 ...

        // 生成小程序码

        DB::beginTransaction();        // 开启事务
        $data['status'] = '2';
        $data['create_time'] = time();
        // 第三步：生成小程序码
        $params = [
            'page'=>'pages/order/home/home',
            'scene'=>$data['sales_mag'],
        ];
        $res_1 = $this->Wxapi->getWxcode($params);
        $path = "./storage/app/uploads/cxma/";
        $res_2 = $this->Wxapi->base64_image_content($res_1,$path);
        if($res_2){
            $data['xc_ma_url'] = substr($res_2,1);
        }else{
            return back()->withInput($data)->with("error","保存失败！【生成小程序码失败】");
        }

        // 判断一级分类属于通用码还是前台码
        switch($data['category1']){
            case 'TYMA': // 通用码
                // 无需生成海报
                if(isset($request->id) && $request->id !=''){
                    // 更新操作
                    $data['update_time'] = time();
                    $up = DB::table("cx_ma")->where("id",$request->id)->update($data);
                    if($up === false){
                        DB::rollBack();
                        return back()->withInput($data)->with("error","保存失败！【更新表数据失败】");
                    }
                }else{
                    // 新增操作
                    $insert = DB::table("cx_ma")->insert($data);
                    if(!$insert){
                        DB::rollBack();
                        return back()->withInput($data)->with("error","保存失败！【插入表数据失败】");
                    }
                }

                break;
            case 'QTMA':// 前台码
                // 需要生成海报

                //根据海报ID查询海报URL
                $res_haiba = DB::table("cx_haibao")->where("id",$data['haibao_id'])->first();
                if(!isset($res_haiba->id) && $res_haiba->id == ''){
                    // 海报不能为空
                    return back()->withInput($data)->with("error","保存失败！【该海报ID不存在资源，请重试！】");
                }
                $background = '.'.$data['xc_ma_url'];//二维码资源
                //方法
                $backgroundInfo = getimagesize($background);
                $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
                $background = $backgroundFun($background);
                $backgroundWidth = imagesx($background);    //宽度
                $backgroundHeight = imagesy($background);   //高度

                $config = array(
                    'image'=>array(
                        array(
                            'url'=>'.'.$data['xc_ma_url'],            //二维码资源
                            'stream'=>0, // TODO 待优化
                            'left'=>$data['left'],
                            'top'=>-$data['top'],
                            'right'=>$data['right'],
                            'bottom'=>$data['bottom'],
                            'width'=>$backgroundWidth,
                            'height'=>$backgroundHeight,
                            'opacity'=>$data['opacity']
                        )
                    ),
                    'background'=>'.'.unserialize($res_haiba->url)[0]                    //背景图
                );

                $path = "./storage/app/uploads/cxma/";
                $new_file = $path.date('Ymd', time()) . "/";
                if (!file_exists($new_file)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }

                $filename = $new_file.time().'.jpg';
                $haibao_url = $this->Wxapi->createPoster($config,$filename);
                if(!$haibao_url){
                    return back()->withInput($data)->with("error","保存失败！【生成海报失败】");
                }
                $data['haibao_url'] = substr($haibao_url,1); //去除.
                unset($data['haibao']);

                if(isset($request->id) && $request->id !=''){
                    // 更新操作
                    $data['update_time'] = time();
                    $up = DB::table("cx_ma")->where("id",$request->id)->update($data);
                    if($up === false){
                        DB::rollBack();
                        return back()->withInput($data)->with("error","保存失败！【更新表数据失败】");
                    }

                }else{
                    // 新增操作
                    $insert = DB::table("cx_ma")->insert($data);
                    if(!$insert){
                        DB::rollBack();
                        return back()->withInput($data)->with("error","保存失败！【保存表数据失败】");
                    }
                }

                break;
            default:
                return back()->withInput($data)->with("error","操作失败！【一级分类数据异常，请重试！】");
                break;
        }

        DB::commit();
        return redirect("admin/cxma/index")->with("success","保存成功！");
    }

    /**
     * 删除
     */
    public function destroy(Request $request)
    {
        $ht_id = $request->input("ht_id");
        if(is_numeric($ht_id)===false || empty($ht_id)){
            $data = [
                'status' => 1,
                'msg' => '该ID已不存在或已被删除，请重试！',
            ];
            return $data;
        }
        $re = DB::table("cx_ma")
            ->where("id",$ht_id)
            ->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '删除成功！',
            ];
            return $data;
        }else{
            $data = [
                'status' => 1,
                'msg' => '删除失败，请稍后重试！',
            ];
            return $data;
        }
    }




    /****************************海报管理*************************************/

    // 海报列表
    public function hb_index()
    {
        $data['res'] = DB::table("cx_haibao")
            ->paginate(5);
        $data['label'] = '海报';
        return view("admin.cxma.hb_index")->with($data);
    }

    // 海报编辑
    public function hb_edit(Request $request)
    {
        $is_same = 'false'; // 检测海报是否生成前台码
        if(isset($request->id) && $request->id != '')
        {
            // 存在编辑
            $data['res'] = $res = DB::table("cx_haibao")->where("id",$request->id)->first();
            if(!isset($res->id)){
                return back()->with("error","当前数据异常，请重试!");
            }
            // 检测该海报是否已生成前台码
            $count = DB::table("cx_ma")->where("haibao_id",$request->id)->count();
            $is_same = $count>0 ? 'true' : 'false'; // true 代表已存在
            $data['label'] = "编辑海报";
        }else
        {
            // 不存在新增
            $data['label'] = '新增海报';
        }

        $data['is_same'] = $is_same;
        return view("admin.cxma.hb_edit")->with($data);
    }

    // 保存海报
    public function hb_store(Request $request)
    {
        $input = $request->input("ht");
        // 检测参数是否为必填
        if(!isset($input['url']) || !isset($input['title'])){
            return back()->with("error","操作失败！【标题或海报为必填项！】");
        }
        // 处理图片URL
        $input['url'] = serialize($input['url']);

        if(isset($request->id) && $request->id!='')
        {
            // 【更新操作】
            $data['res'] = $res = DB::table("cx_haibao")->where("id",$request->id)->first();
            if(!isset($res->id)){
                return back()->with("error","操作失败！【当前数据异常，请重试!】");
            }
            $input['update_time'] = time();
            $up = DB::table("cx_haibao")->where("id",$request->id)->update($input);
            if($up === false){
                return back()->with("error","操作失败！【更新失败，请重试!】");
            }
        }else
        {
            // 【新增操作】
            $input['create_time'] = time();
            $insert = DB::table("cx_haibao")->insert($input);
            if(!$insert){
                return back()->with("error","操作失败！【保存失败，请重试!】");
            }
        }
        return redirect("admin/hb_cxma/index")->with("success","操作成功！");
    }

    // 保存海报
    public function hb_destroy(Request $request)
    {
        $ht_id = $request->id;
        if(is_numeric($ht_id)===false || empty($ht_id)){
            return back()->with("error",'删除失败！【该ID已不存在或已被删除，请重试！】');
        }
        // 检测该海报是否已生成前台码
        $count = DB::table("cx_ma")->where("haibao_id",$ht_id)->count();
        if($count>0){
            return back()->with("error",'删除失败！【该海报已生成前台码，请先移除对应的前台码】');
        }

        $re = DB::table("cx_haibao")
            ->where("id",$ht_id)
            ->delete();
        if($re){
            return redirect("admin/hb_cxma/index")->with("success",'删除成功');
        }else{
            return back()->with("error",'删除失败！【请稍后重试！】');
        }
    }

}
