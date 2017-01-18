<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class Base extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //参数表  blueprint意为蓝图
        Schema::create('params',function (Blueprint $table){
            $table ->increments('pa_id');
            $table ->decimal('pa_dollar', 5,2)->nullable();
            $table ->decimal('pa_eu', 5,2)->nullable();
            $table ->string('pa_bili')->nullable();
            $table->timestamps();
        });
        //用户表
        Schema::create('user',function (Blueprint $table){
            $table ->increments('id');
            $table ->string('user_name');
            $table ->string('user_pass');
            $table->timestamps();
        });

        //设备表
        Schema::create('pros',function (Blueprint $table){
            $table ->increments('pros_id');
            $table ->string('pros_sys')->nullable();   //分系统名
            $table ->string('pros_name')->nullable();   //设备名称
            $table ->string('pros_brand')->nullable();  //品牌
            $table ->string('pros_detail')->nullable(); //设备型号
            $table ->text('pros_less')->nullable();     //简单描述
            $table ->text('pros_more')->nullable();     //详细描述
            $table ->integer('pros_number')->nullable();//数量
            $table ->string('pros_unit')->nullable();   //单位
            $table ->string('pros_area')->nullable();   //产地
            $table ->string('pros_vol')->nullable();    //体积
            $table ->integer('pros_u')->nullable();     //U
            $table ->float('pros_kg',5,2)->nullable();  //重量
            $table ->float('pros_w',5,2)->nullable();   //功耗
            $table ->integer('pros_inprice')->nullable();//成本单价
            $table ->integer('pros_outprice')->nullable();//出厂价格
            $table ->decimal('pros_display_inprice',10)->nullable();//换算后的结果  成本单价
            $table ->decimal('pros_display_outprice',10)->nullable();//换锁后的结果  出厂价格
            $table ->text('pros_remark')->nullable();     //备注
            $table ->string('pros_thumb')->nullable();   //缩略图,后期修改为必须填写
            $table ->string('pros_img')->nullable();   //大图
            $table ->string('pros_flag_money')->default(1); //币种 1美元 2欧元 3人民币
            $table ->string('pros_flag_bili')->default(1); //比例 1无比例 2有比例
            $table ->string('pros_img_other')->nullable(); //其他图片，可为空
            $table ->string('pros_img_other_name')->nullable(); //其他图片的名称 一样采用explode implode和.map()
            $table ->timestamps();
        });

        //todo 系统表，不是数据库的系统，用于制作方案使用
        Schema::create('system',function (Blueprint $table){
            $table->increments('id');
            $table->string('name',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('params');
        Schema::drop('user');
        Schema::drop('pros');
    }
}
