@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/mx')}}">首页</a> &raquo; <a href="#">工资明细表</a>
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
                <h3>工资明细表列表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('gongzi/god_mingxi')}}"><i class="fa fa-recycle"></i>更新工资明细表</a>
                    <a href="{{url('excel/export_mingxi')}}"><i class="fa fa-plus"></i>导出EXCEL表单</a>
                    <input type="text" name="mx_name" class="xs" placeholder="填写查找姓名" id="search">
                    <input type="button" value="查找" onclick="searchName()">

                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th>姓名</th>
                        <th>岗位工资</th>
                        <th>工龄补贴</th>
                        <th>补扣工资</th>
                        <th>加班费</th>
                        <th>临时项</th>
                        <th>应发数</th>
                        <th>工会会费</th>
                        <th>公积金</th>
                        <th>养老金</th>
                        <th>失业保险</th>
                        <th>医疗保险</th>
                        <th>补交统筹</th>
                        <th>补公积</th>
                        <th>个人所得税</th>
                        <th>房租</th>
                        <th>实发数</th>
                    </tr>
                    @foreach($data as $v)
                    <tr>
                        <td >{{$v->mx_id}}</td>
                        <td>{{$v->mx_name}}</td>
                        <td>{{$v->mx_salary}}</td>
                        <td >{{$v->mx_bu}}</td>
                        <td>@if($v->mx_kou==0)@else -@endif{{$v->mx_kou}}</td>
                        <td>{{$v->mx_jia}}</td>
                        <td ><input name="lin" type="text" value="{{$v->mx_lin}}" onclick="changeLin(this,mx_id='{{$v->mx_id}}')"></td>
                        <td>{{$v->mx_ying}}</td>
                        <td>{{$v->mx_gh}}</td>
                        <td >{{$v->mx_gong}}</td>
                        <td>{{$v->mx_yang}}</td>
                        <td>{{$v->mx_shi}}</td>
                        <td >{{$v->mx_yi}}</td>
                        <td>{{$v->mx_bujiao}}</td>
                        <td>{{$v->mx_bugong}}</td>
                        <td >{{$v->mx_shui}}</td>
                        <td>{{$v->mx_fang}}</td>
                        <td>{{$v->mx_shifa}}</td>
                    </tr>
                    @endforeach
                </table>
                <div class="page_list">
                   {{$data->links()}}
                </div>
            </div>
        </div>
    <!--搜索结果页面 列表 结束-->

    <style>
        .result_content ul li span{
            padding:6px 12px;
        }
    </style>
    <script>
        $(function(){
            $('input[name=lin]').css('background-color','#C9C9C9');
        })
        //删除分类
        function deleteObj(mx_id){
            layer.confirm('确定删除这位仁兄？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/mx/')}}/"+mx_id,{'_method':'delete','_token':"{{csrf_token()}}"},function(data){
                    if(data.status=='0'){
                        layer.msg(data.msg, {icon: 6});
                        setTimeout(function(){
                            location.href = location.href;
                        },900)
                    }else if(data.status=='1'){
                        layer.msg(data.msg, {icon: 5});
                        setTimeout(function(){
                            location.href = location.href;
                        },900)
                    }else{
                        layer.msg(data.msg, {icon: 5});
                        setTimeout(function(){
                            location.href = location.href;
                        },900)
                    }
                });
            });
        }

        function searchName(){
            var val = $('#search').val();
            $.post("{{url('gongzi/mx/search')}}",{name:val,'_token':'{{csrf_token()}}'},function(data){
                $('tr:gt(0)').remove();
                    $(data).each(function(k,v){
                        var url1 ="jx/"+v.mx_id+"/edit";
                        var url2 = 'javascript:onclick=deleteObj('+v.mx_id+')';
                        $('tr:first').after("<tr><td class='tc'>"+v.mx_id+"</td>" +
                                "<td>"+v.mx_name+"</td>" +
                                "<td>"+v.mx_salary+"</td>" +
                                "<td>"+v.mx_bu+"</td>" +
                                "<td>"+v.mx_kou+"</td>" +
                                "<td>"+v.mx_jia+"</td>" +
                                "<td>"+v.mx_lin+"</td>" +
                                "<td>"+v.mx_ying+"</td>" +
                                "<td>"+v.mx_gh+"</td>" +
                                "<td>"+v.mx_gong+"</td>" +
                                "<td>"+v.mx_yang+"</td>" +
                                "<td>"+v.mx_shi+"</td>" +
                                "<td>"+v.mx_yi+"</td>" +
                                "<td>"+v.mx_bujiao+"</td>" +
                                "<td>"+v.mx_bugong+"</td>" +
                                "<td>"+v.mx_shui+"</td>" +
                                "<td>"+v.mx_fang+"</td>" +
                                "<td>"+v.mx_shifa+"</td>");
                    })
            });
        }

        function changeLin(obj,mx_id){
            var $flag = $(obj).prop("readonly",false);
            $(obj).attr({"readonly":false}).css('background-color','');
            url = "{{url('gongzi/lin_mingxi')}}";
            obj.onkeydown = function(){
                var e = event || window.event ||arguments.callee.caller.arguments[0];
                var value = $(obj).val();
                var value2 = mx_id;
                if(e && e.keyCode ==13){
                    $.post(url,{"mx_lin":value,"mx_id":value2,'_token':'{{csrf_token()}}'},function(data){
                        $(obj).prop('readonly','true').css('background-color','#C9C9C9');
                        if(data.status=='0'){
                            layer.msg(data.msg, {icon: 6});
                            setTimeout(function(){
                                location.href = location.href;
                            },200)
                        }else{
                            layer.msg(data.msg, {icon: 5});
                            setTimeout(function(){
                                location.href = location.href;
                            },200)
                        }
                    })
                }
            }

            obj.onblur = function(){
                var value = $(obj).val();
                var value2 =mx_id;
                var url = "{{url('gongzi/lin_mingxi')}}";
                $.post(url,{"mx_lin":value,"mx_id":value2,'_token':'{{csrf_token()}}'},function(data){
                    $(obj).prop('readonly','true').css('background-color','#C9C9C9');
                    if(data.status=='0'){
                        layer.msg(data.msg, {icon: 6});
                        setTimeout(function(){
                            location.href = location.href;
                        },200)
                    }else{
                        layer.msg(data.msg, {icon: 5});
                        setTimeout(function(){
                            location.href = location.href;
                        },200)
                    }
                })
            }


        }

    </script>
@endsection
