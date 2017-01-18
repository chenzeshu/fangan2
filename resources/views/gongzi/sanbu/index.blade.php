@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/mx')}}">首页</a> &raquo; <a href="#">三项补贴表</a>
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

    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h3>三项补贴表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('excel/export_sanbu')}}"><i class="fa fa-plus"></i>导出EXCEL</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">序号</th>
                        <th>姓名</th>
                        <th>招行卡号</th>
                        <th>三补额度</th>
                        <th>未交发票</th>
                        <th>本月实发数</th>
                    </tr>
                    @if(isset($array))
                        @foreach($array as $k=>$v)
                        <tr>
                            <td class="tc">{{$v['id']}}</td>
                            <td class="tc">{{$v['name']}}</td>
                            <td>{{$v['zhao']}}</td>
                            <td>{{$v['limit']}}</td>
                            <td>{{$v['if']}}</td>
                            <td>{{$v['shi']}}</td>
                        </tr>
                        @endforeach
                    @endif
                    @if(isset($sum))
                        <tr>
                            <td></td>
                            <td class="tc">合计</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{$sum}}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    <!--搜索结果页面 列表 结束-->

    <style>
        .result_content ul li span{
            padding:6px 12px;
        }
    </style>
@endsection
