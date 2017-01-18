<?php

namespace App\Http\Controllers\Admin;

use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

require_once "resources/org/code/Code.class.php";

class LoginController extends CommonController
{
    public function login()
    {
        if ($input = Input::all()){
            $code = new \Code();
            $_code = $code->get();
            if (strtoupper($input['code'])==$_code){
                $user = User::where('user_name',$input['user_name'])->first();
                if ($user['user_name']==$input['user_name']&&Crypt::decrypt($user['user_pass'])==$input['user_pass']){
                    //保存session
                    Session::put('username',$user['user_name']);
                    Session::put('userid',$user['id']);
                    return redirect('admin/index');
                }else{
                    return back()->with('msg','用户名或密码错误');
                }
            }else{
                return back()->with('msg','验证码错误');
            }
        }elseif (session('username')){
            return redirect('admin/index');
        }
        return view('admin/login');

     }

    public function code()
    {
          $code = new \Code;
          echo $code->make();
     }

    public function logout()
    {
        session(['userid'=>null]);
        session(['username'=>null]);
        return redirect('admin/login');
    }

    public function register()
    {
        if ($input = Input::all()){
            $rules = [
                'user_name'=>'required',
                'password'=> 'required|between:6,20|confirmed',
                'user_type'=>'required'
            ];
            $msg = [
                'user_name.required'=> '[用户名]未填写',
                'password.required'=> '[密码]未填写',
                'password.between'=> '[密码]长度应在6-20位',
                'password.confirmed'=> '[两次填写密码]不一致',
                'user_tpye'=>'[用户类型]未选择'
            ];
            $validator = \Illuminate\Support\Facades\Validator::make($input,$rules,$msg);

            if ($validator->passes()){
                $name =$input['user_name'];
                $password = $input['password'];
                $role_name = $input['user_type'];
                $if = User::where('user_name',$name)->first();
                if($if){
                    return back()->with('msg','用户名已存在');
                }else{
                    $_password = Crypt::encrypt($password);
                    //todo 注册账户
                    $re = User::create(['user_name'=>$name,'user_pass'=>$_password,'user_type'=>$role_name]);
                    $role = User::where('user_name',$name)->first();
                    //todo 赋权
                    $role->attachRole($role_name);
                    if($re){
                        return view('admin.success');
                    }else{
                        return back()->with('msg','没有连接到数据库');
                    }
                }
            }else{
                return back()->withErrors($validator);
            }
        }
        return view('admin.register');
    }

    public function index()
    {
        $data = User::orderBy('id')->paginate(15);
        return view('admin.userlist',compact('data'));
    }

    public function delete($id)
    {
        $re = User::where('id',$id)->delete();
        if($re){
            $data = [
                'status'=> 0,   //因为是ajax异步返回，所以返回一个json数据
                'msg' => '删除成功',
            ];
        }else{
            $data = [
                'status'=> 1,   //因为是ajax异步返回，所以返回一个json数据
                'msg' => '删除失败，请稍后重试',
            ];
        }
        return $data;
    }

    public function test(){
    	Input::except('_token');
    	$name = $_FILES;
    	dd($name);
    }
}
