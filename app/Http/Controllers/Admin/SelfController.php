<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class SelfController extends CommonController
{
    //个人中心
    public function index(){
        $username = session('username');
        $num = Schema::hasTable('space_'.$username);
        if ($num){
            $data = DB::table('space_'.$username)->orderBy('self_id','asc')->paginate(10);
            $links = $data->links();
            return view('admin/self/index',compact('data'));
        }else{
            return view('admin/self/createspace');
        }

    }
    //创建个人空间
    /*
     * 空间名:zw_fa_space_$username
     * self_id 方案表的id,用于在create里做方案小表的表名'zw_fa_space_'.$username.'_'.$id
     * slef_name 方案表的自定义名称
     */
    public function createSpace()
    {
        $username = session('username');
        Schema::create('space_'.$username,function (Blueprint $table){
            $table->increments('self_id');
            $table->string('self_name')->unique();
            $table->string('created_at',50);
            $table->string('updated_at',50)->nullable();
        });
        return view('admin/self/index');
    }
    //get  admin/self/create  造小表

    public function create()
    {
        return view('admin.self.create');
    }

    /*
     * 同时造小表
     *   小表名称:zw_fa_space_$username_$表id(即self_id)
     */
    public function store()
    {
        $username = session('username');

        $input = Input::except('_token');
        $input['created_at']=time();
        $rules = [
            'self_name'=>'required',
        ];
        $message = [
            'self_name.required' =>'[方案名称]必须填写',
        ];
        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            $re = DB::table('space_'.$username)->insert($input);
            if($re){
            //todo 大表中已存在小表ID后，开始建造小表
                $id = DB::table('space_'.$username)->select('self_id')->orderBy('self_id','desc')->first();  //todo 拿最新建造的小表ID
                $id=$id->self_id;
                //todo 建小表
                Schema::create('space_'.$username.'_'.$id,function (Blueprint $table){
                    $table ->increments('id');
                    //分系统名 todo 与数据库用的不是同一套系统名，一共8个，采用int方便排序
                    //todo 第二，设备不需要有系统名，只需要知道自己是哪个系统的儿子就行了
                    $table ->integer('sys')->default(0)->nullable();
                    $table ->string('name')->nullable();   //设备名称
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
                //todo 建小表的顺序表
                Schema::create('space_'.$username.'_'.$id.'_order',function (Blueprint $table){
                   $table->increments('id');
                   $table->string('order',777)->nullable();
                });
            //todo 返回
                return redirect('admin/self');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    //get  admin/self/{self}/edit  编辑分类 {self}是传参的参数值
    public function edit($self_id)
    {
        $username = session('username');
        $field = DB::table('space_'.$username)->where('self_id',$self_id)->first();
        return view('admin/self/edit',compact('field'));
    }
    //put|patch admin/self/{self}  更新分类 {self}是传参的参数值
    public function update($self_id)
    {
        $username = session('username');
        $input = Input::except('_token','_method');
        $input['updated_at']=time();

        $re = DB::table('space_'.$username)->where('self_id',$self_id)->update($input);
        if($re){
            return redirect('admin/self');
        }else{
            return back()->with('errors','商品修改失败,请重试！');
        }
    }

    //delelte admin/self/{self}  删除分类
    public function destroy($self_id)
    {
        $username = session('username');
        $re = DB::table('space_'.$username)->where('self_id',$self_id)->delete();
        //todo 同时要删除对应的小表及order表
        Schema::drop('space_'.$username.'_'.$self_id);
        Schema::drop('space_'.$username.'_'.$self_id.'_order');

        $re2 = Schema::hasTable('space_'.$username.'_'.$self_id);

        if($re&&!$re2){
            $data = [
                'status'=> 0,   //因为是ajax异步返回，所以返回一个json数据
                'msg' => '删除成功',
            ];
        }else{
            $data = [
                'status'=> 1,   //因为是ajax异步返回，所以返回一个json数据
                'msg' => '删除失败，请稍后重试',
            ];
        }
        return $data;
    }

    //get  admin/self/{self}  显示单个分类信息
    public function show()
    {

    }

    public function search()
    {
        $input = Input::all();
        $name = $input['name'];
        $info = self::where("self_name","like","%".$name."%")->orderBy("self_name")->get();   //orderBy模式asc
        return $info;
    }
}
