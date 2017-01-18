@extends('layouts.admin')
@section('content')
	<?php use App\Model\Role; ?>
	<!--头部 开始-->
	<div class="top_box">
		<div class="top_left">
			<div class="logo">方案自动化平台</div>
			<ul>
				<li><a href="{{url('admin/index')}}">首页--数据表</a></li>
				<li><a href="#" class="active">二页--个人空间</a></li>
			</ul>
		</div>
		<div class="top_right">
			<ul>
				<li>你好：{{\Illuminate\Support\Facades\Session::get('username')}}</li>
				@role('owner')
				<li><a href="{{url('admin/register')}}" target="main">添加用户</a></li>
				@endrole
				<li><a href="{{url('admin/pass')}}" target="main">修改密码</a></li>
				<li><a href="{{url('admin/logout')}}">退出</a></li>
			</ul>
		</div>
	</div>
	<!--头部 结束-->

	<!--左侧导航 开始-->
	<div class="menu_box">
		<ul>
			<li>
				<h3><i class="fa fa-fw fa-clipboard"></i>个人空间</h3>
				<ul class="sub_menu">
					<li><a href="{{url('admin/self')}}" target="main"><i class="fa fa-fw fa-plus-square"></i>个人空间列表</a></li>
					<li><a href="{{url('admin/self/create')}}" target="main"><i class="fa fa-fw fa-plus-square"></i>添加方案</a></li>
				</ul>
			</li>
        </ul>
	</div>
	<!--左侧导航 结束-->

	<!--主体部分 开始-->
	<div class="main_box">
		<iframe src="{{url('admin/self')}}" frameborder="0" width="100%" height="100%" name="main"></iframe>
	</div>
	<!--主体部分 结束-->

	<!--底部 开始-->
	<div class="bottom_box">
		CopyRight © 2016. Powered By <a href="http://www.jianshu.com/users/adc147f1ec89/latest_articles" target="_blank">http://www.chenzeshu.com</a>.
	</div>
	<!--底部 结束-->
@endsection
