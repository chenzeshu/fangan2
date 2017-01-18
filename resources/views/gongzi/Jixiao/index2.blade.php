@extends('layouts.admin')
@section('content')
    <link href="{{asset('/resources/views/admin/style/js/login/css/mui.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/resources/views/admin/style/js/login/css/style.css')}}" rel="stylesheet" />
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/mx')}}">首页</a> &raquo; <a href="#">绩效奖金表</a>
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
                <h3>绩效奖金表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('excel/export_jiangjin')}}"><i class="fa fa-plus"></i>导出EXCEL</a>
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
                        <th>中行账号</th>
                        <th>金额（元）</th>
                    </tr>

                    @if(isset($data))
                        @foreach($data as $a =>$b)
                    <tr>
                        <td class="tc">{{$b['jx2_id']}}</td>
                        <td>{{$b['jx2_name']}}</td>
                        <td>{{$b['e_zhong']}}</td>
                        <td>{{$b['jx2_shi']}}</td>
                    </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td class="tc"></td>
                        <td>合计</td>
                        <td></td>
                        <td>{{$info}}</td>
                    </tr>
                </table>
                @if(isset($data))
                <div class="page_list">
                    {{$data->links()}}
                </div>
                @endif
            </div>
        </div>
    <!--搜索结果页面 列表 结束-->

    <style>
        .result_content ul li span{
            padding:6px 12px;
        }
        .mui-switch:before {
            top: -4px;
        }
    </style>
    <script src="/resources/views/admin/style/js/login/js/mui.min.js"></script>
    <script src="/resources/views/admin/style/js/login/js/mui.enterfocus.js"></script>
    <script src="/resources/views/admin/style/js/login/js/app.js"></script>
    <script>
        function searchName(){
            var val = $('#search').val();
            $.post("{{url('admin/mx/search')}}",{name:val,'_token':'{{csrf_token()}}'},function(data){
                $('tr:gt(0)').remove();
                    $(data).each(function(k,v){
                        var url1 ="jx/"+v.mx_id+"/edit";
                        var url2 = 'javascript:onclick=deleteObj('+v.mx_id+')';
                        $('tr:first').after("<tr><td class='tc'>"+v.mx_id+"</td>" +
                                "<td>"+v.mx_name+"</td>" +
                                "<td>"+v.mx_real+"</td>" +
                                "<td><a href='"+url1+"'>修改</a><a href="+url2+">删除</a></td></tr>");
                    })
            });
        }


    </script>
@endsection
