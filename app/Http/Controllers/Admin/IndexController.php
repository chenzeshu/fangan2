<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;

class IndexController extends CommonController
{
    public function index()
    {
        return view('admin.index');
    }

    public function index2()
    {
        return view('admin.index2');
    }

    public function pass()
    {

        if ($input = Input::all()){
            $rules = [
                'password_o'=> 'required',
                'password'=> 'required|between:6,20|confirmed'
            ];
            $msg = [
                'password_o.required'=> '[原密码]未填写',
                'password.required'=> '[新密码]未填写',
                'password.between'=> '[新密码]长度应在6-20位',
                'password.confirmed'=> '[两次填写密码]不一致'
            ];
          $validator = \Illuminate\Support\Facades\Validator::make($input,$rules,$msg);
         if ($validator->passes()){
             $_pass = $input['password_o'];
             $pass =  $input['password'];
             $name = Session::get('username');
             $user = User::where('user_name',$name)->first();
             if (Crypt::decrypt($user->user_pass)==$_pass){
                 $user->user_pass = Crypt::encrypt($pass);
                 $user->update();
                 return back()->with('errors','修改成功');
             }else{
                 return back()->with('errors','原密码错误');
             }
         }else{
             return back()->withErrors($validator);
         }
        }
        return view('admin.pass');
    }

}
