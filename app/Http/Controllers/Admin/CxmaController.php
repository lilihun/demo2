<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\WxController;
use Illuminate\Support\Facades\Config;

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
    public function index()
    {
        $data['res'] = DB::table("cx_ma")
            ->orderBy("order",'desc')
            ->paginate(10);
        $data['cx_status'] = Config::get("params.cx_status");
        $data['label'] = '小程序码';
        return view("admin.cxma.index")->with($data);
    }

//    第一步：生成小程序码页面
    public function addFirst()
    {
        $data['category1'] = Config::get("params.cx_category1");
        $data['category2'] = Config::get("params.cx_category2");

        return view("admin.cxma.add_first")->with($data);
    }

//    第一步：处理--生成小程序码
    public function do_addFirst(Request $request)
    {
        $data = $request->input("ht");
        // 第一步 判断分类属于前台码还是通用码
        // TODO 这一步业务需求待确认中 ...

        // 第二步，判断参数的正确性  TODO scene为中文的情况生成的小程序码是不显示的
        // TODO 这一步业务需求待确认中 ...
        $data['status'] = '1';

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
            return back()->with("error","生成小程序码失败");
        }

        // 开启事务
        DB::beginTransaction();
        $insert = DB::table("cx_ma")->insert($data);
        if(!$insert){
            DB::rollBack();
            return back()->with("error","插入表数据失败");
        }
        DB::commit();
        return redirect("admin/cxma/index")->with("success","生成小程序码成功");
    }

    //    第二步：生成海报页面
    public function addSecond(Request $request)
    {
        $id = $request->id;
        $data['res'] = DB::table("cx_ma")->where("id",$id)->first();
        if(!isset($data['res']->id))
        {
            return back()->with("error","获取小程序码数据失败，请重试！");
        }

        //获取海报
        $data['haibao'] = DB::table("cx_haibao")->where("is_show",'1')->get();

        $data['category1'] = Config::get("params.cx_category1");
        $data['category2'] = Config::get("params.cx_category2");

        return view("admin.cxma.add_second")->with($data);
    }

    //    第二步：处理--生成小程序码
    public function do_addSecond(Request $request)
    {
        $id = $request->id;
        $data = $request->input("ht");

        // 第二步，判断参数的正确性  TODO scene为中文的情况生成的小程序码是不显示的
        // TODO 这一步业务需求待确认中 ...
        $data['status'] = '2';

        // 第三步：带有二维码的海报
        $res_cxma = DB::table("cx_ma")->where("id",$id)->first();
        if(!isset($res_cxma->id)){
            return back()->with("error","获取小程序码数据失败，请重试！");
        }

        $config = array(
            'image'=>array(
                array(
                    'url'=>'.'.$res_cxma->xc_ma_url,            //二维码资源
                    'stream'=>0,
                    'left'=>$data['left'],
                    'top'=>-$data['top'],
                    'right'=>$data['right'],
                    'bottom'=>$data['bottom'],
                    'width'=>$data['width'],
                    'height'=>$data['height'],
                    'opacity'=>$data['opacity']
                )
            ),
            'background'=>'.'.$data['haibao']                    //背景图
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
            return back()->with("error","生成海报失败");
        }
        $data['haibao_url'] = substr($haibao_url,1); //去除.
        $data['update_time'] = time();
        unset($data['haibao']);
        // 开启事务
        DB::beginTransaction();
        $insert = DB::table("cx_ma")->where("id",$id)->update($data);
        if(!$insert){
            DB::rollBack();
            return back()->with("error","更新表数据失败");
        }
        DB::commit();
        return redirect("admin/cxma/index")->with("success","生成海报成功");
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
}
