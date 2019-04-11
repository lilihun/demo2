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

// 小程序码
    Route::get('/admin/cxma/index', 'Admin\CxmaController@index');
    Route::any('/admin/cxma/create', 'Admin\CxmaController@create');
    Route::post('/admin/cxma/store', 'Admin\CxmaController@store');
    Route::any('/admin/cxma/edit/{id}', 'Admin\CxmaController@edit')->where('id', '[0-9]+');
    Route::any('/admin/cxma/destroy', 'Admin\CxmaController@destroy');
    Route::get('/admin/cxma/test', 'Admin\CxmaController@test'); // 调试接口
    
});

Route::any('/','IndexController@test');
