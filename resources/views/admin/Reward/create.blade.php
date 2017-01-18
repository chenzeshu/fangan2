@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/reward')}}">首页</a> &raquo; <a href="#">人员奖惩管理</a>
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
                <a href="{{url('admin/reward/create')}}"><i class="fa fa-plus"></i>添加奖惩信息</a>
                <a href="{{url('admin/reward')}}"><i class="fa fa-recycle"></i>全部奖惩信息</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->
    
    <div class="result_wrap">
        <form action="{{url('admin/reward/')}}" method="post">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                        <tr>
                            <th>姓名：</th>
                            <td>
                                <input type="text" class="md" name="re_name" value="">
                            </td>
                        </tr>
                        <tr>
                            <th><i class="require"></i>奖惩：</th>
                            <td>
                                <select name="re_reward" id="">
                                    <option value="1">奖励</option>
                                    <option value="2">惩罚</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="require"></i>绩效浮动（<b>最多保留2位小数</b>）：</th>
                            <td>
                                <input type="text" name="re_float">
                            </td>
                        </tr>
                        <script src="{{asset('/resources/org/laydate/laydate.js')}}" type="text/javascript" charset="utf-8"></script>
                        <tr>
                            <th><i class="require"></i>生效日期：</th>
                            <td>
                                <input class="laydate-icon" name="re_date_start" onclick="laydate()"  placeholder="选择日期">
                            </td>
                        </tr>

                        <tr>
                            <th><i class="require"></i>结束日期（初定不填）：</th>
                            <td>
                                <input class="laydate-icon" name="re_date_end" onclick="laydate()"  placeholder="选择日期">
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