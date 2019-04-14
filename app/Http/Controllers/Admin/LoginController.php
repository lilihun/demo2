<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

require_once base_path().'/resources/org/code/Code.class.php';

/**
 * 后台登陆模块
 * 功能点：登陆校验、修改密码
 * 创建时间：2019-03-24 10:16
 * 制作人：【老弟来了网制工作室】
 *
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends Controller
{
    /**
     * 登陆校验
     * 说明：加密方式采用sha1加密
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function login()
    {
        // 原始密码 统一 admin/admin
        if($input = Input::all()){
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code){
                return back()->with('msg','验证码错误！');
            }
            $data = [
                'user_name'=>$input['user_name'],
            ];
            $user = DB::table("user")->where($data)->first();

            if($user->user_name != $input['user_name'] || $user->user_pass != sha1($input['user_pass'])){
                return back()->with('msg','用户名或者密码错误！');
            }
            $user_new = [
                'user_id'=>$user->user_id,
                'user_name'=>$user->user_name,
                'user_pass'=>$user->user_pass,
                'gid'=>$user->gid,
            ];
            session(['user'=>$user_new]);
            return redirect('admin');
        }else {
            return view('admin.login');
        }
    }

    public function quit()
    {
        session(['user'=>null]);
        return redirect('admin/login');
    }

    // 修改密码
    public function editPwd(Request $request)
    {
        $user_id = $request->id;
        $user = DB::table("user")->where("user_id",$user_id)->first();
        if(!$user){
            return back()->with('error',"当前用户不存在");
        }
        $data['user'] = $user;

        return view('admin.editpwd')->with($data);
    }

    // 确认修改密码
    public function doEditPwd(Request $request)
    {
        $user_id = $request->id;
        $old_pwd = $request->old_pwd;
        $new_pwd = trim($request->new_pwd);
        $r_new_pwd = trim($request->r_new_pwd);

        //判断两次输入的密码是否一致
        if($new_pwd != $r_new_pwd){
            return back()->with('error','两次输入的密码不一致！');
        }
        $match = "/^[a-zA-Z\d_]{5,}$/";
        if(!preg_match($match,$new_pwd)){
            return back()->with('error','新密码由数字跟字母组合并至少输入5位！');
        }

        // 判断原来的密码是否正确
        $data = [
            'user_id'=>$user_id,
        ];
        $user = DB::table("user")->where($data)->first();
        if(!isset($user->user_id)){
            return back()->with('error','不存在该用户');
        }
        if($user->user_pass != sha1($old_pwd)){
            return back()->with('error','输入的原密码不正确！');
        }

        // 修改密码
        $new_data = [
            'user_pass'=>sha1($new_pwd),
        ];

        $up = DB::table("user")->where("user_id",$user_id)->update($new_data);
        if(!$up){
            return back()->with('error','修改密码失败！');
        }

        session(['user'=>null]);
        return redirect('admin/login')->with("success","修改密码成功！");
    }

    /*
     * 图形码
     */
    public function code()
    {
        $code = new \Code;
        $code->make();
    }
}
