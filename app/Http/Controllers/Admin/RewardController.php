<?php

namespace App\Http\Controllers\Admin;

use App\Model\Reward;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class RewardController extends CommonController
{
    public function index(){
        $data = Reward::orderBy('re_id','asc')->paginate(10);
        $links = $data->links();
        return view('admin/reward/index',compact('data'));
    }

    //get  admin/reward/create  添加商品
    public function create()
    {
        return view('admin.reward.create');
    }

    public function store()
    {
        $input = Input::except('_token');
//        $input['re_time']=time();
        $rules = [
            're_name'=>'required',
            're_reward'=>'required',
            're_float'=>'required',
            're_date_start'=>'required',
            're_date_end'=>'required',
        ];
        $message = [
            're_name.required' =>'[姓名]必须填写',
            're_reward.required' =>'[奖惩]必须选择',
            're_float.required' =>'[绩效浮动]必须填写',
            're_date_start.required' =>'[生效日期]必须填写',
            're_date_end.required' =>'[结束日期]必须填写'
        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $input['re_date_start'] = strtotime($input['re_date_start']);
            $input['re_date_end'] = strtotime($input['re_date_end']);
            $re = Reward::create($input);
            if($re){
                return redirect('admin/reward');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get  admin/reward/{reward}/edit  编辑分类 {reward}是传参的参数值
    public function edit($re_id)
    {
        $field = Reward::find($re_id);
        return view('admin/reward/edit',compact('field'));
    }
    //put|patch admin/reward/{reward}  更新分类 {reward}是传参的参数值
    public function update($re_id)
    {
        $input = Input::except('_token','_method');
        $input['re_date_start'] = strtotime($input['re_date_start']);
        $input['re_date_end'] = strtotime($input['re_date_end']);

        $re = Reward::where('re_id',$re_id)->update($input);
        if($re){
            return redirect('admin/reward');
        }else{
            return back()->with('errors','商品修改失败,请重试！');
        }
    }

    //delelte admin/reward/{reward}  删除分类
    public function destroy($re_id)
    {
        $re = Reward::where('re_id',$re_id)->delete();
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

    //get  admin/reward/{reward}  显示单个分类信息
    public function show()
    {

    }

    public function search()
    {
        $input = Input::all();
        $name = $input['name'];
        $info = Reward::where("re_name","like","%".$name."%")->orderBy("re_name")->get();   //orderBy模式asc
        return $info;
    }

}
