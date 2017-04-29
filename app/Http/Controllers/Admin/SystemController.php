<?php

namespace App\Http\Controllers\Admin;

use App\Model\System;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SystemController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = System::paginate(10);
        return view('admin.systemData.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.systemData.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $if = System::where('name',$request->name)->first();

        if(!$if){
            $re = System::create([
                'name' => $request->name
            ]);
            if($re){
                return redirect('admin/system');
            }else{
                return back()->with('errors','系统添加失败,请重试！');
            }
        }else{
            return back()->with('errors','系统名已存在,请重试！');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = System::find($id);
        return view('admin.systemData.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $if = System::where('name',$request->name)->first();
        if(!$if){
            $re = System::where('id',$id)->update([
                'name'=>$request->name
            ]);
            if($re){
                return redirect('admin/system');
            }else{
                return back()->with('errors','系统修改失败,请重试！');
            }
        }else{
            return back()->with('errors','系统名已存在！');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $re = System::where('id', $id)->delete();
        if($re){
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
}
