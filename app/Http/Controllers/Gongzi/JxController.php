<?php

namespace App\Http\Controllers\Gongzi;

use App\Model\Bu;
use App\Model\Jx2;
use App\Model\Mx;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class JxController extends CommonController
{
    public function index(){
        $data = Jx2::orderBy('jx2_id')->paginate(15);
        $links = $data->links();
        return view('gongzi/jixiao/index',compact('data'));
    }

    public function god()
    {
        Jx2::truncate(); //清空表以便更新
        $data =Mx::leftjoin('reward','reward.re_name','=','mx.mx_name')
            ->leftjoin('kao','kao.kao_name','=','mx.mx_name')
            ->select('mx.mx_name','reward.re_reward','reward.re_float','reward.re_date_start','reward.re_date_end','kao.kao_if')
            ->get();
        $array = array();
        $sum = 0;
        foreach ($data as $k => $v){
            $sum++;
            $array[$k]['jx2_id'] = $sum;
            $array[$k]['jx2_name'] = $v['mx_name'];
            $array[$k]['jx2_base'] =(new Mx())->getJixiao($v['mx_name']);
//            $array[$k]['jx2_xishu'] = 1;
//            $array[$k]['jx2_if'] = 0;   0=>无  1=>20%
            //在奖励期内
            if (time()>$v['re_date_start']&&time()<$v['re_date_end']){
                if ($v['re_reward']==1){   //奖惩
                    $array[$k]['jx2_float'] = 0+$v['re_float'];
                }else{
                    $array[$k]['jx2_float'] = 0-$v['re_float'];
                }
            }else{
                $array[$k]['jx2_float'] = 0;
            }

            //手动奖惩
            $array[$k]['jx2_random'] = 0;
            if ($v['kao_if']==1){//交了
                $array[$k]['jx2_yue'] = '/';
                $array[$k]['jx2_shi'] = $array[$k]['jx2_base']*(1+$array[$k]['jx2_float']);
            }else{//没交月考核表没有绩效
                $array[$k]['jx2_yue'] = "未交";
                $array[$k]['jx2_shi'] = 0;
            }

            Jx2::create($array[$k]);
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

    //get  gongzi/jixiao/{jx}
    public function show($no)
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

    public function changeIf()
    {
        $input = Input::except('_token');
        //修改会造成总值变化，所以要修改
        $info = Jx2::find($input['jx2_id']);
        if ($info['jx2_yue'] == '/' && $input['jx2_if'] == 1) {//交了 且上浮20%
            $info['jx2_shi'] = $info['jx2_base'] * (1 + $info['jx2_float'] + 0.2);//增加了上浮的0.2
        } elseif ($info['jx2_yue'] == '未交') {//没交月考核表没有绩效
            $info['jx2_shi'] = 0;
        } elseif ($input['jx2_if'] == 0) {
            $info['jx2_shi'] = $info['jx2_base'] * (1 + $info['jx2_float']);//不再有0.2
        }
        $field = [
            'jx2_if' => $input['jx2_if'],
            'jx2_shi' => $info['jx2_shi']
        ];
        $re = Jx2::where('jx2_id', $input['jx2_id'])->update($field);
        $data = [];
        if ($re) {
            $data = [
                'status' => 0,
                'msg' => '成功上调20%！'
            ];
        } else {
            $data = [
                'status' => 1,
                'msg' => '临时项修改失败！'
            ];
        }
        return $data;

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

    public function jiangjin()
    {
        $data = Jx2::leftjoin('employee','employee.e_name','=','jx2.jx2_name')->select('jx2.jx2_id','jx2.jx2_name','jx2.jx2_shi','employee.e_zhong')
            ->orderBy('jx2_id')->paginate(15);
        $info =0;
        foreach ($data as $k =>$v){
            $info +=$v['jx2_shi'];
        }
        return view('gongzi/jixiao/index2',compact('data','info'));
    }

    public function huizong1(){
        //得到部门1是1的数据（真实-管理总部）、2是2（真实-业务总部）、3是3（真实-云卫通）
        $data_1 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen1',1)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen1")->get();
        $data_2 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen1',2)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen1")->get();
        $data_3 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen1',3)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen1")->get();
        $array_1 = [
            'bumen'=>'管理总部', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_2 = [
            'bumen'=>'业务总部', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_3 = [
            'bumen'=>'云卫通', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_heji = [
            'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        foreach ($data_1 as $k=>$v){
            $array_1['sum'] ++;
            $array_1['base'] += $v['jx2_base'];
            $array_1['shi'] += $v['jx2_shi'];
        }
        foreach ($data_2 as $x=>$y){
            $array_2['sum'] ++;
            $array_2['base'] += $y['jx2_base'];
            $array_2['shi'] += $y['jx2_shi'];
        }
        foreach ($data_3 as $m=>$n){
            $array_3['sum'] ++;
            $array_3['base'] += $n['jx2_base'];
            $array_3['shi'] += $n['jx2_shi'];
        }
        $array_heji['sum'] = $array_1['sum']+$array_2['sum']+$array_3['sum'];
        $array_heji['base'] =$array_1['base']+$array_2['base']+$array_3['base'];
        $array_heji['shi'] = $array_1['shi']+$array_2['shi']+$array_3['shi'];
        $array_1 = json_encode($array_1); $array_1 = json_decode($array_1);
        $array_2 = json_encode($array_2); $array_2 = json_decode($array_2);
        $array_3 = json_encode($array_3); $array_3 = json_decode($array_3);
        $array_heji = json_encode($array_heji);$array_heji = json_decode($array_heji);
        return view('gongzi/jixiao/index3',compact('array_1','array_2','array_3','array_heji'));
    }

    public function huizong2(){
        //得到部门1是1的数据（真实-管理总部）、2是2（真实-业务总部）、3是3（真实-云卫通）
        $data_1 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen2',1)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen2")->get();
        $data_2 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen2',2)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen2")->get();
        $data_3 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen2',3)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen2")->get();
        $data_4 =Jx2::leftjoin('job','jx2.jx2_name','=','job.job_name')->where('job.job_bumen2',4)->where('job.job_real',1)->select("jx2.jx2_base",'jx2.jx2_shi',"job.job_bumen2")->get();
        $array_1 = [
            'bumen'=>'管理总部', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_2 = [
            'bumen'=>'业务总部', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_3 = [
            'bumen'=>'云卫通', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_4 = [
            'bumen'=>'技术部', 'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        $array_heji = [
            'sum'=> 0 , 'base'=>0 , 'shi'=> 0,
        ];
        foreach ($data_1 as $k=>$v){
            $array_1['sum'] ++;
            $array_1['base'] += $v['jx2_base'];
            $array_1['shi'] += $v['jx2_shi'];
        }
        foreach ($data_2 as $x=>$y){
            $array_2['sum'] ++;
            $array_2['base'] += $y['jx2_base'];
            $array_2['shi'] += $y['jx2_shi'];
        }
        foreach ($data_3 as $m=>$n){
            $array_3['sum'] ++;
            $array_3['base'] += $n['jx2_base'];
            $array_3['shi'] += $n['jx2_shi'];
        }
        foreach ($data_4 as $m=>$n){
            $array_4['sum'] ++;
            $array_4['base'] += $n['jx2_base'];
            $array_4['shi'] += $n['jx2_shi'];
        }
        $array_heji['sum'] = $array_1['sum']+$array_2['sum']+$array_3['sum']+$array_4['sum'];
        $array_heji['base'] =$array_1['base']+$array_2['base']+$array_3['base']+$array_4['base'];
        $array_heji['shi'] = $array_1['shi']+$array_2['shi']+$array_3['shi']+$array_4['shi'];
        $array_1 = json_encode($array_1); $array_1 = json_decode($array_1);
        $array_2 = json_encode($array_2); $array_2 = json_decode($array_2);
        $array_3 = json_encode($array_3); $array_3 = json_decode($array_3);
        $array_4 = json_encode($array_4); $array_4 = json_decode($array_4);
        $array_heji = json_encode($array_heji);$array_heji = json_decode($array_heji);
        return view('gongzi/jixiao/index4',compact('array_1','array_2','array_3','array_4','array_heji'));
    }

    public function sanbu()
    {
       $bu = Bu::leftjoin('employee','employee.e_name','=','bu.bu_name')->where('bu.bu_date',1)
           ->select('bu.bu_id','bu.bu_name','bu.bu_limit','bu.bu_if','employee.e_zhao')->paginate(15);
        $num = 0;
        $sum = 0;
        $array = array();
        foreach ($bu as $k=>$v){
            $num++;
            $array[$k]['id']=$num;
            $array[$k]['name']=$v['bu_name'];
            $koubu = (new Mx())->getKoubu($v['bu_name']); //因假而扣除的补贴数
            $array[$k]['zhao']=$v['e_zhao'];
            $array[$k]['limit']=$v['bu_limit'];
            if ($v['bu_if']==1){
                $array[$k]['if']="/";
                $array[$k]['shi']=$v['bu_limit']-$koubu;
            }else{
                $array[$k]['if']="未交";
                $array[$k]['shi']=0;
            }
            $sum +=$array[$k]['shi'];
        }
        return view('gongzi/sanbu/index',compact('array','sum'));
    }
}
