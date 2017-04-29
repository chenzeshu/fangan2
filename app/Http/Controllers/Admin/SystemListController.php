<?php

namespace App\Http\Controllers\Admin;

use App\Model\SystemList;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SystemListController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SystemList::paginate(10);
        return view('admin.systemList.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.systemList.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $if = SystemList::where('name',$request->name)->first();

        if(!$if){
            $re = SystemList::create([
                'name' => $request->name
            ]);
            if($re){
                return redirect('admin/systemList');
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
        $field = SystemList::find($id);
        return view('admin.systemList.edit', compact('field'));
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
        $if = SystemList::where('name',$request->name)->first();
        if(!$if){
            $re = SystemList::where('id',$id)->update([
                'name'=>$request->name
            ]);
            if($re){
                return redirect('admin/systemList');
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
        $re = SystemList::where('id', $id)->delete();
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
