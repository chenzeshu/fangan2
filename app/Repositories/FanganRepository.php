<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/29
 * Time: 9:59
 */

namespace App\Repositories;


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
                //todo 如果$order不为空
                $data = DB::table($table_name)->orderBy(DB::raw('FIELD(id,'.$order.')'))->get();
            }else{
                //todo 如果order为空（通常发生在刚建表时）
                $data = DB::table($table_name)->orderBy('son')->orderBy('id')->get();  //先按系统排，也可以用groupBy
            }
        }else{
            //todo 如果没有order数据，就按原生field()
            $data = DB::table($table_name)->orderBy('son')->orderBy('id')->get();  //先按系统排，也可以用groupBy
        }

        return $data;
    }
}