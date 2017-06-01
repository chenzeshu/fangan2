<?php

namespace App\Http\Controllers\Admin;

use App\Model\Params;
use App\Model\Pros;
use App\Model\System;
use App\Model\SystemList;
use App\Repositories\ProsRepositories;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ProsController extends Controller
{
    /**
     * pros的系统为关联键为"name"，同时，没有做ORM，纯手工关联。
     */
    protected $repo;

    public function __construct(ProsRepositories $repo)
    {
        $this->repo = $repo;
    }

    public function index(){
        $data = Pros::orderBy('pros_id','asc')->paginate(10);
        $systemList = SystemList::all();
        $links = $data->links();
        return view('admin/pros/index',compact('data', 'systemList'));
    }

    //get  admin/reward/create  添加商品
    public function create()
    {
        $systemList = SystemList::all();
        return view('admin.pros.create',compact('systemList'));
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
            $dollar = $params['pa_dollar'];
            $eu = $params['pa_eu'];
            $bili = $params['pa_bili'];

            //todo 返回各种币种换算后的人民币价格
            $input['pros_display_inprice'] = $this->repo
                ->judgeInprice($input['pros_flag_money'], $input['pros_inprice'], $dollar, $eu);

            //todo 是否转换为有比例报价
            $input['pros_display_outprice'] = $this->repo
                ->judgeOutprice($input['pros_flag_bili'], $input['pros_outprice'],$input['pros_display_inprice'], $bili);

            //todo 存储
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
        $systemList = SystemList::all();
        $field = Pros::find($pros_id);
        $field->pros_img_other = explode(',',$field->pros_img_other);
        return view('admin/pros/edit',compact('field','systemList'));
    }
    //put|patch admin/reward/{reward}  更新分类 {reward}是传参的参数值
    public function update($pros_id)
    {
        $input = Input::except('_token','_method');

        $re = Pros::where('pros_id',$pros_id)->update($input);

        return $this->repo->changeResponse($re);
    }

    //delelte admin/reward/{reward}  删除分类
    public function destroy($pros_id)
    {
        $re = Pros::where('pros_id',$pros_id)->delete();

        return $this->repo->changeResponse($re);
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
        if($sys == "all"){
            $pros = Pros::where('pros_name','like','%'.$name.'%')
                ->where('pros_detail','like','%'.$detail.'%')
                ->get();
        }
        else{
            $pros = Pros::where('pros_sys','like','%'.$sys.'%')
                ->where('pros_name','like','%'.$name.'%')
                ->where('pros_detail','like','%'.$detail.'%')
                ->get();
        }


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
