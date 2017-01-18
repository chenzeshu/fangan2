<?php

namespace App\Http\Controllers;

use App\Model\Bu;
use App\Model\Employee;
use App\Model\Jx2;
use App\Model\Mx;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ExcelController extends Controller
{
    //todo 方案EXCEL导出
    public function export_fangan($tablename)
    {

        //按照order表导出
        $orderData = DB::table(session('table').'_order')->first();
        $order = $orderData->order;
        $pros = DB::table(session('table'))->orderBy(DB::raw('FIELD(id,'.$order.')'))->get();
        Excel::create(session('username')."的《".$tablename."》方案",function ($excel) use ($pros,$tablename){
            //创建文件中第一个sheet
            $excel->sheet($tablename,function ($sheet) use ($pros){
                //第一行 菜单标题
                $sheet->setHeight(1,40);
                $sheet->setWidth(array(
                    'A'     =>  7,
                    'B'     =>  19,
                    'C'     =>  19,
                    'D'     =>  25,
                    'E'     =>  35,
                    'F'     =>  7,
                    'G'     =>  5,
                    'H'     =>  9,
                    'I'     =>  12,
                    'J'     =>  10,
                    'K'     =>  9,
                    'L'     =>  12,
                    'M'     =>  15,
                    'N'     =>  15,
                    'O'     =>  32,
                ));

                $sheet->row(1, ['序号','设备名称','品牌','规格型号','简单参数描述',
                    '数量','单位','产地','体积(l*w*h,mm)','机柜尺寸(U)','重量(kg)','功耗(W)','成本单价(元)','出厂单价(元)','备注',]);
                //todo 标题栏的格式
                $sheet->getStyle('J1')->getAlignment()->setWrapText(true);
                $sheet->getStyle('I1')->getAlignment()->setWrapText(true);
                $sheet->cells('A1:O1', function($cells) {
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '11',
                        'bold'       =>  true
                    ));
                });

            //todo 设备栏
                $count = 2;  //行数
                $id = 1; //设备序号
                foreach ($pros as $k) {
                    //todo 有系统的行 merge
                    if ($k->sys) {
                        $sheet->appendRow([$k->name]);
                        $sheet->mergeCells('A' . $count . ':E' . $count);
                        $sheet->cells('A'.$count, function($cells) {
                            $cells->setFont(array(
                                'bold'       =>  true
                            ));
                        });
                        $count = $count + 1;
                        foreach ($pros as $v) {
                            if ($v->father == $k->sys) {
                                $sheet->appendRow([
                                    $id, $v->name, $v->brand, $v->detail, $v->less,$v->number, $v->unit, $v->area,
                                    $v->vol,$v->u,$v->kg, $v->w, $v->display_inprice, $v->display_outprice, $v->remark
                                ]);
                                //todo 使用PHPExcel本地方法来达到warptext
                                //todo 特别说明：PHPExcel在这个项目里用composer装了，但没有在composer.json里save
                                $sheet->getStyle('O'.$count)->getAlignment()->setWrapText(true);
                                $count = $count + 1;
                                $id = $id + 1;
                            }
                        }
                    }
                }
                //todo 表格整体样式调整
                $sheet->cells('A1:O'.$count, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                });
                //todo 设置边框, >>必须放到最后<<，不然最先生成有边框的空白cells
                $count = $count-1;
                $sheet->setBorder('A1:O'.$count);


            });
        })->export('xls');
    }


    //Excel文件导出功能
    public function export_mingxi(){
        $data = Mx::orderBy('mx_id')->get();

        $array = array();
        foreach ($data as $k => $v){
            $array[$k]['mx_id']=$v['mx_id'];
            $array[$k]['mx_name']=$v['mx_name'];
            $array[$k]['mx_salary']=$v['mx_salary'];
            $array[$k]['mx_bu']=$v['mx_bu'];
            $array[$k]['mx_kou']=$v['mx_kou'];
            $array[$k]['mx_jia']=$v['mx_jia'];
            $array[$k]['mx_lin']=$v['mx_lin'];
            $array[$k]['mx_ying']=$v['mx_ying'];
            $array[$k]['mx_kong']=' ';  //特别空一列
            $array[$k]['mx_gh']=$v['mx_gh'];
            $array[$k]['mx_gong']=$v['mx_gong'];
            $array[$k]['mx_yang']=$v['mx_yang'];
            $array[$k]['mx_shi']=$v['mx_shi'];
            $array[$k]['mx_yi']=$v['mx_yi'];
            $array[$k]['mx_bujiao']=$v['mx_bujiao'];
            $array[$k]['mx_bugong']=$v['mx_bugong'];
            $array[$k]['mx_shui']=$v['mx_shui'];
            $array[$k]['mx_fang']=$v['mx_fang'];
            $array[$k]['mx_shifa']=$v['mx_shifa'];
            }

//        $year = date('Y',time());
//        $mon = date('m',time());
//        $filename = $year."年".$mon."月份";
        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'工资明细表',function($excel) use ($array){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:S1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  5,
                    'B'     =>  10,
                    'C'     =>  15,
                    'D'     =>  6,
                    'E'     =>  7,
                    'F'     =>  10,
                    'G'     =>  10,
                    'H'     =>  12,
                    'I'     =>  2,
                    'J'     =>  6,
                    'K'     =>  10,
                    'L'     =>  10,
                    'M'     =>  12,
                    'N'     =>  6,
                    'O'     =>  6,
                    'P'     =>  5,
                    'Q'     =>  10,
                    'R'     =>  8,
                    'S'     =>  12,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'工资明细表']);
                $sheet->row(2, ['序号','姓名','岗位工资','工龄补贴','补扣工资','加班费','临时项','应发数','','工会补贴',
                    '公积金','养老金', '失业保险','医疗保险','社交统筹','补公积','个人所得税','房租','实发数']);//第二行填充数据;
                $sheet->cells('A1:S1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '22',
                        'bold'       =>  true
                    ));
                });
                $sheet->cells('A2:S2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                $m = count($array)+1;
                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($m-1, 25);
                    $m++;

                }

                $sheet->cells('A'.(count($array)-1).':S'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                //使用PHPExcel本地方法来达到warptext
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //只需要去掉以前的->getActiveSheet()
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('E2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('J2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('N2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('O2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('P2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('Q2')->getAlignment()->setWrapText(true);

                //倒数第二步：合计栏
                $sheet->row($m-1, ['','合计']);
                $sheet->setHeight($m-1, 25);
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A'.(count($array)-1).':S'.($m-1), 'thin');

                //边界----------
            });
        })->export('xls');
    }

    public function export_huizong1(){
        $data = Mx::orderBy('mx_id')->get();
//得到部门1是1的数据（真实-管理总部）、2是2（真实-业务总部）、3是3（真实-云卫通）
        $data_1 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',1)->where('job.job_real',1)->select("mx.*","job.job_bumen1")->get();
        $data_2 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',2)->where('job.job_real',1)->select("mx.*","job.job_bumen1")->get();
        $data_3 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',3)->where('job.job_real',1)->select("mx.*","job.job_bumen1")->get();

        $array = [
            'd1'=>[
                'bumen'=>'管理总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
            'd2'=>[
                'bumen'=>'业务总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
            'd3'=>[
                'bumen'=>'云卫通', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
        ];
        foreach ($data_1 as $k=>$v){
            $array['d1']['sum'] ++;
            $array['d1']['salary'] += $v['mx_salary'];
            $array['d1']['bu'] += $v['mx_bu'];
            $array['d1']['kou'] += $v['mx_kou'];
            $array['d1']['jia'] += $v['mx_jia'];
            $array['d1']['lin'] += $v['mx_lin'];
            $array['d1']['ying'] += $v['mx_ying'];
            $array['d1']['kong']=' ';  //特别空一列
            $array['d1']['gh'] += $v['mx_gh'];
            $array['d1']['gong'] += $v['mx_gong'];
            $array['d1']['yang'] += $v['mx_yang'];
            $array['d1']['shi'] += $v['mx_shi'];
            $array['d1']['yi'] += $v['mx_yi'];
            $array['d1']['bujiao'] += $v['mx_bujiao'];
            $array['d1']['bugong'] += $v['mx_bugong'];
            $array['d1']['shui'] += $v['mx_shui'];
            $array['d1']['fang'] += $v['mx_fang'];
            $array['d1']['shifa'] += $v['mx_shifa'];
        }
        foreach ($data_2 as $x=>$y){
            $array['d2']['sum'] ++;
            $array['d2']['salary'] += $y['mx_salary'];
            $array['d2']['bu'] += $y['mx_bu'];
            $array['d2']['kou'] += $y['mx_kou'];
            $array['d2']['jia'] += $y['mx_jia'];
            $array['d2']['lin'] += $y['mx_lin'];
            $array['d2']['ying'] += $y['mx_ying'];
            $array['d2']['kong']=' ';  //特别空一列
            $array['d2']['gh'] += $y['mx_gh'];
            $array['d2']['gong'] += $y['mx_gong'];
            $array['d2']['yang'] += $y['mx_yang'];
            $array['d2']['shi'] += $y['mx_shi'];
            $array['d2']['yi'] += $y['mx_yi'];
            $array['d2']['bujiao'] += $y['mx_bujiao'];
            $array['d2']['bugong'] += $y['mx_bugong'];
            $array['d2']['shui'] += $y['mx_shui'];
            $array['d2']['fang'] += $y['mx_fang'];
            $array['d2']['shifa'] += $y['mx_shifa'];
        }
        foreach ($data_3 as $m=>$n){
            $array['d3']['sum'] ++;
            $array['d3']['salary'] += $n['mx_salary'];
            $array['d3']['bu'] += $n['mx_bu'];
            $array['d3']['kou'] += $n['mx_kou'];
            $array['d3']['jia'] += $n['mx_jia'];
            $array['d3']['lin'] += $n['mx_lin'];
            $array['d3']['ying'] += $n['mx_ying'];
            $array['d3']['kong'] =' ';  //特别空一列
            $array['d3']['gh'] += $n['mx_gh'];
            $array['d3']['gong'] += $n['mx_gong'];
            $array['d3']['yang'] += $n['mx_yang'];
            $array['d3']['shi'] += $n['mx_shi'];
            $array['d3']['yi'] += $n['mx_yi'];
            $array['d3']['bujiao'] += $n['mx_bujiao'];
            $array['d3']['bugong'] += $n['mx_bugong'];
            $array['d3']['shui'] += $n['mx_shui'];
            $array['d3']['fang'] += $n['mx_fang'];
            $array['d3']['shifa'] += $n['mx_shifa'];
        }
//        $year = date('Y',time());
//        $mon = date('m',time());
//        $filename = $year."年".$mon."月份";
        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'工资汇总表',function($excel) use ($array){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:S1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  8,
                    'C'     =>  12,
                    'D'     =>  10,
                    'E'     =>  8,
                    'F'     =>  10,
                    'G'     =>  10,
                    'H'     =>  12,
                    'I'     =>  2,
                    'J'     =>  6,
                    'K'     =>  10,
                    'L'     =>  10,
                    'M'     =>  12,
                    'N'     =>  6,
                    'O'     =>  6,
                    'P'     =>  5,
                    'Q'     =>  10,
                    'R'     =>  8,
                    'S'     =>  12,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'工资汇总表']);
                $sheet->row(2, ['部门','人数','岗位工资','工龄补贴','补扣工资','加班费','临时项','应发数','','工会补贴',
                    '公积金','养老金', '失业保险','医疗保险','社交统筹','补公积','个人所得税','房租','实发数']);//第二行填充数据;
                $sheet->cells('A1:S1', function($cells){
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '22',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:S2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                        'right'   => array(
                            'style' => 'solid'
                        ),
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                        'left'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
                $m = count($array);

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($m, 25);
                    $m++;

                }
                $sheet->cells('A'.(count($array)).':S'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid',
                        ),
                        'right'   => array(
                            'style' => 'solid',
                        ),
                        'bottom'   => array(
                            'style' => 'solid',
                        ),
                        'left'   => array(
                            'style' => 'solid',
                        ),
                    ));
                });
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('E2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('J2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('N2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('O2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('P2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('Q2')->getAlignment()->setWrapText(true);

                //倒数第二步：合计栏
                $sheet->row($m, ['合计']);
                $sheet->setHeight($m, 25);
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A'.(count($array)-1).':S'.$m, 'thin');

                //边界----------
            });
        })->export('xls');
    }

    public function export_huizong2(){
        $data = Mx::orderBy('mx_id')->get();
//得到部门1是1的数据（真实-管理总部）、2是2（真实-业务总部）、3是3（真实-云卫通）
        $data_1 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',1)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $data_2 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',2)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $data_3 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen1',3)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();
        $data_4 =Mx::leftjoin('job','mx.mx_name','=','job.job_name')->where('job.job_bumen2',4)->where('job.job_real',1)->select("mx.*","job.job_bumen2")->get();

        $array = [
            'd1'=>[
                'bumen'=>'管理总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
            'd2'=>[
                'bumen'=>'业务总部', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
            'd3'=>[
                'bumen'=>'云卫通', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
            'd4'=>[
                'bumen'=>'研发部门', 'sum'=> 0 , 'salary'=>0 , 'bu'=> 0, 'kou'=> 0, 'jia'=> 0, 'lin'=> 0, 'ying'=>0 ,'kong'=>'',
                'gh'=>0 , 'gong'=>0 , 'yang'=>0 , 'shi'=>0, 'yi'=> 0, 'bujiao'=>0 , 'bugong'=> 0, 'shui'=> 0, 'fang'=> 0, 'shifa'=>0,
            ],
        ];
        foreach ($data_1 as $k=>$v){
            $array['d1']['sum'] ++;
            $array['d1']['salary'] += $v['mx_salary'];
            $array['d1']['bu'] += $v['mx_bu'];
            $array['d1']['kou'] += $v['mx_kou'];
            $array['d1']['jia'] += $v['mx_jia'];
            $array['d1']['lin'] += $v['mx_lin'];
            $array['d1']['ying'] += $v['mx_ying'];
            $array['d1']['kong']=' ';  //特别空一列
            $array['d1']['gh'] += $v['mx_gh'];
            $array['d1']['gong'] += $v['mx_gong'];
            $array['d1']['yang'] += $v['mx_yang'];
            $array['d1']['shi'] += $v['mx_shi'];
            $array['d1']['yi'] += $v['mx_yi'];
            $array['d1']['bujiao'] += $v['mx_bujiao'];
            $array['d1']['bugong'] += $v['mx_bugong'];
            $array['d1']['shui'] += $v['mx_shui'];
            $array['d1']['fang'] += $v['mx_fang'];
            $array['d1']['shifa'] += $v['mx_shifa'];
        }
        foreach ($data_2 as $x=>$y){
            $array['d2']['sum'] ++;
            $array['d2']['salary'] += $y['mx_salary'];
            $array['d2']['bu'] += $y['mx_bu'];
            $array['d2']['kou'] += $y['mx_kou'];
            $array['d2']['jia'] += $y['mx_jia'];
            $array['d2']['lin'] += $y['mx_lin'];
            $array['d2']['ying'] += $y['mx_ying'];
            $array['d2']['kong']=' ';  //特别空一列
            $array['d2']['gh'] += $y['mx_gh'];
            $array['d2']['gong'] += $y['mx_gong'];
            $array['d2']['yang'] += $y['mx_yang'];
            $array['d2']['shi'] += $y['mx_shi'];
            $array['d2']['yi'] += $y['mx_yi'];
            $array['d2']['bujiao'] += $y['mx_bujiao'];
            $array['d2']['bugong'] += $y['mx_bugong'];
            $array['d2']['shui'] += $y['mx_shui'];
            $array['d2']['fang'] += $y['mx_fang'];
            $array['d2']['shifa'] += $y['mx_shifa'];
        }
        foreach ($data_3 as $m=>$n){
            $array['d3']['sum'] ++;
            $array['d3']['salary'] += $n['mx_salary'];
            $array['d3']['bu'] += $n['mx_bu'];
            $array['d3']['kou'] += $n['mx_kou'];
            $array['d3']['jia'] += $n['mx_jia'];
            $array['d3']['lin'] += $n['mx_lin'];
            $array['d3']['ying'] += $n['mx_ying'];
            $array['d3']['kong'] =' ';  //特别空一列
            $array['d3']['gh'] += $n['mx_gh'];
            $array['d3']['gong'] += $n['mx_gong'];
            $array['d3']['yang'] += $n['mx_yang'];
            $array['d3']['shi'] += $n['mx_shi'];
            $array['d3']['yi'] += $n['mx_yi'];
            $array['d3']['bujiao'] += $n['mx_bujiao'];
            $array['d3']['bugong'] += $n['mx_bugong'];
            $array['d3']['shui'] += $n['mx_shui'];
            $array['d3']['fang'] += $n['mx_fang'];
            $array['d3']['shifa'] += $n['mx_shifa'];
        }
        foreach ($data_4 as $m=>$n){
            $array['d4']['sum'] ++;
            $array['d4']['salary'] += $n['mx_salary'];
            $array['d4']['bu'] += $n['mx_bu'];
            $array['d4']['kou'] += $n['mx_kou'];
            $array['d4']['jia'] += $n['mx_jia'];
            $array['d4']['lin'] += $n['mx_lin'];
            $array['d4']['ying'] += $n['mx_ying'];
            $array['d4']['kong'] =' ';  //特别空一列
            $array['d4']['gh'] += $n['mx_gh'];
            $array['d4']['gong'] += $n['mx_gong'];
            $array['d4']['yang'] += $n['mx_yang'];
            $array['d4']['shi'] += $n['mx_shi'];
            $array['d4']['yi'] += $n['mx_yi'];
            $array['d4']['bujiao'] += $n['mx_bujiao'];
            $array['d4']['bugong'] += $n['mx_bugong'];
            $array['d4']['shui'] += $n['mx_shui'];
            $array['d4']['fang'] += $n['mx_fang'];
            $array['d4']['shifa'] += $n['mx_shifa'];
        }
//        $year = date('Y',time());
//        $mon = date('m',time());
//        $filename = $year."年".$mon."月份";
        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'工资汇总表',function($excel) use ($array){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:S1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  8,
                    'C'     =>  12,
                    'D'     =>  10,
                    'E'     =>  8,
                    'F'     =>  10,
                    'G'     =>  10,
                    'H'     =>  12,
                    'I'     =>  2,
                    'J'     =>  6,
                    'K'     =>  10,
                    'L'     =>  10,
                    'M'     =>  12,
                    'N'     =>  6,
                    'O'     =>  6,
                    'P'     =>  5,
                    'Q'     =>  10,
                    'R'     =>  8,
                    'S'     =>  12,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'工资汇总表']);
                $sheet->row(2, ['部门','人数','岗位工资','工龄补贴','补扣工资','加班费','临时项','应发数','','工会补贴',
                    '公积金','养老金', '失业保险','医疗保险','社交统筹','补公积','个人所得税','房租','实发数']);//第二行填充数据;
                $sheet->cells('A1:S1', function($cells){
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '22',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:S2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                        'right'   => array(
                            'style' => 'solid'
                        ),
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                        'left'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
                $m = count($array);

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight(($m-1), 25);
                    $m++;

                }
                $sheet->cells('A'.(count($array)-1).':S'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid',
                        ),
                        'right'   => array(
                            'style' => 'solid',
                        ),
                        'bottom'   => array(
                            'style' => 'solid',
                        ),
                        'left'   => array(
                            'style' => 'solid',
                        ),
                    ));
                });
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('E2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('J2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('N2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('O2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('P2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('Q2')->getAlignment()->setWrapText(true);

                //倒数第二步：合计栏
                $sheet->row($m-1, ['合计']);
                $sheet->setHeight($m-1, 25);
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A'.(count($array)-2).':S'.($m-1), 'thin');

                //边界----------
            });
        })->export('xls');
    }

    public function export_shui(){
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
            if ($array[$k]['salary']>=3500){
                $array[$k]['jishui']= $array[$k]['salary'] - 3500 - $array[$k]['yingkou'];
             }else{
                $array[$k]['jishui']= 0;
            }
            $array[$k]['bili']=(new Mx())->getShui_bili($v['e_name'],$v['mx_ying']);
            //为了保证顺序，2个if就不合并了。
            if ($array[$k]['salary']>=3500){
                $array[$k]['kou2']=3500;
            }else{
                $array[$k]['kou2']=$array[$k]['salary'];
            }
            $array[$k]['shui']=(new Mx())->getShui($v['e_name'],$v['mx_ying']);
            $whole+= $array[$k]['shui'];
        }

        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'工资汇总表',function($excel) use ($array,$whole){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array,$whole){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:J1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  5,
                    'B'     =>  15,
                    'C'     =>  35,
                    'D'     =>  12,
                    'E'     =>  12,
                    'F'     =>  20,
                    'G'     =>  12,
                    'H'     =>  12,
                    'I'     =>  10,
                    'J'     =>  10,

                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'个人所得税代扣代缴表']);
                $sheet->row(2, ['序号','纳税人姓名','身份证号','放发总额','扣除数','应扣养老公积金','计税基数','交税比例','扣除数','个税']);//第二行填充数据;
                $sheet->cells('A1:J1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '22',
                        'bold'       =>  true
                    ));
                });
                $sheet->cells('A2:J2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                $m = count($array);

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($m, 25);
                    $m++;
                }

                $sheet->cells('A'.(count($array)-1).':J'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));

                });
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('E2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('J2')->getAlignment()->setWrapText(true);

                //倒数第二步：合计栏
                $sheet->row($m, ['','合计','','','','','','','',$whole]);
                $sheet->setHeight($m, 25);
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A'.(count($array)).':J'.$m, 'thin');

                //边界----------
            });
        })->export('xls');
    }

    public function export_gongzi(){
        $mx = Mx::leftjoin('employee','employee.e_name','=','mx.mx_name')->select('mx.mx_name','mx.mx_shifa','employee.e_zhao')->get();

        $array = array();
        $sum = 1;
        $whole = 0;
        foreach ($mx as $k=>$v){
            $array[$k]['id']=$sum;
            $sum++;
            $array[$k]['name']=$v['mx_name'];
            $array[$k]['zhao']=$v['e_zhao'];
            $array[$k]['shifa']=$v['mx_shifa'];
            $whole += $v['mx_shifa'];
        }

        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'工资汇总表',function($excel) use ($array,$whole){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array,$whole){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:D1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setHeight(2, 25);
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  30,
                    'C'     =>  60,
                    'D'     =>  30,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'工资汇总表']);
                $sheet->row(2, ['序号','姓名','招商银行账号','实发数']);//第二行填充数据;
                $sheet->cells('A1:D1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '22',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:D2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                        'right'   => array(
                            'style' => 'solid'
                        ),
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                        'left'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
                $m = count($array);

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($m, 25);
                    $m++;

                }

                $sheet->cells('A'.(count($array)).':S'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid',
                        ),
                        'right'   => array(
                            'style' => 'solid',
                        ),
                        'bottom'   => array(
                            'style' => 'solid',
                        ),
                        'left'   => array(
                            'style' => 'solid',
                        ),
                    ));
                });
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);

                //倒数第二步：合计栏
                $sheet->row($m, ['','合计','',$whole]);
                $sheet->setHeight($m, 25);
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A'.(count($array)-1).':S'.$m, 'thin');

                //边界----------
            });
        })->export('xls');
    }
//绩效明细
    public function export_jixiao(){
        $data = Mx::orderBy('mx_id')->get();

        $array = array();
        foreach ($data as $k => $v){
            $array[$k]['mx_id']=$v['mx_id'];
            $array[$k]['mx_name']=$v['mx_name'];
            $array[$k]['mx_salary']=$v['mx_salary'];
            $array[$k]['mx_bu']=$v['mx_bu'];
            $array[$k]['mx_kou']=$v['mx_kou'];
            $array[$k]['mx_jia']=$v['mx_jia'];
            $array[$k]['mx_lin']=$v['mx_lin'];
            $array[$k]['mx_ying']=$v['mx_ying'];
            $array[$k]['mx_kong']=' ';  //特别空一列
            $array[$k]['mx_gh']=$v['mx_gh'];
            $array[$k]['mx_gong']=$v['mx_gong'];
            $array[$k]['mx_yang']=$v['mx_yang'];
            $array[$k]['mx_shi']=$v['mx_shi'];
            $array[$k]['mx_yi']=$v['mx_yi'];
            $array[$k]['mx_bujiao']=$v['mx_bujiao'];
            $array[$k]['mx_bugong']=$v['mx_bugong'];
            $array[$k]['mx_shui']=$v['mx_shui'];
            $array[$k]['mx_fang']=$v['mx_fang'];
            $array[$k]['mx_shifa']=$v['mx_shifa'];
        }

//        $year = date('Y',time());
//        $mon = date('m',time());
//        $filename = $year."年".$mon."月份";
        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'工资汇总表',function($excel) use ($array){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:J1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  5,
                    'B'     =>  10,
                    'C'     =>  15,
                    'D'     =>  6,
                    'E'     =>  7,
                    'F'     =>  10,
                    'G'     =>  10,
                    'H'     =>  12,
                    'I'     =>  2,
                    'J'     =>  6,
                    'K'     =>  10,
                    'L'     =>  10,
                    'M'     =>  12,
                    'N'     =>  6,
                    'O'     =>  6,
                    'P'     =>  5,
                    'Q'     =>  10,
                    'R'     =>  8,
                    'S'     =>  12,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'工资汇总表']);
                $sheet->row(2, ['部门','姓名','岗位工资','工龄补贴','补扣工资','加班费','临时项','应发数','','工会补贴',
                    '公积金','养老金', '失业保险','医疗保险','社交统筹','补公积','个人所得税','房租','实发数']);//第二行填充数据;
                $sheet->cells('A1:S1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '22',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:S2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                        'right'   => array(
                            'style' => 'solid'
                        ),
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                        'left'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
                $m = count($array)+1;

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($m, 25);
                    $m++;

                }

                $sheet->cells('A'.(count($array)+1).':S'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid',
                        ),
                        'right'   => array(
                            'style' => 'solid',
                        ),
                        'bottom'   => array(
                            'style' => 'solid',
                        ),
                        'left'   => array(
                            'style' => 'solid',
                        ),
                    ));
                });
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('E2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('J2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('N2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('O2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('P2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('Q2')->getAlignment()->setWrapText(true);

                //倒数第二步：合计栏
                $sheet->row($m, ['合计']);
                $sheet->setHeight($m, 25);
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A'.count($array).':S'.$m, 'thin');

                //边界----------
            });
        })->export('xls');
    }
//月绩效奖金
    public function export_jiangjin()
    {
        $data = Jx2::leftjoin('employee','jx2.jx2_name','=','employee.e_name')->orderBy('jx2_id')
            ->select('employee.e_zhong','jx2.jx2_name','jx2.jx2_shi','jx2.jx2_id')->get();
        $array = array();
        $sum = 0;
        foreach ($data as $k => $v){
            $array[$k]['id']=$v['jx2_id'];
            $array[$k]['name']=$v['jx2_name'];
            $array[$k]['zhong']=$v['e_zhong'];
            $array[$k]['shi']=$v['jx2_shi'];
            $sum +=$v['jx2_shi'];
        }

        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'月绩效奖金',function($excel) use ($array,$sum){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array,$sum){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:D1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  25,
                    'C'     =>  50,
                    'D'     =>  25,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'月绩效奖金']);
                $sheet->row(2, ['序号','人数','绩效工资基数','实发数']);//第二行填充数据;
                $sheet->cells('A1:D1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '20',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:D2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                $n =0;

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($n+3, 25);
                    $n++;

                }
                $m = count($array)+2;//总行数
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                //文字自动换行
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                //倒数第二步：合计栏
                $m++;//合计栏在总行数上+1
                $sheet->row($m, ['','合计','',$sum]);
                $sheet->setHeight($m, 25);
                $sheet->cells('A3:D'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A1:D'.$m, 'thin');

                //边界----------
            });
        })->export('xls');
    }
//绩效汇总表一
    public function export_jxhz1()
    {
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

        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'月份绩效工资汇总表',function($excel) use ($array_1,$array_2,$array_3,$array_heji){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array_1,$array_2,$array_3,$array_heji){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:D1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  30,
                    'B'     =>  15,
                    'C'     =>  35,
                    'D'     =>  35,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'绩效工资汇总表']);
                $sheet->row(2, ['序号','姓名','中行账号','金额（元）']);//第二行填充数据;
                $sheet->cells('A1:D1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '20',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:D2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });

                $sheet->appendRow($array_1);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                $sheet->setHeight(3, 25);
                $sheet->appendRow($array_2);
                $sheet->setHeight(4, 25);
                $sheet->appendRow($array_3);
                $sheet->setHeight(5, 25);

                //文字自动换行
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                //倒数第二步：合计栏
                $sheet->row(6, ['合计',$array_heji['sum'],$array_heji['base'],$array_heji['shi']]);
                $sheet->setHeight(6, 25);
                $sheet->cells('A3:D6', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A1:D6', 'thin');

                //边界----------
            });
        })->export('xls');
    }
//绩效汇总表二
    public function export_jxhz2()
    {
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

        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'月份绩效工资汇总表',function($excel) use ($array_1,$array_2,$array_3,$array_4,$array_heji){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array_1,$array_2,$array_3,$array_4,$array_heji){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:D1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  30,
                    'B'     =>  15,
                    'C'     =>  35,
                    'D'     =>  35,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月份".'绩效工资汇总表']);
                $sheet->row(2, ['序号','人数','绩效工资基数','实发数']);//第二行填充数据;
                $sheet->cells('A1:D1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '20',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:D2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });

                $sheet->appendRow($array_1);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                $sheet->setHeight(3, 25);
                $sheet->appendRow($array_2);
                $sheet->setHeight(4, 25);
                $sheet->appendRow($array_3);
                $sheet->setHeight(5, 25);
                $sheet->appendRow($array_4);
                $sheet->setHeight(5, 25);

                //文字自动换行
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                //倒数第二步：合计栏
                $sheet->row(7, ['合计',$array_heji['sum'],$array_heji['base'],$array_heji['shi']]);
                $sheet->setHeight(7, 25);
                $sheet->cells('A3:D7', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A1:D7', 'thin');

                //边界----------
            });
        })->export('xls');
    }
//三项补贴表
    public function export_sanbu()
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

        Excel::create('南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time()).'月三项补贴',function($excel) use ($array,$sum){
            //创建文件中第一个sheet
            $excel->sheet('南京中网卫星通信股份有限公司', function($sheet) use ($array,$sum){   //上面和这个function 要use()否则数组引入不进去
                $sheet->mergeCells('A1:F1');                    //合并A1:E1:单元格
                $sheet->setHeight(1, 40);                       //设置第一行行高50
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  20,
                    'C'     =>  50,
                    'D'     =>  25,
                    'E'     =>  20,
                    'F'     =>  25,
                ));
                $sheet->row(1, ['南京中网卫星通信股份有限公司'.date('Y',time())."年".date('m',time())."月".'三项补贴']);
                $sheet->row(2, ['序号','姓名','招行卡号','三补额度','未交发票','本月实发数']);//第二行填充数据;
                $sheet->cells('A1:F1', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '20',
                        'bold'       =>  true
                    ));
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => ''
                        ),
                    ));
                });
                $sheet->cells('A2:F2', function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                $n =0;

                foreach ($array as $k => $v){
                    $sheet->appendRow($v);          //要先appendRow,否则setHeight会先创一行，然后 appendRow就append到下一行，导致出现空行。
                    $sheet->setHeight($n+3, 25);
                    $n++;

                }
                $m = count($array)+2;//总行数
                //使用PHPExcel本地方法
                //$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);
                //去掉以前的->getActiveSheet()
                //文字自动换行
                $sheet->getStyle('D2')->getAlignment()->setWrapText(true);
                //倒数第二步：合计栏
                $m++;//合计栏在总行数上+1
                $sheet->row($m, ['','合计','','','',$sum]);
                $sheet->setHeight($m, 25);
                $sheet->cells('A3:F'.$m, function($cells) {
                    $cells->setAlignment('center');            //水平居中
                    $cells->setValignment('center');           //垂直居中
                    $cells->setFont(array(
                        'family'     => '宋体',
                        'size'       => '12',
                        'bold'       =>  false
                    ));
                });
                //最后，设置边框必须放到最后，不然最先生成有边框的空白cells
                $sheet->setBorder('A1:F'.$m, 'thin');

                //边界----------
            });
        })->export('xls');
    }

}