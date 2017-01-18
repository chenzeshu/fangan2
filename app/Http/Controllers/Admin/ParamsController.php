<?php

namespace App\Http\Controllers\Admin;

use App\Model\Params;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ParamsController extends Controller
{
    public function index(){
        $data = Params::orderBy('pa_id','asc')->paginate(10);
        $links = $data->links();
        return view('admin/Params/index',compact('data'));
    }

    //get  admin/Params/create  添加商品
    public function create()
    {
        return view('admin.Params.create');
    }

    public function store()
    {
        $input = Input::except('_token');
//        $input['pa_time']=time();
        $rules = [

        ];
        $message = [

        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $re = Params::create($input);
            if($re){
                return redirect('admin/Params');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get  admin/Params/{Params}/edit  编辑分类 {Params}是传参的参数值
    public function edit($pa_id)
    {
        $field = Params::find($pa_id);
        return view('admin/Params/edit',compact('field'));
    }
    //put|patch admin/Params/{Params}  更新分类 {Params}是传参的参数值
    public function update($pa_id)
    {
        $input = Input::except('_token','_method');

        $re = Params::where('pa_id',$pa_id)->update($input);
        if($re){
            return redirect('admin/Params');
        }else{
            return back()->with('errors','商品修改失败,请重试！');
        }
    }

    //delelte admin/Params/{Params}  删除分类
    public function destroy($pa_id)
    {
        $re = Params::where('pa_id',$pa_id)->delete();
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

    //get  admin/Params/{Params}  显示单个分类信息
    public function show()
    {

    }

    public function search()
    {
        $input = Input::all();
        $name = $input['name'];
        $info = Params::where("pa_name","like","%".$name."%")->orderBy("pa_name")->get();   //orderBy模式asc
        return $info;
    }
}
