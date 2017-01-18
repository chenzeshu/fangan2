<?php

namespace App\Http\Controllers\Admin;

use App\Model\Params;
use App\Model\Pros;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ProsController extends Controller
{
    public function index(){
        $data = Pros::orderBy('pros_id','asc')->paginate(10);
        $links = $data->links();
        return view('admin/pros/index',compact('data'));
    }

    //get  admin/reward/create  添加商品
    public function create()
    {
        return view('admin.pros.create');
    }

    public function store()
    {
        $input = Input::except('_token');

        $rules = [
            'pros_name'=>'required',
        ];
        $input['pros_img_other']=implode(',',$input['pros_img_other']);
        $input['pros_img_other_name']=implode(',',$input['pros_img_other_name']);
        $message = [
            'pros_name.required' =>'[设备名称]必须填写',
        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

            //todo 按币种转换成本为人民币
            $params = Params::first();
            $dollar =$params['pa_dollar'];
            $eu = $params['pa_eu'];
            $bili =$params['pa_bili'];

            if ($input['pros_flag_money'] == 1 ){
                //系数1 美元
                $input['pros_display_inprice'] = $input['pros_inprice']*$dollar ;
            }elseif($input['pros_flag_money'] == 2 ){
                //系数2 欧元
                $input['pros_display_inprice'] = $input['pros_inprice']*$eu ;
            }else{
                //系数3 人民币
                $input['pros_display_inprice'] = $input['pros_inprice'];
            }
            //todo 是否转换为有比例报价
            if ($input['pros_flag_bili']==1){
                //系数1 无比例
                $input['pros_display_outprice'] = $input['pros_outprice'];
            }else{
                //系数2 有比例
                $input['pros_display_outprice'] = $input['pros_display_inprice']*$bili;
            }


            $re = Pros::create($input);
            if($re){
                return redirect('admin/pros');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get  admin/reward/{reward}/edit  编辑分类 {reward}是传参的参数值
    public function edit($pros_id)
    {
        $field = Pros::find($pros_id);
        $field->pros_img_other = explode(',',$field->pros_img_other);
        return view('admin/pros/edit',compact('field'));
    }
    //put|patch admin/reward/{reward}  更新分类 {reward}是传参的参数值
    public function update($pros_id)
    {
        $input = Input::except('_token','_method');

        $re = Pros::where('pros_id',$pros_id)->update($input);

        if($re){
            $data = [
                'status'=> 0,   //因为是ajax异步返回，所以返回一个json数据
                'msg' => '修改成功',
            ];
        }else{
            $data = [
                'status'=> 1,   //因为是ajax异步返回，所以返回一个json数据
                'msg' => '修改失败，请重试',
            ];
        }
        return $data;
    }

    //delelte admin/reward/{reward}  删除分类
    public function destroy($pros_id)
    {
        $re = Pros::where('pros_id',$pros_id)->delete();
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

    public function proSearch()
    {
        $input = Input::except('_token');
        $sys = $input['sys'];
        $name =$input['name'];
        $detail =$input['detail'];

        $pros = Pros::where('pros_sys','like','%'.$sys.'%')
            ->where('pros_name','like','%'.$name.'%')
            ->where('pros_detail','like','%'.$detail.'%')
            ->get();

        if ($pros){
            $data =[
                'status' =>0,
                'msg' =>$pros
            ];
        }
        return $data;
    }
    //图文弹窗 or 参数弹窗
    //因为是小数据库，而且用的人少，基本不存在性能损失，所以公用了
    public function showDesAndImg()
    {
        $input = Input::except('_token');
        $pro = Pros::find($input['id']);
        return $pro;
    }

}
