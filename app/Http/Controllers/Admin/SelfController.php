<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\SelfRepository;
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
    protected $repo;

    function __construct(SelfRepository $repo)
    {
        $this->repo = $repo;
    }

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
        $this->repo->createSelfSpace();

        return view('admin/self/index');
    }
    //get  admin/self/create  造小表

    public function create()
    {
        return view('admin.self.create');
    }

    /**
     *   造方案表,同时存储方案表
     *
     *   方案表名称:zw_fa_space_$username_$表id(即self_id)
     */
    public function store(Requests\SelfRequest $request)
    {
        $username = session('username');

        $request = $request->except('_token');
        $request->created_at = time();

        $re = DB::table('space_'.$username)->insert($request);

        if($re){
        //todo 大表中已存在小表ID后，开始建造小表
            $id = DB::table('space_'.$username)->select('self_id')->orderBy('self_id','desc')->first();  //todo 拿最新建造的小表ID
            $id = $id->self_id;
            //todo 建小表
            $this->repo->createSelfTable($id, $username);
            //todo 建小表的顺序表
            $this->repo->createSelfOrderTable($id, $username);
        //todo 返回
            return redirect('admin/self');
        }else{
            return back()->with('errors','数据填充失败，请稍后重试！');
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
        $input['updated_at'] = time();

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
