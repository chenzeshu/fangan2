@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
<div class="crumb_warp">
    <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; 添加用户
</div>
<!--面包屑导航 结束-->

<!--结果集标题与导航组件 开始-->
<div class="result_wrap">
    <div class="result_title">
        <h3>添加用户</h3>
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
<!--结果集标题与导航组件 结束-->

<div class="result_wrap">
    <form method="post" action="{{url('admin/register')}}">
        {{csrf_field()}}
        <table class="add_tab">
            <tbody>
            <tr>
                <th width="120"><i class="require">*</i>用户名：</th>
                <td>
                    <input type="text" name="user_name"> </i>请输入用户名</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>密码：</th>
                <td>
                    <input type="password" name="password"> </i>新密码6-20位</span>
                </td>
            </tr>
            <tr>
                <th><i class="require">*</i>确认密码：</th>
                <td>
                    <input type="password" name="password_confirmation"> </i>再次输入密码</span>
                </td>
            </tr>
            <tr>
                <th>用户类型</th>
                <td><input type="radio" value="owner" name="user_type">集成规划所成员　
                    <input type="radio" value="people" name="user_type">普通用户　</td>
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