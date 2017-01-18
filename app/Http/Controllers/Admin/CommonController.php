<?php

namespace App\Http\Controllers\Admin;

use App\Model\Pros;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    /*
     * 借用宝地做下Controller说明
     * Index-首页
     * Login-登录、注册和修改密码  //自定义
     * Parms-汇率等参数  // resource
     * Pros-设备数据库  // resource
     * Self-个人空间   //  resource
     * Fangan-个人空间的个人表    //自定义
     */
    //图片上传
    public function upload()
    {
        $file = Input::file('Filedata');
        if ($file->isValid()){
            $entension = $file ->getClientOriginalExtension(); //文件后缀
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
            $path = $file ->move(base_path().'/uploads',$newName);
            $filepath = 'uploads/'.$newName;
            return $filepath;
        }
    }
    //删除队列的设备图片 || 其他图片
    public function delimg()
    {
        $input = Input::except('_token');
        //这里不用对数据库做任何操作，edit操作是表单的事情…………
        if (unlink(base_path().'\\'.$input['imgName'])){
            return $alert = "删除成功";
        }

    }
}
