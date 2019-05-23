<?php

/******************* 后台部分 *************************/
//登录模块
Route::group(['middleware' => ['web']], function () {

    Route::any('admin/login', 'Admin\LoginController@login');
    Route::get('admin/code', 'Admin\LoginController@code');
});
Route::group(['middleware' => ['web','admin.login']], function () {//web代表web.php路由，'admin.login'代表在kernel中间件注册的名称

    /**********登录退出修改密码管理****************/
    Route::any('admin', 'Admin\IndexController@index');//后台首页
    Route::any('admin/quit', 'Admin\LoginController@quit');//退出登录
    Route::any('admin/editPwd/{id}', 'Admin\LoginController@editPwd');// 修改密码
    Route::any('admin/doEditPwd/{id}', 'Admin\LoginController@doEditPwd');// 确认修改密码

    /**********模板管理****************/
    Route::get('/admin/demo/index', 'Admin\DemoController@index');
    Route::any('/admin/demo/create', 'Admin\DemoController@create');
    Route::post('/admin/demo/store', 'Admin\DemoController@store');
    Route::any('/admin/demo/edit/{id}', 'Admin\DemoController@edit')->where('id', '[0-9]+');
    Route::any('/admin/demo/destroy', 'Admin\DemoController@destroy');

    /**********【小程序码】生成小程序码、海报码****************/
    Route::any('/admin/cxma/index/{type?}', 'Admin\CxmaController@index'); // 列表
    Route::any('/admin/cxma/addFirst/{id?}', 'Admin\CxmaController@addFirst'); // 生成【小程序码】页面
    Route::any('/admin/cxma/do_addFirst', 'Admin\CxmaController@do_addFirst'); // 生成【小程序码】页面
    Route::post('/admin/cxma/destroy', 'Admin\CxmaController@destroy'); // 删除

    /**********【小程序码】海报管理****************/
    Route::get('/admin/hb_cxma/index', 'Admin\CxmaController@hb_index'); //海报列表
    Route::any('/admin/hb_cxma/edit/{id?}', 'Admin\CxmaController@hb_edit'); //编辑
    Route::post('/admin/hb_cxma/store/{id?}', 'Admin\CxmaController@hb_store'); // 保存
    Route::any('/admin/hb_cxma/destroy/{id}', 'Admin\CxmaController@hb_destroy'); // 删除

    /**********权限管理之账号管理****************/
    Route::get('/account/user/list','Admin\AuthorityController@userlist');//账号管理
    Route::get('/account/user/edit/{id}','Admin\AuthorityController@useredit');//编辑账号
    Route::get('/account/user/add','Admin\AuthorityController@useradd');//编辑账号
    Route::post('/account/user/saves','Admin\AuthorityController@usersaves');//保存账号
    Route::get('/account/user/del/{id}','Admin\AuthorityController@userdel');//删除账号

    /**********权限管理之角色管理****************/
    Route::get('/account/roles/list','Admin\AuthorityController@Rolist');//角色管理
    Route::post('/account/roles/save','Admin\AuthorityController@Rosave');//保存角色保存
    Route::get('/account/roles/edit/{gid}','Admin\AuthorityController@Roedit');//编辑角色页面
    Route::get('/account/roles/add','Admin\AuthorityController@Roadd');//新增角色页面
    Route::get('/account/roles/del/{gid}','Admin\AuthorityController@Rodel');//删除角色

    /*******************************************************
                    =====公共方法=====
    ******************************************************/
    Route::any('/admin/image/upload', 'Api\CommentController@upload'); // 公共方法---【异步文件上传】
});

/**********测试方法****************/
Route::get('/admin/cxma/test', 'Admin\CxmaController@test'); // 调试接口
Route::get('redis', 'Api\TestController@redis'); // 商城秒杀


Route::any('/','IndexController@test');
