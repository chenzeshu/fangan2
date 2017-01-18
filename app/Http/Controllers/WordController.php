<?php

namespace App\Http\Controllers;

use App\Model\Pros;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;

//require_once base_path()."/vendor/phpoffice/phpword/samples/Sample_Header.php";

class WordController extends Controller
{
    //表格导出方法1
    public function export()//测试成功 文件出现在gongz/下
    {
        //todo 实例化word并创造文件
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(
            array(
                'alignment'  => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
//                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                'spacing'    => 120,
            )
        );
        $section = $phpWord->addSection();
        //todo 查询方案表
//        $pros = Pros::find(874);
        $pros = DB::table('space_admin_29')->where('id',4)->first();
        //todo 系统名 二号宋体
        $sys = $pros->sys;
        //后期改为方案表的系统名，用foreach
        $section->addText(
            $sys,
            array('name' => '宋体', 'size' => 22,'bold'=>true),
            array('space' => array('before' => 360, 'after' => 480),)
        );
//        //todo 设备1名 三号宋体
        $name = $pros->name;
        $section->addText(
            $name,
            array('name' => '宋体', 'size' => 16,'bold'=>true),
            array('space' => array('before' => 360, 'after' => 480),)
        );
        //todo 设备1型号 三号宋体
        $detail = $pros->detail;
        $section->addText(
            $detail,
            array('name' => '宋体', 'size' => 16,'bold'=>true),
            array('space' => array('before' => 360, 'after' => 480),)
        );
        //todo 套话
        //选用 型号 +名称
        $section->addText(
            '选用'.$detail.$name.'。',
            array('name' => '宋体', 'size' => 12,'bold'=>true),
            array('space' => array('before' => 360, 'after' => 480),)
        );
//        //todo 设备图片
        $img = $pros->img;
        $img_path =base_path().'/'.$img;
        $section->addImage($img_path);
//        //todo 详细描述(可能是表格)
        $html = $pros->more;
//        dd($html);
        //如果存在表格
        $index = strpos($html,'<table');

        if ($index!==false){
            $add = '<table style="width: 100%; border: 6px #000000 solid;" ';//表格格式，后部要有个空格
            $html = substr_replace($html,$add,$index,6);
        }
        dd($html);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);
        //todo 额外图片，格式：首行图片名+图片
        $img_other = explode(',',$pros->img_other); //字符串打散为数组
        foreach ($img_other as $v){
            $section->addImage(base_path().'/'.$v);
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('helloWorld.docx');
    }

    function cword($data,$fileName='')
    {
        if(empty($data)) return '';

        $data = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">'.$data.'</html>';
        $dir  = "./docfile/".date("Ymd")."/";

        if(!file_exists($dir)) mkdir($dir,777,true);

        if(empty($fileName))
        {
            $fileName=$dir.date('His').'.doc';
        }
        else
        {
            $fileName =$dir.$fileName.'.doc';
        }

        $writefile = fopen($fileName,'wb') or die("创建文件失败"); //wb以二进制写入
        fwrite($writefile,$data);
        fclose($writefile);
        return $fileName;
    }

    //todo 方案WORD导出
    public function export_word($tablename)
    {
        //1.规定系统名
        //2.开始遍历本系统下的设备
        //3.进入下个系统的循环
        //原则：尽量少搜索数据库，即使多遍历一次

        //todo 实例化word并创造文件
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontSize(12);
        $phpWord->setDefaultParagraphStyle(
            array(
                'alignment'  => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
//                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
                'spacing'    => 120,
            )
        );
        $section = $phpWord->addSection();
        //todo 得到表的数据
        $proinfo = DB::table(session('table'))->get(); //因为fangan的index页面已经做好了order，所以直接get
        foreach ($proinfo as $pro){
            if ($pro->sys!=0){
        //todo 系统名 二号宋体
                                    //todo 加一个空行以区分每个系统
                                    $section->addText('');
                $section->addText(
                    $pro->name,
                    array('name' => '宋体', 'size' => 22,'bold'=>true,'spacing'=>11),
                    array('keepNext' => true)
                );
                foreach ($proinfo as $_pro){
                    if ($_pro->father == $pro->sys){  //todo 如果是本系统下的
        //todo 设备1名 三号宋体
                                    //todo 加一个空行以区分每个设备
                                    $section->addText('');
                        $name = $_pro->name;
                        $section->addText(
                            $name,
                            array('name' => '宋体', 'size' => 16,'bold'=>true,'spacing'=>8),
                            array('keepNext' => true)
                        );
        //todo 设备1型号 三号宋体
                        $detail = $_pro->detail;
                        $section->addText(
                            $detail,
                            array('name' => '宋体', 'size' => 16,'bold'=>true,'spacing'=>8)
                        );
        //todo 套话
                        //选用 空格+品牌+空格+型号 +名称
                        $brand = $_pro->brand;
                        $section->addText(
                            '选用'.$brand.$detail.$name.'。',
                            array('name' => '宋体', 'size' => 12,'bold'=>true,'spacing'=>8),
                            array('indentation' => array('firstLine' => 480))  //首行缩进2个汉字宽度
                        );
        //todo 设备图片
                        if ($_pro->img!=''){ //如果存在图片
                            $img = $_pro->img;
                            $img_path =base_path().'/'.$img;
                            $section->addImage($img_path,array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                        }
        //todo 详细描述(可能是表格)
                        $html = $_pro->more;
                        //todo 如果存在表格
                        $index = strpos($html,'<table');
                        if ($index!==false){
                            $add = '<table style="width: 100%; border: 6px #000000 solid;" ';//表格格式，后部要有个空格
                            $html = substr_replace($html,$add,$index,6);
                        }
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);
        //todo 额外图片，格式：首行图片名+图片
                        if ($_pro->img_other!=''){//如果存在图片
                            //todo 字符串打散为数组
                            $img_other = explode(',',$_pro->img_other);
                            $img_other_name = explode(',',$_pro->img_other_name);//explode可以把一个成员的数组专成字符串

                            foreach ($img_other as $k=>$v){
                                $section->addText($img_other_name[$k],
                                    array('name' => '宋体', 'size' => 16,'bold'=>true,'spacing'=>8));
                                $section->addImage(base_path().'/'.$v,array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                            }
                        }

                    }

                }
            }
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $objWriter->save('helloWorld.docx');
        header('Content-type: application/word');
        header('Content-Disposition: attachment; filename="helloWorld.docx"');
        $objWriter->save('php://output');
    }
}
