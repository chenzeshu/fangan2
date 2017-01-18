@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/params')}}">首页</a> &raquo; <a href="#">人员奖惩管理</a>
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
            <h3>添加人员基数</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/params/create')}}"><i class="fa fa-plus"></i>添加参数信息</a>
                <a href="{{url('admin/params')}}"><i class="fa fa-recycle"></i>全部参数信息</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->
    
    <div class="result_wrap">
        <form action="{{url('admin/params/')}}" method="post">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                    <tr>
                        <th class="tc">美元汇率</th>
                        <td>
                            <input type="text" class="md" name="pa_dollar">
                        </td>
                    </tr>
                    <tr>
                        <th>欧元汇率</th>
                        <td>
                            <input type="text" class="md" name="pa_eu">
                        </td>

                    </tr>
                    <tr>
                        <th>报价比例</th>
                        <td>
                            <input type="text" class="md" name="pa_bili">
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