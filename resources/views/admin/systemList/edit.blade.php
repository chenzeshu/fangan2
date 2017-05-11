@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/systemListList')}}">首页</a> &raquo; <a href="#">系统（数据库）列表管理</a>
    </div>
    <!--面包屑导航 结束-->
    <div class="result_wrap">
        <div class="result_title">
            <div class="mark">
                @if(is_object($errors))
                    @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                            <p>{{$error}}</p>
                        @endforeach
                    @endif
                @else
                    <p>{{$errors}}</p>
                @endif
            </div>
        </div>
    </div>
	<!--结果集标题与导航组件 开始-->
	<div class="result_wrap">
        <div class="result_title">
            <h3>编辑系统（数据库）</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/systemList/create')}}"><i class="fa fa-plus"></i>添加系统（数据库）</a>
                <a href="{{url('admin/systemList')}}"><i class="fa fa-recycle"></i>全部系统（数据库）</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="{{url('admin/systemList/'.$field->id)}}" method="post" enctype="multipart/form-data>
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th class="tc">系统名称</th>
                    <td><input type="text" class="md" name="name" value="{{$field->name}}"></td>
                </tr>
                <tr>
                    <th class="tc">系统文件（小于10MB）</th>
                    <td>
                        @if(empty($field->path))
                            <p>修改前未上传文件</p>
                            @else
                            <strong>本系统之前已上传文件，是否要进行修改？</strong><br>
                            @endif
                        <input type="file" name="file">
                        <div id="warning"></div>
                    </td>
                </tr>
                    <th></th>
                    <td>
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>

@endsection