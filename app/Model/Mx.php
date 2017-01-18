<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mx extends Model
{
    protected $table = 'mx';
    protected  $primaryKey = 'mx_id';
    public $timestamps = false;
    protected $guarded =[];

    //得到80%工资   已经考虑了试用期，所以用起来不用担心
    public function getSalary($name)
    {
        $job = Job::where('job_name',$name)->orderBy('job_date_change','desc')->first();  //得定岗工资+时间
        $employee = Employee::where('e_name', $name)->first();  //用来找出合同种类+房租
        //job_salary_change默认是1不是0，因为0会判定为false
        if ($job['job_salary_change']==1&&$job['job_real']==1){   //没调过工资   用 ->是对象，Trying to get property of non object
            if ($employee['e_type']==3){//试用期
                $salary=$job['job_salary_enter']*0.64;
            }else{   //非试用期
               $salary=$job['job_salary_enter']*0.8;
            }
        }elseif($job['job_real']==1){   //调过工资（一般调过的都不是试用期）
           $salary=$job['job_salary_change']*0.8;
        }
        return $salary;
    }
    //绩效工资基数   正确定岗工资*0.2   考虑了试用期和试用期调岗
    public function getJixiao($name)
    {
        $job = Job::where('job_name',$name)->orderBy('job_date_change','desc')->first();  //得定岗工资+时间
        $kao = Kao::where('kao_name',$name)->first();
        $employee = Employee::where('e_name', $name)->first();  //用来找出合同种类+房租
        if ($job['job_salary_change']==1&&$job['job_real']==1){   //没调过工资   用 ->是对象，Trying to get property of non object
            if ($employee['e_type']==3){//试用期
                $salary=$job['job_salary_enter']*0.16;
            }else{   //非试用期
                $salary=$job['job_salary_enter']*0.2;
            }
        }elseif($job['job_real']==1){   //调过工资（一般调过的都不是试用期）
            $salary=$job['job_salary_change']*0.2;
        }
        //没交月表绩效为0
        if($kao['kao_if']==2){
            $salary=0;
        }
        return $salary;
    }
    //应发总额  $ying=当月工资应发数
    public function shouldSalary($name,$ying)
    {
        $jx = Jx::where('jx_name', $name)->first();   //上月绩效
        $bu = Bu::where(['bu_name'=>$name,'bu_date'=>2])->first(); //上月三补
        if ($bu['bu_if']==1){
            $sanbu = $bu['bu_limit'];  //上月交过发票
        }else{
            $sanbu=0; //上月没交发票
        }
        return  $shouldSalary = $ying+$jx['jx_real']+$sanbu;  //总和=当月应发数+上月绩效实发数+上月三补实发数

    }
    //得到工龄补贴
    //1, 满一年全在次年下个月发，即使是去年本月3号入职，也是今年下个月5号发。所以
    //要防备的只有1号-5号，那么只要+5天的秒数就行
    //2.1-5年 50/年，第六年起100/年，总共600封顶  50-100-150-200-250-350-450-550-600 第九年封顶
    //3.返聘没有工龄补贴
    //4.试用期未满一年，就不用考虑了
    public function getBu($name)
    {
        $job = Job::where('job_name',$name)->orderBy('job_date_change','desc')->first(); //得定岗工资+时间
        $employee = Employee::where('e_name', $name)->first();  //用来找出合同种类+房租
        $time = time();
        $times = floor(($time-$job['job_date_enter'])%(31536000+432000));
        if ($times<6){
            $bu=$times*50;
        }elseif(5<$times&&$times<9){
            $bu=250+($times-5)*100;
        }else{
            $bu=600;
        }
        //3，考虑合同种类 第二种无固定+500
        if ($employee['e_type']==2){
            $bu= $bu+500;
        }elseif($employee['e_type']==4){//返聘无工龄补贴
            $bu= 0;
        }
        return $bu;
    }
    //得到加班费    $salary 是定岗工资（100%），受试用期影响
    public function getJia($name,$salary,$hour)
    {
        $employee = Employee::where('e_name', $name)->first();  //用来找出合同种类+房租
        if ($hour==null){
            $jia= 0;  //不加班没有加班费
        }else{
            if ($employee['e_type']==3){//试用期
                $jia=$salary/21.75/8*$hour*3;   //岗位工资/0.8=定岗工资 和试用期*0.8抵消了
            }else{
                $jia=$salary/0.8/21.75/8*$hour*3;
            }
        }
        return $jia;
    }
    //个税
    public function getShui($name,$ying)
    {
        $jx = Jx::where('jx_name', $name)->first();   //上月绩效
        $bu = Bu::where(['bu_name'=>$name,'bu_date'=>2])->first(); //上月三补
        //总和低于3500不交,需要上月绩效表+上月三补表(交/不交)
        if ($bu['bu_if']==1){
            $sanbu = $bu['bu_limit'];  //上月交过发票
        }else{
            $sanbu=0; //上月没交发票
        }
        //绩效
        $whole = $ying+$jx['jx_real']+$sanbu;  //总和=当月应发数+上月绩效实发数+上月三补实发数
        $_whole = $whole-3500; //减去基础数
        if ($_whole<0){
            $shui=0;
        }elseif($_whole<1500){
            $shui=$_whole*0.03;
        }elseif($_whole<4500){
            $shui=$_whole*0.1-105;
        }elseif($_whole<9000){
            $shui=$_whole*0.2-555;
        }elseif($_whole<35000){
            $shui=$_whole*0.25-1005;
        }elseif($_whole<55500){
            $shui=$_whole*0.3-2755;
        }elseif($_whole<80000){
            $shui=$_whole*0.35-5505;
        }else{
            $shui=$_whole*0.45-13505;
        }
        if ($shui<1){
            $shui=0;
        }
        return $shui;
    }
    //交税比例
    public function getShui_bili($name,$ying)
    {
        $jx = Jx::where('jx_name', $name)->first();   //上月绩效
        $bu = Bu::where(['bu_name'=>$name,'bu_date'=>2])->first(); //上月三补
        //总和低于3500不交,需要上月绩效表+上月三补表(交/不交)
        if ($bu['bu_if']==1){
            $sanbu = $bu['bu_limit'];  //上月交过发票
        }else{
            $sanbu=0; //上月没交发票
        }
        //绩效
        $whole = $ying+$jx['jx_real']+$sanbu;  //总和=当月应发数+上月绩效实发数+上月三补实发数
        $_whole = $whole-3500; //减去基础数
        if ($_whole<0){
            $shui=0;
        }elseif($_whole<1500){
            $shui="3%";
        }elseif($_whole<4500){
            $shui="10%";
        }elseif($_whole<9000){
            $shui="20%";
        }elseif($_whole<35000){
            $shui="25%";
        }elseif($_whole<55500){
            $shui="30%";
        }elseif($_whole<80000){
            $shui="35%";
        }else{
            $shui="45%";;
        }
        return $shui;
    }
     //房租
    public function getDorm($name)
    {
        $employee = Employee::where('e_name', $name)->first();  //用来找出合同种类+房租
        if($employee['e_dorm']==1){
            $fang=100;   //房租
        }else{
            $fang=0;   //房租
        }
        return $fang;
    }
    
    //补扣工资
    public function getBukou($name)
    {
        $bu = (new Mx())->getBu($name); //工龄补贴
        $kou = Bukou::leftjoin('job','job.job_name','=','bukou.bk_name')
            ->where('bk_name',$name)->where('job_real',1)->select('job.job_salary_enter','job.job_salary_change','bukou.*')->get();
        $money = 0;
        foreach ($kou as $k => $v) {
            //salary = 定岗工资+工龄补贴
            if ($v->job_salary_change==1){  //没有调过工资
                $salary =$v->job_salary_enter+$bu;
            }else{//调过工资
                $salary =$v->job_salary_change+$bu;
            }

            if ($v['type']==1){
                $money += ($salary/21.75/8)*$v['hour'];
            }elseif ($v->bk_type==2){
                $money += 50*$v['time'];
            }elseif ($v->bk_type==3){
                $money += ($salary/21.75/8)*$v->bk_hour*2;
            }
       }
       return $money;
    }
    //导入名字可以得到三补扣除数
    //注意：病事假第四天才开始扣
    public function getKoubu($name)
    {
        $koubu = Koubu::where('kb_name',$name)->get();
        $limit = Bu::where('bu_name',$name)->where('bu_date',1)->first();
        $limit = round($limit['bu_limit']/21.75,3);  //每天三补数
        $num = 0;
        foreach ($koubu as $k => $v){
            if ($v->kb_type==1){//婚假一般15天
                    $num += $limit * $v->kb_date_sum;
            }elseif($v->kb_type==2){//男员工产假一般15天
                    $num += $limit * $v->kb_date_sum;
            }else{//病事假超过三天
                $num += $limit * ($v->kb_date_sum-3);
            }
        }
        return $num;
    }
}
