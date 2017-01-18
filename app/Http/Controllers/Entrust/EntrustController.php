<?php

namespace App\Http\Controllers\Entrust;

use App\Model\Permission;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EntrustController extends Controller
{
    public function index()
    {
//        //todo 造角色
//        $owner = new Role();
//        $owner ->name = 'owner';
//        $owner->display_name = '集成规划所管理员'; // optional
//        $owner->description  = '集成规划所管理员'; // optional
//        $owner->save();
//
//        $admin = new Role();
//        $admin->name         = 'people';
//        $admin->display_name = '普通用户'; // optional
//        $admin->description  = '进来看看的人'; // optional
//        $admin->save();
//
//        //todo 用户绑角色
//        $user = User::where('user_name','admin')->first();
//        $user ->attachRole($owner);
//
//        //todo 造权限
//        $createPost = new Permission();
//        $createPost->name = "编辑能力";
//        $createPost->display_name = "编辑数据库";
//        $createPost->description = "编辑数据库";
//        $createPost->save();
//
//        $editUser = new Permission();
//        $editUser->name         = '写方案';
//        $editUser->display_name = '能去个人空间写方案'; // optional
//        $editUser->description  = '能去个人空间写方案'; // optional
//        $editUser->save();
//
//        //todo 角色绑定权限
////        $owner->attachPermission($createPost);
//        $owner->attachPermissions(array($createPost,$editUser));
//
////        $admin->attachPermission();
        echo Role::areRole(session('username'),'owner');

    }
}
