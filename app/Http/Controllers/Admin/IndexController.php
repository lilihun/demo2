<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repository\AdminRepository;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\HotelApiController;

/**
 * 后台首页
 * 创建时间：2019-03-24 10:52
 * 制作人：【老弟来了网制工作室】
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class IndexController extends Controller
{
    /**
     * 管理员主页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * 管理员信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminInfo()
    {
        return view('admin.users.admin');
    }
}
