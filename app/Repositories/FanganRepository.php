<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/29
 * Time: 9:59
 */

namespace App\Repositories;


use App\Model\Params;
use Illuminate\Support\Facades\DB;

class FanganRepository
{
    public function sessionDeal($self_id)
    {
        $username = session('username');
        session(['table_id' => $self_id]);
        session(['table' => 'space_'.$username.'_'.$self_id]);

        return $username;
    }

    /**
     * 根据顺序表，按顺序展示方案表
     * @return mixed
     */
    public function judgeOrder()
    {
        $table_name = session('table');
        $order_table = $table_name.'_order';
        //判断顺序表是否有内容
        $num = DB::table($order_table)->count();
        if ($num){
            //todo 如果order表有order
            $orderData = DB::table($order_table)->first();
            $order = $orderData->order;
            if (!$order == ""){
                //todo 如果$order不为空，即有顺序时，最终按最后更新在最上原则
                $data = DB::table($table_name)->orderBy(DB::raw('FIELD(id,'.$order.')'))->get();
            }else{
                //todo 如果order有第一条数据，但是order字段为空（通常发生在刚建表时）
                $data = DB::table($table_name)->orderBy('son', 'desc')->orderBy('id', 'desc')->get();  //先按系统排，也可以用groupBy
            }
        }else{
            //todo 如果order表为空，就按原生field()
            $data = DB::table($table_name)->orderBy('son', 'desc')->orderBy('id', 'desc')->get();  //先按系统排，也可以用groupBy
        }

        return array_reverse($data);
    }

    /**
     * 根据汇率、税率及比例，对填写的价格进行换算，最后得到以人民币为单位的价格。
     * @param $pros
     * @return mixed
     */
    public function getDisplayPrice($pros)
    {
        //todo 处理每条的display_price
        //todo 拿到当前的汇率
        $params = Params::find(1);
        $mei = $params->pa_dollar;// 美元汇率
        $eu = $params->pa_eu;// 欧元汇率
        $bili = $params->pa_bili;// 报价比例
        //todo 处理价格
        foreach ($pros as $k => $pro){
            switch ($pro->pros_flag_money){
                case "1": //美元
                    $pro->pros_display_inprice = $pro->pros_inprice * $mei * 1.2285; //1.17*1.05 = 1.2285
                    $pro->pros_display_outprice = $pro->pros_outprice * $mei * 1.2285;
                    break;
                case "2": //欧元
                    $pro->pros_display_inprice = $pro->pros_inprice * $eu * 1.2285;
                    $pro->pros_display_outprice = $pro->pros_outprice * $eu * 1.2285;
                    break;
                default: //人民币
                    $pro->pros_display_inprice = $pro->pros_inprice * 1.2285;
                    $pro->pros_display_outprice = $pro->pros_outprice * 1.2285;
                    break;
            }
            //若有比例
            if($pro->pros_flag_bili == 2){
                $pro->pros_display_outprice = $pro->pros_display_inprice * $bili;
            }
//            if($pro->pros_id == 2){
//                dd($pro);
//            }
        }
        return $pros;
    }
}