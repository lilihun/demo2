<?php
/**
 * 用户及权限管理
 * Class CategoryController
 * @package App\Http\Controllers\Admin
 */
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AuthorityController extends Controller{
    public function __construct()
    {
//        角色权限管理的一级、二级分类名称
        $this->aulis = Config::get("Authority.cateName");
    }

    public function userlist(){
        // 账号列表
        $userlist = DB::table('user')->where('gid','!=','0')->get();
        foreach ($userlist as $k=>$v){
            $res = DB::table('group')->where('gid',$v->gid)->first();
            if(isset($res->name)){
                $userlist[$k]->gname = $res->name;
            }
        }
        return view('admin.account.user.list')->with(['userlist'=>$userlist]);
    }
    public function useradd(){
        // 账号添加

        //读取所有的权限组
        $userlist = DB::table('group')->get();
        return view('admin.account.user.add')->with(['userlist'=>$userlist]);
    }

    public function useredit(Request $request){
        // 账号编辑

        $list = DB::table('user')->where('user_id',$request->id)->first();
//        $list->user_pass = Crypt::decrypt($list->user_pass);
        $list->user_pass = $list->user_pass;

        //读取所有的权限组
        $userlist = DB::table('group')->get();
        return view('admin.account.user.edit')->with(['userlist'=>$userlist,'detail'=>$list]);
    }


    public function usersaves(Request $request){
        // 账号保存

        //两次输入的密码校验
        if($request->user_pass != $request->user_passconfirm){
            return back()->with("error",'两次输入密码不一致');
        }
        $where = ['user_name'=>$request->user_name];

        $res = DB::table('user')->where($where)->first();

        $user_pass = '';
        if(isset($request->id)){
            //编辑
            $user_pass = $request->user_pass;
            if($res->user_id == $request->id){
                //清除
                $res =null;
            }
        }
        if($res){
            //存在同登录名账号;
            return back()->with("error",'操作失败,请检查是否存在同名登陆账号');
        }
        $data = array(
            'user_name'=>$request->user_name,
            'user_pass'=>sha1($request->user_pass),
            'gid'=>$request->gid,
        );
        if($request->id){
            //修改保存
            if($user_pass == $request->user_pass){
                $data['user_pass'] = $request->user_pass; //无需sha1加密
            }
            $res = DB::table('user')->where('user_id',$request->id)->update($data);
        }else{
            //新增保存
            $res = DB::table('user')->insertGetId($data);
        }
        if ($res===false){
            return back()->with("error",'操作失败');
        }else{
            return redirect("/account/user/list")->with("success",'操作成功');
        }


    }

    public function userdel(Request $request){
        $user_id = $request->id;
        $res = DB::table('user')->where('user_id',$user_id)->delete();
        if ($res===false){
            return back()->with("error",'操作失败');
        }else{
            return redirect("/account/user/list")->with("success",'操作成功');
        }
    }


    /*--------------------------------权限组相关操作----------------------------------*/
    public function Rolist(){
        // 权限组列表
        $userlist = DB::table('group')->get();
        return view('admin.account.roles.list')->with(['userlist'=>$userlist]);
    }

    public function Roadd(){
        // 权限组新增

        $Authority = Config::get("Authority.AuthorList");
        $data=[
            'author'=>$Authority,
            'aulis'=>$this->aulis,
        ];
        return view('admin.account.roles.add')->with($data);
    }

    public function Roedit(Request $request){
        // 权限组编辑
        $gid = $request->gid;
        $userlist = DB::table('group')->where('gid',$gid)->first();
        $userlist->author = unserialize( $userlist->author);
        $Authority = Config::get("Authority.AuthorList");
        $data=[
            'userlist'=>$userlist,
            'author'=>$Authority,
            'aulis'=>$this->aulis,
            'gid'=>$gid,
        ];
        return view('admin.account.roles.edit')->with($data);
    }
    public function  Rosave(Request $request){
        if(isset($request->gid)){
            //保存
            $data['author'] = serialize($request->workground);
            $data['name'] = $request->role_name;
            $res = DB::table('group')->where('gid',$request->gid)->update($data);
           if($res ==1){
               //成功
               return redirect("/account/roles/list")->with("success",'操作成功');
           }else{
               return back()->with("error",'操作失败');
           }
        }else{
            //  新增
            $data['author'] = serialize($request->workground);
            $data['name'] = $request->role_name;
            $res = DB::table('group')->insertGetId($data);
            if(!$res){
                return back()->with("error",'操作失败');
            }else{
            //  成功
                return redirect("/account/roles/list")->with("success",'操作成功');

            }
        }
    }
    public function Rodel(Request $request){
        $gid = $request->gid;
        $userres = DB::table('user')->where('gid',$gid)->first();
        if($userres){
            return back()->with("error",'该角色下还存在账号,若要删除,请先移动此角色下的账号');
        }
        $res = DB::table('group')->where('gid',$gid)->delete();
        if ($res===false){
            return back()->with("error",'操作失败');
        }else{
            return redirect("/account/roles/list")->with("success",'操作成功');
        }
    }

}
