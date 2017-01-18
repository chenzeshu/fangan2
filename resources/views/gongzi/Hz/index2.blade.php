@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/mx')}}">首页</a> &raquo; <a href="#">工资汇总表</a>
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
                <h3>工资汇总表列表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('excel/export_huizong1')}}"><i class="fa fa-plus"></i>导出部门一EXCEL表单</a>
                    <a href="{{url('excel/export_huizong2')}}"><i class="fa fa-plus"></i>导出部门二EXCEL表单</a>
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
                    @if(isset($array_1))
                    <tr>
                        <td class="tc">{{$array_1->bumen}}</td>
                        {{--<td class="tc">@if($array_1->job_bumen2==1) 管理总部 @elseif($array_1->job_bumen2==2) 业务总部--}}
                            {{--@elseif($array_1->job_bumen2==3) 云卫通 @else 科技部 @endif</td>--}}
                        <td>{{$array_1->sum}}</td>
                        <td>{{$array_1->salary}}</td>
                        <td>{{$array_1->bu}}</td>
                        <td>@if($array_1->kou==0)@else -@endif{{$array_1->kou}}</td>
                        <td>{{$array_1->jia}}</td>
                        <td>{{$array_1->lin}}</td>
                        <td>{{$array_1->ying}}</td>
                        <td>{{$array_1->gh}}</td>
                        <td>{{$array_1->gong}}</td>
                        <td>{{$array_1->yang}}</td>
                        <td>{{$array_1->shi}}</td>
                        <td>{{$array_1->yi}}</td>
                        <td>{{$array_1->bujiao}}</td>
                        <td>{{$array_1->bugong}}</td>
                        <td>{{$array_1->shui}}</td>
                        <td>{{$array_1->fang}}</td>
                        <td>{{$array_1->shifa}}</td>
                    </tr>
                    @endif
                    @if(isset($array_2))
                        <tr>
                            <td class="tc">{{$array_2->bumen}}</td>
                            <td>{{$array_2->sum}}</td>
                            <td>{{$array_2->salary}}</td>
                            <td>{{$array_2->bu}}</td>
                            <td>@if($array_2->kou==0)@else -@endif{{$array_2->kou}}</td>
                            <td>{{$array_2->jia}}</td>
                            <td>{{$array_2->lin}}</td>
                            <td>{{$array_2->ying}}</td>
                            <td>{{$array_2->gh}}</td>
                            <td>{{$array_2->gong}}</td>
                            <td>{{$array_2->yang}}</td>
                            <td>{{$array_2->shi}}</td>
                            <td>{{$array_2->yi}}</td>
                            <td>{{$array_2->bujiao}}</td>
                            <td>{{$array_2->bugong}}</td>
                            <td>{{$array_2->shui}}</td>
                            <td>{{$array_2->fang}}</td>
                            <td>{{$array_2->shifa}}</td>
                        </tr>
                    @endif
                    @if(isset($array_3))
                        <tr>
                            <td class="tc">{{$array_3->bumen}}</td>
                            <td>{{$array_3->sum}}</td>
                            <td>{{$array_3->salary}}</td>
                            <td>{{$array_3->bu}}</td>
                            <td>@if($array_3->kou==0)@else -@endif{{$array_3->kou}}</td>
                            <td>{{$array_3->jia}}</td>
                            <td>{{$array_3->lin}}</td>
                            <td>{{$array_3->ying}}</td>
                            <td>{{$array_3->gh}}</td>
                            <td>{{$array_3->gong}}</td>
                            <td>{{$array_3->yang}}</td>
                            <td>{{$array_3->shi}}</td>
                            <td>{{$array_3->yi}}</td>
                            <td>{{$array_3->bujiao}}</td>
                            <td>{{$array_3->bugong}}</td>
                            <td>{{$array_3->shui}}</td>
                            <td>{{$array_3->fang}}</td>
                            <td>{{$array_3->shifa}}</td>
                        </tr>
                    @endif
                    @if(isset($array_4))
                        <tr>
                            <td class="tc">{{$array_4->bumen}}</td>
                            <td>{{$array_4->sum}}</td>
                            <td>{{$array_4->salary}}</td>
                            <td>{{$array_4->bu}}</td>
                            <td>@if($array_4->kou==0)@else -@endif{{$array_4->kou}}</td>
                            <td>{{$array_4->jia}}</td>
                            <td>{{$array_4->lin}}</td>
                            <td>{{$array_4->ying}}</td>
                            <td>{{$array_4->gh}}</td>
                            <td>{{$array_4->gong}}</td>
                            <td>{{$array_4->yang}}</td>
                            <td>{{$array_4->shi}}</td>
                            <td>{{$array_4->yi}}</td>
                            <td>{{$array_4->bujiao}}</td>
                            <td>{{$array_4->bugong}}</td>
                            <td>{{$array_4->shui}}</td>
                            <td>{{$array_4->fang}}</td>
                            <td>{{$array_4->shifa}}</td>
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

        {{--function changeLin(obj,mx_id){--}}
            {{--var $flag = $(obj).prop("readonly",false);--}}
            {{--$(obj).attr({"readonly":false}).css('background-color','');--}}
            {{--url = "{{url('gongzi/lin_mingxi')}}";--}}
            {{--obj.onkeydown = function(){--}}
                {{--var e = event || window.event ||arguments.callee.caller.arguments[0];--}}
                {{--var value = $(obj).val();--}}
                {{--var value2 = mx_id;--}}
                {{--if(e && e.keyCode ==13){--}}
                    {{--$.post(url,{"mx_lin":value,"mx_id":value2,'_token':'{{csrf_token()}}'},function(data){--}}
                        {{--$(obj).prop('readonly','true').css('background-color','#C9C9C9');--}}
                        {{--if(data.status=='0'){--}}
                            {{--layer.msg(data.msg, {icon: 6});--}}
                            {{--setTimeout(function(){--}}
                                {{--location.href = location.href;--}}
                            {{--},200)--}}
                        {{--}else{--}}
                            {{--layer.msg(data.msg, {icon: 5});--}}
                            {{--setTimeout(function(){--}}
                                {{--location.href = location.href;--}}
                            {{--},200)--}}
                        {{--}--}}
                    {{--})--}}
                {{--}--}}
            {{--}--}}

            {{--obj.onblur = function(){--}}
                {{--var value = $(obj).val();--}}
                {{--var value2 =mx_id;--}}
                {{--var url = "{{url('gongzi/lin_mingxi')}}";--}}
                {{--$.post(url,{"mx_lin":value,"mx_id":value2,'_token':'{{csrf_token()}}'},function(data){--}}
                    {{--$(obj).prop('readonly','true').css('background-color','#C9C9C9');--}}
                    {{--if(data.status=='0'){--}}
                        {{--layer.msg(data.msg, {icon: 6});--}}
                        {{--setTimeout(function(){--}}
                            {{--location.href = location.href;--}}
                        {{--},200)--}}
                    {{--}else{--}}
                        {{--layer.msg(data.msg, {icon: 5});--}}
                        {{--setTimeout(function(){--}}
                            {{--location.href = location.href;--}}
                        {{--},200)--}}
                    {{--}--}}
                {{--})--}}
            {{--}--}}


        {{--}--}}

    </script>
@endsection
