<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\WxController;

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
        $res = $this->Wxapi->getWxcode();
        $path = "./storage/app/uploads/cxma/";
        $res22 = $this->Wxapi->base64_image_content($res,$path);
        dd($res22);


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

        $filename = './weixin/img/new'.time().'.jpg';
        $res = $this->Wxapi->createPoster($config,$filename);
        echo '<pre>';print_r($res);exit();
    }


    // 列表
    public function index()
    {
        $data['res'] = DB::table("cx_ma")
            ->orderBy("order",'desc')
            ->paginate(10);
        $data['label'] = '小程序码';
        return view("admin.cxma.index")->with($data);
    }
}
