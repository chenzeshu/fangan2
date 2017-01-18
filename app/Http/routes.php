<?php

/*
 *    常用:session
 *    table     @string    包括了table_id
 *    table_id   @int    是个人第几张表
 *
 * */
Route::group(['middleware' => ['web']], function () {\
	//测试
	Route::any('test', 'Admin\loginController@test');
    //权限控制器
    Route::any('admin/auth', 'Entrust\EntrustController@index');
    //后台登陆
    Route::any('/', 'Admin\loginController@login');
    Route::any('admin/login', 'Admin\loginController@login');
    Route::get('admin/code', 'Admin\loginController@code');
    Route::get('admin/logout', 'Admin\loginController@logout');
    //Word 导出
    Route::get('word/wordexport','WordController@export');  //例子
    Route::get('word/export_fangan/{tablename}','WordController@export_word');
    //EXCEL导出
    Route::get('excel/export_fangan/{tablename}','ExcelController@export_fangan');
    //图片上传
    Route::any('upload','Admin\CommonController@upload');
    Route::any('delimg','Admin\CommonController@delimg');

    Route::get('excel/export_mingxi','ExcelController@export_mingxi');
    Route::get('excel/export_huizong1','ExcelController@export_huizong1');  //生成汇总_部门1Excel
    Route::get('excel/export_huizong2','ExcelController@export_huizong2');  //生成汇总_部门2Excel
    Route::get('excel/export_shui','ExcelController@export_shui');          //生成汇总_部门2Excel
    Route::get('excel/export_gongzi','ExcelController@export_gongzi');      //生成工资发放表
    Route::get('excel/export_jixiao','ExcelController@export_jixiao');      //生成绩效明细表
    Route::get('excel/export_jiangjin','ExcelController@export_jiangjin');  //生成越绩效奖金表
    Route::get('excel/export_jxhz1','ExcelController@export_jxhz1');        //生成绩效明细表
    Route::get('excel/export_jxhz2','ExcelController@export_jxhz2');        //生成绩效明细表
    Route::get('excel/export_sanbu','ExcelController@export_sanbu');        //生成绩效明细表
    //EXCEL导入
    Route::get('excel/import','ExcelController@import');
    //发送右键
    Route::get('mail','MailController@index');
    Route::any('mail/name/','MailController@laSend');      //专人发送
    Route::get('mail/all','MailController@allSend');      //全部发送
    Route::get('mail/send','MailController@send');       //phpmail
    Route::get('mail/laSend','MailController@laSend');  //自带mail模块
});

Route::group(['middleware'=>['web','admin.login'],'prefix'=>'admin','namespace' => 'Admin'], function () {
    //用户列表
    Route::any('user','loginController@index');
    //注册用户
    Route::any('register', 'loginController@register');
    //删除用户
    Route::any('delete/{id}', 'loginController@delete');
    //业务逻辑
    Route::any('index', 'IndexController@index');
    Route::any('index2', 'IndexController@index2');
    Route::any('pass', 'IndexController@pass');  //改密码
    Route::resource('pros', 'ProsController');  //设备数据库
    Route::any('prosearch','ProsController@proSearch'); //数据库 查询
    Route::any('prosshowdesandimg','ProsController@showDesAndImg'); //数据库 图文弹窗
    Route::any('prosshowparams','ProsController@showParams'); //数据库 图文弹窗
    Route::resource('params', 'ParamsController');  //参数表
    Route::resource('self', 'SelfController'); //个人空间
    Route::any('createSpace','SelfController@createSpace'); //创建个人空间
    Route::get('fangan/index/{id}','FanganController@index'); //小表主页
    Route::any('fangan/create','FanganController@create'); //小表 增加系统
    Route::any('fangan/checkid','FanganController@checkId'); //小表 添加设备  AJAX
    Route::any('fangan/edit/{sys_id}','FanganController@edit'); //小表     系统添加设备
    Route::any('fangan/delete/{id}','FanganController@delete'); //小表 删除
    Route::get('fangan/create','FanganController@create'); //小表 分系统页面
    Route::any('fangan/already/{sys}','FanganController@alreadyExist'); //小表 分系统页面 AJAX
    Route::any('fangan/reorder','FanganController@reOrder'); //小表 重新排序 AJAX
    Route::any('fangan/renumber','FanganController@reNumber'); //小表 改数字 AJAX
    Route::any('fangan/searchPros','FanganController@searchPros'); //小表 改数字 AJAX


});

Route::group(['middleware'=>['web','admin.login'],'namespace' => 'Entrust'], function () {
//权限分发
    Route::any('auth','EntrustController@index'); //小表 改数字 AJAX
});


