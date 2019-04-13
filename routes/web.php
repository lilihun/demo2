<?php

/******************* 后台部分 *************************/
//登录模块
Route::group(['middleware' => ['web']], function () {

    Route::any('admin/login', 'Admin\LoginController@login');
    Route::get('admin/code', 'Admin\LoginController@code');
});
Route::group(['middleware' => ['web','admin.login']], function () {//web代表web.php路由，'admin.login'代表在kernel中间件注册的名称
// 登录退出修改密码
    Route::any('admin', 'Admin\IndexController@index');//后台首页
    Route::any('admin/quit', 'Admin\LoginController@quit');//退出登录
    Route::any('admin/editPwd/{id}', 'Admin\LoginController@editPwd');// 修改密码
    Route::any('admin/doEditPwd/{id}', 'Admin\LoginController@doEditPwd');// 确认修改密码

// 模板
    Route::get('/admin/demo/index', 'Admin\DemoController@index');
    Route::any('/admin/demo/create', 'Admin\DemoController@create');
    Route::post('/admin/demo/store', 'Admin\DemoController@store');
    Route::any('/admin/demo/edit/{id}', 'Admin\DemoController@edit')->where('id', '[0-9]+');
    Route::any('/admin/demo/destroy', 'Admin\DemoController@destroy');

// 【小程序码】生成小程序码、海报码
    Route::get('/admin/cxma/index', 'Admin\CxmaController@index');
    Route::any('/admin/cxma/addFirst/{id?}', 'Admin\CxmaController@addFirst'); // 生成【小程序码】页面
    Route::any('/admin/cxma/do_addFirst', 'Admin\CxmaController@do_addFirst'); // 生成【小程序码】页面
    Route::any('/admin/cxma/addSecond/{id}', 'Admin\CxmaController@addSecond'); // 生成【海报】页面
    Route::any('/admin/cxma/do_addSecond/{id}', 'Admin\CxmaController@do_addSecond'); // 生成【海报】页面
    Route::post('/admin/cxma/destroy', 'Admin\CxmaController@destroy'); // 删除

// 【小程序码】海报管理
    Route::get('/admin/hb_cxma/index', 'Admin\CxmaController@hb_index'); //海报列表
    Route::any('/admin/hb_cxma/edit/{id?}', 'Admin\CxmaController@hb_edit'); //编辑
    Route::post('/admin/hb_cxma/store/{id?}', 'Admin\CxmaController@hb_store'); // 保存
    Route::post('/admin/hb_cxma/destroy', 'Admin\CxmaController@hb_destroy'); // 删除



    Route::get('/admin/cxma/test', 'Admin\CxmaController@test'); // 调试接口

    /*******************************************************
                    =====公共方法=====
    ******************************************************/
    Route::any('/admin/image/upload', 'Api\CommentController@upload'); // 公共方法---【异步文件上传】

});

Route::any('/','IndexController@test');
