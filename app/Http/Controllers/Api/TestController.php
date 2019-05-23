<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 【调试API】
 *
 * 创建时间：2019-04-24 18:26
 * 制作人：【老弟来了网制工作室】
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class TestController extends Controller
{
    // 测试商城秒杀+redis
    public function redis(Request $request)
    {
        $redis_name = 'seckill';
        // 模拟100人请求秒杀（高压力）
        for($i = 0;$i < 100; $i ++)
        {
            $uid = rand(10000000,99999999);
            //获取当前队列已经拥有的数量，如果少于10，则加入这个队列
            $num = 10;
            if(Redis::lLen($redis_name) < $num)
            {
                Redis::rPush($redis_name,$uid);
                echo $uid . '秒杀成功'."<br/>";exit();
            }else
            {
                // 如果当前队列人数已经达到10人，则返回秒杀已结束
                echo '秒杀已结束'."<br/>";exit();
            }
        }
    }
}
