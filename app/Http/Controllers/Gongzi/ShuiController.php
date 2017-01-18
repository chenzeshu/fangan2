<?php

namespace App\Http\Controllers\Gongzi;

use App\Jobs\Job;
use App\Model\Base;
use App\Model\Employee;
use App\Model\Mx;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShuiController extends CommonController
{
    public function index(){
        $e = Employee::leftjoin('mx','employee.e_name','=','mx.mx_name')->select('employee.e_name','employee.e_credit','mx.*')->orderBy('e_id')->get();
        $array = array();
        $whole = 0;   //合计
        $sum = 0;
        foreach ($e as $k=>$v){
            $sum++;
            $array[$k]['id']= $sum;                                 //序号
            $array[$k]['name']= $v['e_name'];                       //纳税人姓名
            $array[$k]['credit']= $v['e_credit'];                   //身份证号
            $array[$k]['salary']= (new Mx())->shouldSalary($v['e_name'],$v['mx_ying']);         //放发总额
            $array[$k]['kou']= 3500;                                                            //扣除数
            $array[$k]['yingkou']= $v['mx_gong']+$v['mx_yang']+$v['mx_yi']+$v['mx_shi']+$v['mx_bujiao']+$v['mx_bugong'];    //应扣养老公积金

                      //计税基数
            $array[$k]['bili']=(new Mx())->getShui_bili($v['e_name'],$v['mx_ying']) ;
            //超过3500/未超过3500
            if ($array[$k]['salary']>=3500){
                $array[$k]['jishui']= $array[$k]['salary'] - 3500 - $array[$k]['yingkou'];
                $array[$k]['kou2']=3500;
            }else{
                $array[$k]['jishui']= 0;
                $array[$k]['kou2']=$array[$k]['salary'];
            }
            $array[$k]['shui']=(new Mx())->getShui($v['e_name'],$v['mx_ying']);
            $whole+= $array[$k]['shui'];
        }
        return view('gongzi.shui.index',compact('array','whole'));
    }

    //get  admin/base/create  添加商品
    
    //发工资
    public function fagongzi()
    {
        $mx = Mx::leftjoin('employee','employee.e_name','=','mx.mx_name')->select('mx.mx_name','mx.mx_shifa','employee.e_zhao')->paginate(20);
        $links = $mx->links();
        $sum = 1;
        $whole = 0;
        foreach ($mx as $k=>$v){
          $whole += $v['mx_shifa'];
        }
        return view('gongzi.shui.index2',compact('mx','sum','whole'));
    }
    public function create()
    {

    }

    public function store()
    {

    }

    //get  admin/base/{base}/edit  编辑分类 {base}是传参的参数值
    public function edit($base_id)
    {

    }
    //put|patch admin/base/{base}  更新分类 {base}是传参的参数值
    public function update($base_id)
    {


    }

    //delelte admin/base/{base}  删除分类
    public function destroy($base_id)
    {

    }

    //get  admin/base/{base}  显示单个分类信息
    public function show()
    {

    }

    public function search()
    {
        $input = Input::all();
        $name = $input['name'];
        $info = Base::where("base_name","like","%".$name."%")->orderBy("base_id")->get();   //orderBy模式asc
        return $info;
    }
}
