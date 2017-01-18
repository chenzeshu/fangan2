<?php

namespace App\Http\Controllers\Gongzi;

use App\Model\Base;
use App\Model\Bu;
use App\Model\Employee;
use App\Model\Job;
use App\Model\Jx;
use App\Model\Koubu;
use App\Model\Mx;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use Maatwebsite\Excel\Facades\Excel;


class MxController extends CommonController
{
    public function index(){
        $data =Mx::orderBy('mx_id','asc')->paginate(10);
        $links = $data->links();
        return view('gongzi/Mx/index',compact('data'));
    }

    public function god()
    {
        Mx::truncate(); //清空表以便更新
        $data = DB::table('kao')->leftjoin('jia','kao.kao_name','=','jia.jia_name')
            ->leftjoin('base','kao.kao_name','=','base.base_name')
            ->select('kao.*', 'jia.jia_hour', 'base.base_gong','base.base_yang')  //坑，后面还要加个->get()不然递归堆栈溢出
            ->get();  //测试成功
        $_data = array();  //承载重任之新数组
        $id =1;   //用来让下面的id更好看……
        foreach ($data as $k=>$v){

            $info = Job::where('job_name',$v->kao_name)->orderBy('job_date_change','desc')->first();  //岗位工资
            $employee = Employee::where('e_name', $v->kao_name)->first();  //用来找出合同种类+房租
            $jx = Jx::where('jx_name', $v->kao_name)->first();   //上月绩效
            $bu = Bu::where(['bu_name'=>$v->kao_name,'bu_date'=>2])->first(); //上月三补
            //序号
            $_data[$k]['mx_id']=$id;
            $id++;
            //姓名
            $_data[$k]['mx_name']=$v->kao_name;
            //岗位工资
            $_data[$k]['mx_salary']= (new Mx())->getSalary($_data[$k]['mx_name']);
            //工龄补贴
            $_data[$k]['mx_bu']=(new Mx())->getBu($_data[$k]['mx_name']);
            //加班费
            $_data[$k]['mx_jia']=(new Mx())->getJia($_data[$k]['mx_name'],$_data[$k]['mx_salary'],$v->jia_hour);
            //补扣工资
            $_data[$k]['mx_kou']=(new Mx())->getBukou($_data[$k]['mx_name']);    // 在本页其他函数赋值
            $_data[$k]['mx_lin']=0;  // 在本页changeLin赋值 临时项
            $_data[$k]['mx_ying']=$_data[$k]['mx_salary'] + $_data[$k]['mx_bu'] - $_data[$k]['mx_kou']+ $_data[$k]['mx_jia'];  //应发数
            $_data[$k]['mx_gh']=$_data[$k]['mx_salary']*0.0025;    //工会会费 0.25%
            $_data[$k]['mx_gong']=$v->base_gong*0.1;   //公积金
            $_data[$k]['mx_yang']=$v->base_yang*0.08;   //养老金
            $_data[$k]['mx_shi']=$v->base_yang*0.005;   //失业保险
            $_data[$k]['mx_yi']=$v->base_yang*0.02;   //医疗保险
            $_data[$k]['mx_bujiao']= 0; //补统筹
            $_data[$k]['mx_bugong']= 0; //补公积金
            //个人所得税
            $_data[$k]['mx_shui'] = (new Mx())->getShui($_data[$k]['mx_name'],$_data[$k]['mx_ying']);
            //房租
            $_data[$k]['mx_fang'] = (new Mx())->getDorm($_data[$k]['mx_name']);
            $minus =$_data[$k]['mx_gh']+$_data[$k]['mx_yi']+$_data[$k]['mx_shi']+$_data[$k]['mx_yang']+$_data[$k]['mx_gong']+$_data[$k]['mx_bujiao']+$_data[$k]['mx_bugong']+ $_data[$k]['mx_shui']+$_data[$k]['mx_fang']; //应扣数;
            $_data[$k]['mx_shifa']= $_data[$k]['mx_ying']-$minus;   //实发数 =应发数减后面的
            $re = Mx::create($_data[$k]);
        }
        return back()->with('errors','已更新');
    }
    //get  admin/base/create  添加商品
    public function create()
    {
        return view('admin.Base.create');
    }

    public function store()
    {
        $input = Input::except('_token');
//        $input['base_time']=time();
        $rules = [
            'base_name'=>'required',
            'base_gong'=>'required',
            'base_yang'=>'required',
        ];
        $message = [
            'base_name.required' =>'[姓名]必须填写',
            'base_gong.required' =>'[公积金基数]必须填写',
            'base_yang.required' =>'[养老金基数]必须上传'
        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $re = Base::create($input);
            if($re){
                return redirect('admin/base');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get  admin/base/{base}/edit  编辑分类 {base}是传参的参数值
    public function edit($mx_id)
    {
        $field = Mx::find($mx_id);
        return view('admin/base/edit',compact('field'));
    }
    //put|patch admin/base/{base}  更新分类 {base}是传参的参数值
    public function update($mx_id)
    {
        $input = Input::except('_token','_method');

        $re = Base::where('base_id',$base_id)->update($input);
        $data = [];
        if($re){
            $data =[
                'status'=>0,
                'msg' => '临时项修改成功！'
            ];
        }else{
            $data =[
                'status'=>1,
                'msg' => '临时项修改失败！'
            ];
        }
        return $data;
    }

    //delelte admin/base/{base}  删除分类
    public function destroy($base_id)
    {
        $re = Base::where('base_id',$base_id)->delete();
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

    //get  admin/base/{base}  显示单个分类信息
    public function show()
    {

    }

    public function search()
    {
        $input = Input::all();
        $name = $input['name'];
        $info = Mx::where("mx_name","like","%".$name."%")->orderBy("mx_id")->get();   //orderBy模式asc
        return $info;
    }

    public function createExcel()
    {//暂时放弃此处路由，过载
        echo 1;
    }
    
    public function changeLin()
    {
        $input = Input::except('_token');
        $mx = Mx::where('mx_id',$input['mx_id'])->first();
        $mx['mx_lin']=$input['mx_lin'];
        $mx['mx_ying']=$mx['mx_ying']+$mx['mx_lin'];  //临时项如果扣钱，前面要加英文输入法下的的-
        $mx['mx_shifa']=$mx['mx_shifa']+$mx['mx_lin'];
        $_input = [
            'mx_lin'=> $mx['mx_lin'],
            'mx_ying'=> $mx['mx_ying'] ,
            'mx_shifa'=> $mx['mx_shifa']
        ];
        $re = Mx::where('mx_id',$mx['mx_id'])->update($_input);
        $data = [];
        if($re){
            $data =[
                'status'=>0,
                'msg' => '临时项修改成功！'
            ];
        }else{
            $data =[
                'status'=>1,
                'msg' => '临时项修改失败！'
            ];
        }
        return $data;
    }

}
