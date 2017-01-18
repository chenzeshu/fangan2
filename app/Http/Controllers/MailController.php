<?php

namespace App\Http\Controllers;

use App\Model\Employee;
use App\Model\Mx;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use PHPMailer;


class MailController extends Controller
{
    public function index(){
        $data =Mx::orderBy('mx_id','asc')->paginate(10);
        $links = $data->links();
        return view('gongzi/Mx/mail',compact('data'));
    }

    public function send()
    {
        $user = "胡拥军";
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = false; // 是否加密 还可ssl-465端口
        $mail->Host = "smtp.qq.com";
        $mail->Port = 25; //  25 465 or 587
        $mail->CharSet  = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式
        $mail->Username = "1193297950@qq.com";
        $mail->Password = "hrozrodrqiiqbaee";
        $mail->SetFrom("1193297950@qq.com");   //发件人地址
        $mail->FromName = "软件";              //发件人姓名
        $mail->IsHTML(true); //支持html格式内容

        $mail->addAddress("2439384241@qq.com");
        $mail->Subject = date("Y",time())."年".date("m",time())."月工资发放明细";                 //标题
        $mail->Body = '你好, <b>'.$user.'</b>! <br/>
        这是一封来自<a href="http://www.baidu.com" target="_blank">百度</a>的邮件！<br/> 
<img alt="helloweba" src="cid:my-attach">';
//        $mail->addAttachment(base_path().'/uploads/20160821042656709.jpg','公子条.jpg'); // 添加附件,并指定名称


        if(!$mail->Send()) {
            echo "<br>Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }
    }

    public function laSend()
    {
            $input = Input::except('_token');
            $name = $input['name'];
            $info = Employee::where('e_name',$name)->first();
            $mx = Mx::where('mx_name',$name)->first();
            $user =[
                'email'=>$info['e_mail'],
                'name'=>$name,
                'salary'=>$mx['mx_salary'],
                'bu'=>$mx['mx_bu'],
                'jia'=>$mx['mx_jia'],
                'kou'=>$mx['mx_kou'],
                'lin'=>$mx['mx_lin'],
                'ying'=>$mx['mx_ying'],
                'gh'=>$mx['mx_gh'],
                'gong'=>$mx['mx_gong'],
                'yang'=>$mx['mx_yang'],
                'shi'=>$mx['mx_shi'],
                'yi'=>$mx['mx_yi'],
                'bujiao'=>$mx['mx_bujiao'],
                'bugong'=>$mx['mx_bugong'],
                'shui'=>$mx['mx_shui'],
                'fang'=>$mx['mx_fang'],
                'shifa'=>$mx['mx_shifa'],
            ];
            Mail::send('mail.test', ['user' => $user], function ($m) use ($user) {
                $m->to($user['email'], $user['name'])->subject(date("Y",time())."年".date("m",time())."月工资发放明细");
            });
            $msg = "发送成功";
            return $msg;

    }

    public function allSend()
    {
            $name = Employee::select('e_name')->get();
            $sum = 0;
            $time = time();
            foreach ($name as $k => $v){
                $sum++;
                $info = Employee::where('e_name',$v->e_name)->first();
                $mx = Mx::where('mx_name',$v->e_name)->first();
                $user =[
                    'email'=>$info['e_mail'],
                    'name'=>$v->e_name,
                    'salary'=>$mx['mx_salary'],
                    'bu'=>$mx['mx_bu'],
                    'jia'=>$mx['mx_jia'],
                    'kou'=>$mx['mx_kou'],
                    'lin'=>$mx['mx_lin'],
                    'ying'=>$mx['mx_ying'],
                    'gh'=>$mx['mx_gh'],
                    'gong'=>$mx['mx_gong'],
                    'yang'=>$mx['mx_yang'],
                    'shi'=>$mx['mx_shi'],
                    'yi'=>$mx['mx_yi'],
                    'bujiao'=>$mx['mx_bujiao'],
                    'bugong'=>$mx['mx_bugong'],
                    'shui'=>$mx['mx_shui'],
                    'fang'=>$mx['mx_fang'],
                    'shifa'=>$mx['mx_shifa'],
                ];
                Mail::send('mail.test', ['user' => $user], function ($m) use ($user) {
                    $m->to($user['email'], $user['name'])->subject(date("Y",time())."年".date("m",time())."月工资发放明细");
                });
            }
            $time= time()-$time;

          echo "全部员工共".$sum."人，总计".$sum."人发送成功，用时".$time."秒";
    }
}
