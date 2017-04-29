<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/29
 * Time: 11:37
 */

namespace App\Repositories;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SelfRepository
{
    public function createSelfSpace()
    {
        $username = session('username');
        Schema::create('space_'.$username,function (Blueprint $table){
            $table->increments('self_id');
            $table->string('self_name')->unique();
            $table->string('created_at',50);
            $table->string('updated_at',50)->nullable();
        });
    }

    public function createSelfTable($id, $username)
    {
        Schema::create('space_'.$username.'_'.$id,function (Blueprint $table){
            $table ->increments('id');
            //分系统名 todo 与数据库用的不是同一套系统名，一共8个，采用int方便排序
            //todo 第二，设备不需要有系统名，只需要知道自己是哪个系统的儿子就行了
            $table ->integer('sys')->default(0)->nullable();
            $table ->string('name')->nullable();   //设备名称
            $table ->string('goodsid')->nullable();   //物资编码
            $table ->string('brand')->nullable();  //品牌
            $table ->string('detail')->nullable(); //设备型号
            $table ->text('less')->nullable();     //简单描述
            $table ->text('more')->nullable();     //详细描述
            $table ->integer('number')->nullable();//数量
            $table ->string('unit')->nullable();   //单位
            $table ->string('area')->nullable();   //产地
            $table ->string('vol')->nullable();    //体积
            $table ->integer('u')->nullable();     //U
            $table ->float('kg',5,2)->nullable();  //重量
            $table ->float('w',5,2)->nullable();   //功耗
            $table ->integer('display_inprice')->nullable();//todo 插入换算后的成本单价
            $table ->integer('display_outprice')->nullable();//todo 插入换算后的出厂价格
            $table ->text('remark')->nullable();     //备注
            $table ->string('thumb')->nullable();    //缩略图
            $table ->string('img')->nullable();   //大图
            $table ->string('flag_money')->default(1)->nullable(); //币种 1美元 2欧元 3人民币
            $table ->string('flag_bili')->default(1)->nullable(); //比例 1无比例 2有比例
//                    $table ->integer('type')->default(2);  //1为系统 2为设备
            //todo 设备的系统编号,与分系统编号对应 系统级写0||这项存在，就没必要上一个type了
            $table ->integer('father')->default(0)->nullable();
            $table ->integer('son')->default(999)->nullable();
            $table ->integer('sysid')->nullable();  //对应pros数据库的id
            $table ->string('img_other')->nullable(); //补充图片
            $table ->string('img_other_name')->nullable(); //补充图片的名字
        });
    }

    public function createSelfOrderTable($id, $username)
    {
        Schema::create('space_'.$username.'_'.$id.'_order',function (Blueprint $table){
            $table->increments('id');
            $table->string('order',777)->nullable();
        });
    }
}