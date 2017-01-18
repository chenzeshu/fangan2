@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/jx')}}">首页</a> &raquo; <a href="#">上月绩效管理</a>
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
            <h3>编辑上月绩效</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/jx/create')}}"><i class="fa fa-plus"></i>添加上月绩效</a>
                <a href="{{url('admin/jx')}}"><i class="fa fa-recycle"></i>全部上月绩效</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="{{url('admin/jx/'.$field->jx_id)}}" method="post">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th>姓名：</th>
                    <td>
                        <input type="text" class="xs" name="jx_name" value="{{$field->jx_name}}">
                    </td>
                </tr>
                <tr>
                    <th><i class="require"></i>上月实发绩效（元）：</th>
                    <td>
                        <input type="text" class="md" name="jx_real" value="{{$field->jx_real}}">
                    </td>
                </tr>
                <tr>
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