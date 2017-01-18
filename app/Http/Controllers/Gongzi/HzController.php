<?php

namespace App\Http\Controllers\Gongzi;

use App\Model\Mx;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HzController extends CommonController
{
    public function index(){
        //得到部门1是1的数据（真实-管理总部）、2是2（真实-业务总部）、3是3（真实-云卫通）
        $data_1 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',1)->where('job.job_real',1)->select("mx.*","job.job_bumen1")->get();
        $data_2 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',2)->where('job.job_real',1)->select("mx.*","job.job_bumen1")->get();
        $data_3 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',3)->where('job.job_real',1)->select("mx.*","job.job_bumen1")->get();
        $array_1 = [
            'bumen'=>'管理总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        $array_2 = [
            'bumen'=>'业务总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        $array_3 = [
            'bumen'=>'云卫通', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        foreach ($data_1 as $k=>$v){
            $array_1['sum'] ++;
            $array_1['salary'] += $v['mx_salary'];
            $array_1['bu'] += $v['mx_bu'];
            $array_1['kou'] += $v['mx_kou'];
            $array_1['jia'] += $v['mx_jia'];
            $array_1['lin'] += $v['mx_lin'];
            $array_1['ying'] += $v['mx_ying'];
            $array_1['gh'] += $v['mx_gh'];
            $array_1['gong'] += $v['mx_gong'];
            $array_1['yang'] += $v['mx_yang'];
            $array_1['shi'] += $v['mx_shi'];
            $array_1['yi'] += $v['mx_yi'];
            $array_1['bujiao'] += $v['mx_bujiao'];
            $array_1['bugong'] += $v['mx_bugong'];
            $array_1['shui'] += $v['mx_shui'];
            $array_1['fang'] += $v['mx_fang'];
            $array_1['shifa'] += $v['mx_shifa'];
        }
        foreach ($data_2 as $x=>$y){
            $array_2['sum'] ++;
            $array_2['salary'] += $y['mx_salary'];
            $array_2['bu'] += $y['mx_bu'];
            $array_2['kou'] += $y['mx_kou'];
            $array_2['jia'] += $y['mx_jia'];
            $array_2['lin'] += $y['mx_lin'];
            $array_2['ying'] += $y['mx_ying'];
            $array_2['gh'] += $y['mx_gh'];
            $array_2['gong'] += $y['mx_gong'];
            $array_2['yang'] += $y['mx_yang'];
            $array_2['shi'] += $y['mx_shi'];
            $array_2['yi'] += $y['mx_yi'];
            $array_2['bujiao'] += $y['mx_bujiao'];
            $array_2['bugong'] += $y['mx_bugong'];
            $array_2['shui'] += $y['mx_shui'];
            $array_2['fang'] += $y['mx_fang'];
            $array_2['shifa'] += $y['mx_shifa'];
        }
        foreach ($data_3 as $m=>$n){
            $array_3['sum'] ++;
            $array_3['salary'] += $n['mx_salary'];
            $array_3['bu'] += $n['mx_bu'];
            $array_3['kou'] += $n['mx_kou'];
            $array_3['jia'] += $n['mx_jia'];
            $array_3['lin'] += $n['mx_lin'];
            $array_3['ying'] += $n['mx_ying'];
            $array_3['gh'] += $n['mx_gh'];
            $array_3['gong'] += $n['mx_gong'];
            $array_3['yang'] += $n['mx_yang'];
            $array_3['shi'] += $n['mx_shi'];
            $array_3['yi'] += $n['mx_yi'];
            $array_3['bujiao'] += $n['mx_bujiao'];
            $array_3['bugong'] += $n['mx_bugong'];
            $array_3['shui'] += $n['mx_shui'];
            $array_3['fang'] += $n['mx_fang'];
            $array_3['shifa'] += $n['mx_shifa'];
        }
        $array_1 = json_encode($array_1); $array_1 = json_decode($array_1);
        $array_2 = json_encode($array_2); $array_2 = json_decode($array_2);
        $array_3 = json_encode($array_3); $array_3 = json_decode($array_3);
        return view('gongzi/Hz/index',compact('array_1','array_2','array_3'));
    }

    public function index2()
    {
        //得到部门1是1的数据（真实-管理总部）、2是2（真实-业务总部）、3是3（真实-云卫通）
        $data_1 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen2',1)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $data_2 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen2',2)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $data_3 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen2',3)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $data_4 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen2',4)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $array_1 = [
            'bumen'=>'管理总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        $array_2 = [
            'bumen'=>'业务总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        $array_3 = [
            'bumen'=>'云卫通', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        $array_4 = [
            'bumen'=>'技术部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,
            'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
        ];
        foreach ($data_1 as $k=>$v){
            $array_1['sum'] ++;
            $array_1['salary'] += $v['mx_salary'];
            $array_1['bu'] += $v['mx_bu'];
            $array_1['kou'] += $v['mx_kou'];
            $array_1['jia'] += $v['mx_jia'];
            $array_1['lin'] += $v['mx_lin'];
            $array_1['ying'] += $v['mx_ying'];
            $array_1['gh'] += $v['mx_gh'];
            $array_1['gong'] += $v['mx_gong'];
            $array_1['yang'] += $v['mx_yang'];
            $array_1['shi'] += $v['mx_shi'];
            $array_1['yi'] += $v['mx_yi'];
            $array_1['bujiao'] += $v['mx_bujiao'];
            $array_1['bugong'] += $v['mx_bugong'];
            $array_1['shui'] += $v['mx_shui'];
            $array_1['fang'] += $v['mx_fang'];
            $array_1['shifa'] += $v['mx_shifa'];
        }
        foreach ($data_2 as $x=>$y){
            $array_2['sum'] ++;
            $array_2['salary'] += $y['mx_salary'];
            $array_2['bu'] += $y['mx_bu'];
            $array_2['kou'] += $y['mx_kou'];
            $array_2['jia'] += $y['mx_jia'];
            $array_2['lin'] += $y['mx_lin'];
            $array_2['ying'] += $y['mx_ying'];
            $array_2['gh'] += $y['mx_gh'];
            $array_2['gong'] += $y['mx_gong'];
            $array_2['yang'] += $y['mx_yang'];
            $array_2['shi'] += $y['mx_shi'];
            $array_2['yi'] += $y['mx_yi'];
            $array_2['bujiao'] += $y['mx_bujiao'];
            $array_2['bugong'] += $y['mx_bugong'];
            $array_2['shui'] += $y['mx_shui'];
            $array_2['fang'] += $y['mx_fang'];
            $array_2['shifa'] += $y['mx_shifa'];
        }
        foreach ($data_3 as $m=>$n){
            $array_3['sum'] ++;
            $array_3['salary'] += $n['mx_salary'];
            $array_3['bu'] += $n['mx_bu'];
            $array_3['kou'] += $n['mx_kou'];
            $array_3['jia'] += $n['mx_jia'];
            $array_3['lin'] += $n['mx_lin'];
            $array_3['ying'] += $n['mx_ying'];
            $array_3['gh'] += $n['mx_gh'];
            $array_3['gong'] += $n['mx_gong'];
            $array_3['yang'] += $n['mx_yang'];
            $array_3['shi'] += $n['mx_shi'];
            $array_3['yi'] += $n['mx_yi'];
            $array_3['bujiao'] += $n['mx_bujiao'];
            $array_3['bugong'] += $n['mx_bugong'];
            $array_3['shui'] += $n['mx_shui'];
            $array_3['fang'] += $n['mx_fang'];
            $array_3['shifa'] += $n['mx_shifa'];
        }
        foreach ($data_4 as $a=>$b){
            $array_4['sum'] ++;
            $array_4['salary'] += $b['mx_salary'];
            $array_4['bu'] += $b['mx_bu'];
            $array_4['kou'] += $b['mx_kou'];
            $array_4['jia'] += $b['mx_jia'];
            $array_4['lin'] += $b['mx_lin'];
            $array_4['ying'] += $b['mx_ying'];
            $array_4['gh'] += $b['mx_gh'];
            $array_4['gong'] += $b['mx_gong'];
            $array_4['yang'] += $b['mx_yang'];
            $array_4['shi'] += $b['mx_shi'];
            $array_4['yi'] += $b['mx_yi'];
            $array_4['bujiao'] += $b['mx_bujiao'];
            $array_4['bugong'] += $b['mx_bugong'];
            $array_4['shui'] += $b['mx_shui'];
            $array_4['fang'] += $b['mx_fang'];
            $array_4['shifa'] += $b['mx_shifa'];
        }
        $array_1 = json_encode($array_1); $array_1 = json_decode($array_1);
        $array_2 = json_encode($array_2); $array_2 = json_decode($array_2);
        $array_3 = json_encode($array_3); $array_3 = json_decode($array_3);
        $array_4 = json_encode($array_4); $array_4 = json_decode($array_4);
        return view('gongzi/Hz/index2',compact('array_1','array_2','array_3','array_4'));
    }

    public function god()
    {
        Hz::truncate(); //清空表以便更新
        $data = DB::table('kao')->leftjoin('jia','kao.kao_name','=','jia.jia_name')
            ->leftjoin('base','kao.kao_name','=','base.base_name')
            ->select('kao.*', 'jia.jia_hour', 'base.base_gong','base.base_yang')  //坑，后面还要加个->get()不然递归堆栈溢出
            ->get();  //测试成功
        $_data = array();  //承载重任之 新数组
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
            $_data[$k]['mx_kou']=0;    // 在本页其他函数赋值
            $_data[$k]['mx_lin']=0;  // 在本页changeLin赋值 临时项
            $_data[$k]['mx_ying']=$_data[$k]['mx_salary'] + $_data[$k]['mx_bu'] - $_data[$k]['mx_kou']+ $_data[$k]['mx_jia'];  //应发数
            $_data[$k]['mx_gh']=$_data[$k]['mx_salary']*0.005;    //工会会费 0.5%
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
