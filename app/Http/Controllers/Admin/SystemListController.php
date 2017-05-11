<?php

namespace App\Http\Controllers\Admin;

use App\Model\SystemList;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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
        $path = null;
        //文件存储
        if($request->hasFile('file')){
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $path = $file->move(storage_path('app\public\system'));
        }

        if(!$if){
            $re = SystemList::create([
                'name' => $request->name,
                'path' => $path,
                'ext' =>$ext
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
        $if = SystemList::findOrFail($id);
        $path = $if->path;
        $ext = $if->ext;
        //文件存储
        if($request->hasFile('file')){
            if(!empty($path)){
                $_path = explode('\\', $path);
                $_path = $_path[6].'\\'.$_path[7].'\\'.$_path[8];
                Storage::delete($_path);
            }
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $path = $file->move(storage_path('app\public\system'));
        }

        $re = SystemList::where('id',$id)->update([
            'name'=>$request->name,
            'path'=>$path,
            'ext'=>$ext
        ]);

        if($re){
            return redirect('admin/systemList');
        }else{
            return back()->with('errors','系统修改失败,请重试！');
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
        $file = SystemList::findOrFail($id);
        $path = $file->path;
        if(!empty($path)){
            $_path = explode('\\', $path);
            $_path = $_path[6].'\\'.$_path[7].'\\'.$_path[8];
            Storage::delete($_path);
        }

        $re = $file->delete();
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

    public function download($id)
    {
        $file = SystemList::findOrFail($id);
        $path = $file->path;
        if(empty($path)){
            return Redirect::back()->withErrors('本系统尚未上传文件');
        }
        else{
            $_path = explode('\\', $path);
            $_path = $_path[5].'\\'.$_path[6].'\\'.$_path[7].'\\'.$_path[8];
            return response()->download(storage_path($_path), $file->name.".".$file->ext);
        }

    }
}
