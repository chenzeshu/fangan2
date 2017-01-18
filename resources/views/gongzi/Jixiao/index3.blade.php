@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/mx')}}">首页</a> &raquo; <a href="#">绩效工资汇总一</a>
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
                <h3>绩效工资汇总一</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('excel/export_jxhz1')}}"><i class="fa fa-plus"></i>导出EXCEL</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">部门</th>
                        <th>人数</th>
                        <th>绩效工资基数</th>
                        <th>实发数</th>
                    </tr>
                    @if(isset($array_1))
                    <tr>
                        <td class="tc">{{$array_1->bumen}}</td>
                        {{--<td class="tc">@if($array_1->job_bumen2==1) 管理总部 @elseif($array_1->job_bumen2==2) 业务总部--}}
                            {{--@elseif($array_1->job_bumen2==3) 云卫通 @else 科技部 @endif</td>--}}
                        <td>{{$array_1->sum}}</td>
                        <td>{{$array_1->base}}</td>
                        <td>{{$array_1->shi}}</td>
                    </tr>
                    @endif
                    @if(isset($array_2))
                        <tr>
                            <td class="tc">{{$array_2->bumen}}</td>
                            <td>{{$array_2->sum}}</td>
                            <td>{{$array_2->base}}</td>
                            <td>{{$array_2->shi}}</td>
                        </tr>
                    @endif
                    @if(isset($array_3))
                        <tr>
                            <td class="tc">{{$array_3->bumen}}</td>
                            <td>{{$array_3->sum}}</td>
                            <td>{{$array_3->base}}</td>
                            <td>{{$array_3->shi}}</td>
                        </tr>
                    @endif
                    @if(isset($array_heji))
                        <tr>
                            <td class="tc">合计</td>
                            <td>{{$array_heji->sum}}</td>
                            <td>{{$array_heji->base}}</td>
                            <td>{{$array_heji->shi}}</td>
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
