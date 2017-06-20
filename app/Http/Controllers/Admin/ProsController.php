<?php

namespace App\Http\Controllers\Admin;

use App\Model\Params;
use App\Model\Pros;
use App\Model\System;
use App\Model\SystemList;
use App\Repositories\FanganRepository;
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
    protected $fangan;
    public function __construct(ProsRepositories $repo, FanganRepository $fangan)
    {
        $this->repo = $repo;
        $this->fangan = $fangan;
    }

    public function index(){
        $data = Pros::orderBy('pros_id','asc')->paginate(10);
        $data = $this->fangan->getDisplayPrice($data);
        $systemList = SystemList::all();
        $links = $data->links();
        return view('admin/pros/index',compact('data', 'systemList'));
    }

    //get  admin/pros/create  添加商品
    public function create()
    {
        //拿到系统list
        $systemList = SystemList::all();
        return view('admin.pros.create',compact('systemList'));
    }

    public function store()
    {
        $input = Input::except(['_token','file_upload','file_upload2']);

        $rules = [
            'pros_name'=>'required',
        ];
        if(isset($input['pros_img_other']) && isset($input['pros_img_other_name'])){
            $input['pros_img_other']=implode(',',$input['pros_img_other']);
            $input['pros_img_other_name']=implode(',',$input['pros_img_other_name']);
        }

        $message = [
            'pros_name.required' =>'[设备名称]必须填写',
        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

            //todo 按币种转换成本为人民币并存储
            $params = Params::first();
            $dollar = $params['pa_dollar'];
            $eu = $params['pa_eu'];
            $bili = $params['pa_bili'];

         //fixme 根据2017.6.15更改，要么在create时就存入display_price，要么就在取时换算出display_price
        //个人倾向于存时换算， 否则大批量取时有损性能。----未fix
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
    //put|patch admin/pros/{pros_id}  更新分类 {reward}是传参的参数值
    public function update($pros_id)
    {
        $input = Input::except('_token','_method');

        //todo 按币种转换成本为人民币并存储
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

    //备注
    public function showRemark(){
        $input = Input::except('_token');
        $id = $input['id'];
        $remark = Pros::find($id)->pros_remark;
        return $remark;
    }

}
