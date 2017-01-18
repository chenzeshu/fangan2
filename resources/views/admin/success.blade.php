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
<p>注册成功</p>
        </div>
    </div>
</div>
<!--结果集标题与导航组件 结束-->

<div class="result_wrap">
    <form method="post" action="{{url('admin/register')}}">
        {{csrf_field()}}
        <table class="add_tab">
            <tbody>

            </tbody>
        </table>
    </form>
</div>
@endsection