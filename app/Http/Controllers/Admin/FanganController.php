<?php

namespace App\Http\Controllers\Admin;

use App\Model\Params;
use App\Model\Pros;
use App\Model\System;
use App\Model\SystemList;
use App\Repositories\FanganRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;

class FanganController extends CommonController
{
    /**
     * 常用:session
     *    table   string    包括了table_id
     *    table_id   int    是个人第几张表
     *
     *  与系统名的关联键为"id"，未做ORM
     */

    protected $repo;

    function __construct(FanganRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index($self_id)
    {
        $username = $this->repo->sessionDeal($self_id);
        $data = $this->repo->judgeOrder();

        //DB类没有find()
        $table = DB::table('space_'.$username)->where('self_id',$self_id)->first();
        $table_name = $table->self_name;
        return view('admin/fangan/index',compact('data','table_name'));
    }

    //添加分系统页面||逻辑
    public function create()
    {
       if ($input = Input::except('_token')){
           $again = DB::table(session('table'))->where('sys',$input['sys'])->first();
           if ($again){
               //todo 若分系统不可添加
               return back()->with('本系统已经存在');
           }
           //todo 若分系统可以添加
           //todo 就将系统名与系统ID添加到个人table里
           $system = System::where('id',$input['sys'])->first();
           $input['name'] = $system['name'];
           DB::table(session('table'))->insert($input);
           return redirect(url('admin/fangan/index').'/'.session('table_id'));
       }else{
           $systemData = System::all();
           return view('admin/fangan/create',compact('systemData'));
       }

    }

    /*
     * 检查提交分系统时，方案是否已存在本系统。
     * 配合前端AJAX使用
     */
    public function alreadyExist($sys)
    {
        $again = DB::table(session('table'))->where('sys',$sys)->first();
        if ($again){
            $data =[
              'status' =>1,
              'text'=>"本系统已经存在"
            ];
        }else{
            $data = [
                'status' =>2,
                'text'=>"本系统可以选择"
            ];
        }
        return $data;
    }

    /**
     * 更改表的设备排列顺序
     * @return mixed
     */
    public function reOrder()
    {
        $input = Input::except('_token');
//        $order = explode(',',$input['order']); //成为数组
        $arr = [
          'id' =>1,
          'order' =>$input['order']
        ];
        $num = DB::table(session('table')."_order")->count();
        if (!$num){
            //todo 如果是新表，就插入新的order数据
            $re = DB::table(session('table')."_order")->insert($arr);
        }else{
            //todo 如果表已有数据，就更新order
            $re = DB::table(session('table')."_order")->where('id',1)->update($arr);
        }
        return $re;

    }

    /**
     * 更改设备数量
     * @return array
     */
    public function reNumber()
    {
        $input = Input::except('_token');
        $re = DB::table(session('table'))->where('id',$input['id'])->update(['number'=>$input['number']]);
        if ($re){
            $data = [
                "status"=>0,
                "msg"=>"修改成功"
            ];
        }else{
            $data = [
                "status"=>1,
                "msg"=>"修改失败"
            ];
        }
        return $data;
    }
    //todo 删除系统，则系统下设备全部删除
    public function delete($id)
    {
        $whether = DB::table(session('table'))->where('id',$id)->first();
        if ($sys = $whether->sys){
            //删除系统则删除系统+系统下所有设备
                 DB::table(session('table'))->where('id',$id)->delete();
            $re = DB::table(session('table'))->where('father',$sys)->delete();
        }else{
            $re = DB::table(session('table'))->where('id',$id)->delete();
        }
        if ($re){
            $data = [
                "status"=>0,
                "msg"=>"删除成功"
            ];
        }else{
            $data = [
                "status"=>1,
                "msg"=>"删除失败"
            ];
        }
        return $data;
    }
    //todo 传来了系统编号，即为设备father
    //todo edit用于展示“系统名”全为$sys的设备列表
    public function edit($sys_id)
    {
        $sys = System::find($sys_id);
        $systemList = SystemList::all();
        $sys_name = $sys['name'];
        //限制40条
        $pros = Pros::take(40)->get();
        //todo 拿到display_price
        $pros = $this->repo->getDisplayPrice($pros);

        $numrows = Pros::count();
        return view('admin/fangan/pros',compact('pros','sys_name','numrows','sys_id','systemList'));
    }

    //todo 搜索设备
    public function searchPros()
    {
        $input = Input::except('_token');
        $sys = $input['sys'];
        $name =$input['name'];
        $detail =$input['detail'];

        $pros = Pros::where('pros_sys','like','%'.$sys.'%')
                ->where('pros_name','like','%'.$name.'%')
                ->where('pros_detail','like','%'.$detail.'%')
                ->get();
        //todo 拿到display_price
        $pros = $this->repo->getDisplayPrice($pros);

        if ($pros){
            $data =[
                'status' => 0,
                'msg'    => $pros
            ];
        }
        return $data;
    }

    //todo 添加设备
    //接收选择的设备ID并将设备添加到私人空间的方案表中
    public function checkId()
    {
       $input = Input::except('_token');
        $sys_name = $input['sys_name'];
        $sys_id = $input['sys_id'];
        $ids = explode(',',$input['ids']);

        //2017.615
        $pros = Pros::find($ids);
        DB::transaction(function ()use($pros, $ids, $sys_id){
            foreach ($pros as $k => $pro) {
                $array = [
                    'goodsid'=>$pro['pros_goodsid'], 'name'=>$pro['pros_name'],
                    'brand'=>$pro['pros_brand'], 'detail'=>$pro['pros_detail'],
                    'less'=>$pro['pros_less'], 'more'=>$pro['pros_more'],
                    'number'=>$pro['pros_number'], 'unit'=>$pro['pros_unit'],
                    'area'=>$pro['pros_area'], 'vol'=>$pro['pros_vol'],
                    'u'=>$pro['pros_u'], 'kg'=>$pro['pros_kg'], 'w'=>$pro['pros_w'],
                    'display_inprice'=>$pro['pros_display_inprice'],
                    'display_outprice'=>$pro['pros_display_outprice'], 'remark'=>$pro['pros_remark'],
                    'thumb'=>$pro['pros_thumb'], 'img'=>$pro['pros_img'],
                    'flag_money'=>$pro['pros_flag_money'], 'flag_bili'=>$pro['pros_flag_bili'],
                    'father'=>$sys_id, 'son'=>999, 'sysid'=>$ids[$k], //sysid指设备在pros表中的id
                    'img'=>$pro['pros_img'],
                    'img_other'=>$pro['pros_img_other'],
                    'img_other_name'=>$pro['pros_img_other_name']
                ];
                DB::table(session('table'))->insert($array);
            }

        });
        return 'ok';
    }
}
