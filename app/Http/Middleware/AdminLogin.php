<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
class AdminLogin
{
    /**
     * 后台登陆中间件
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!session('user')){
            return redirect('admin/login');
        }

        $autrue=0; // 权限检测是否通过
        $isAu=0; // 是否要检测权限
        //权限对比
        $uri = $_SERVER['REQUEST_URI']; //  当前路径
        $gid= session('user.gid');
        if($gid==0){
            //超级管理员
            $autrue = 1;
        }else{
            $Authority = Config::get("Authority.AuthorList");   //获取权限检查项
            foreach($Authority as $k=>$v){
                foreach ($v as $k1=>$v1){
                    foreach($v as$k2=>$v2){
                        foreach($v2 as $k3=>$v3){
//                            if (strpos($k3,$uri) !== false){
                                if (strpos($uri,$k3) !== false){  // $uri 在该字符串中进行查找
                                //存在
                                $isAu=1;
                                break;
                            }
                        }

                    }
                }
            }
            //需要检测
            if($isAu==1){
                $angr = DB::table('group')->where('gid',$gid)->first();
                if(($angr->author!='') &&($angr->author!=' ')&&($angr->author!='N;') && isset($angr->author)){
                    $aulis = unserialize($angr->author);
                    foreach($aulis as $k=>$v){
                        foreach ($v as $k1=>$v1){
                            foreach($v1 as$k2=>$v2){
//                                if (strpos($v2,$uri)!==false){
                                    if (strpos($uri,$v2)!==false){
                                    //存在    //检测通过
                                    $autrue=1;
                                    break;
                                }
                            }
                        }
                    }
                }
//                    //检测不通过  输出报错
                if($autrue==0){
                    return back()->with("error","对不起，您暂无操作权限！");
                }
            }


        }

        return $next($request);
    }
}
