<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/29
 * Time: 10:00
 */

namespace App\Repositories;


class ProsRepositories
{
    public function judgeInprice($flag, $inprice, $dollar, $eu)
    {
        if ($flag == 1 ){
            //系数1 美元
            return $inprice * $dollar ;
        }elseif($flag == 2 ){
            //系数2 欧元
            return $inprice * $eu ;
        }else{
            //系数3 人民币
            return $inprice;
        }
    }

    public function judgeOutprice($flag, $outprice, $inprice, $bili)
    {
        if ($flag==1){
            //系数1 无比例
            return $outprice;
        }else{
            //系数2 有比例
            return $inprice * $bili;
        }
    }

    public function changeResponse($re)
    {
        if($re){
            $data = [
                'status'=> 0,
                'msg' => '成功',
            ];
        }else{
            $data = [
                'status'=> 1,
                'msg' => '失败，请稍后重试',
            ];
        }
        return $data;
    }
}