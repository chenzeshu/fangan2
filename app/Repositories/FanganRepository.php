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
    //返回AJAX信息
    public $callback_success = [
        "status"=>0,
        "msg"=>"删除成功"
    ];

    public $callback_error = [
        "status"=>1,
        "msg"=>"删除失败"
    ];

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

        return $data;
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
                    $pro->pros_display_inprice = $pro->pros_inprice * $mei;
                    $pro->pros_display_outprice = $pro->pros_outprice * $mei * 1.2285; //1.17*1.05 = 1.2285
                    break;
                case "2": //欧元
                    $pro->pros_display_inprice = $pro->pros_inprice * $eu;
                    $pro->pros_display_outprice = $pro->pros_outprice * $eu * 1.2285;
                    break;
                default: //人民币
                    $pro->pros_display_inprice = $pro->pros_inprice;
                    $pro->pros_display_outprice = $pro->pros_outprice * 1.2285;
                    break;
            }
            //若有比例
            if($pro->pros_flag_bili == 2){
                $pro->pros_display_outprice = $pro->pros_display_inprice * $bili;
            }
            //todo 报价精确到百位
            $pro->pros_display_inprice = round($pro->pros_display_inprice,-2);
            $pro->pros_display_outprice = round($pro->pros_display_outprice, -2);
        }
        return $pros;
    }

    /**
     * 删除系统/设备，并暴露本方法给控制器
     * @param $db
     * @param $id
     * @return bool|mixed
     */
    public function deleteDevices($db, $id)
    {
        $whether = $db->where('id',$id)->first();
        if ($sys = $whether->sys){
            $re = $this->deleteFatherAndSon($id, $sys);
        }else{
            $re = $this->deleteOnlySon($id);
        }
        return $re;
    }
    /**
     * 删除系统则删除系统+系统下所有设备
     * @param $id
     * @param $sys
     * @return bool
     */
    private function deleteFatherAndSon($id, $sys)
    {
        //todo 删除系统
        DB::table(session('table'))->where('id',$id)->delete();
        //todo 删除子设备
        $exist = DB::table(session('table'))->where('father',$sys)->first();
        //todo 假如子设备是空的
        $re = true;
        if($exist){
            //todo 假如存在子设备
            $re= DB::table(session('table'))->where('father',$sys)->delete();
        }
        return $re;
    }

    /**
     * 仅删除子设备
     * @param $id
     * @return mixed
     */
    private function deleteOnlySon($id){
        $re = DB::table(session('table'))->where('id',$id)->delete();
        return $re;
    }
}