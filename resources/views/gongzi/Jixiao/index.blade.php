@extends('layouts.admin')
@section('content')
    <link href="{{asset('/resources/views/admin/style/js/login/css/mui.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/resources/views/admin/style/js/login/css/style.css')}}" rel="stylesheet" />
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/mx')}}">首页</a> &raquo; <a href="#">绩效明细表</a>
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
                <h3>绩效明细表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('gongzi/god_jixiao')}}"><i class="fa fa-plus"></i>载入/更新绩效明细表</a>
                    <a href="{{url('excel/export_jixiao')}}"><i class="fa fa-plus"></i>导出EXCEL</a>
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
                        <th>绩效工资基数</th>
                        <th>系数</th>
                        <th>上调20%</th>
                        <th>有限期奖惩</th>
                        <th>当月奖惩</th>
                        <th>未交月度考核表</th>
                        <th>本月实发数</th>
                    </tr>

                    @if(isset($data))
                        @foreach($data as $a =>$b)
                    <tr>
                        <td class="tc">{{$b['jx2_id']}}</td>
                        <td>{{$b['jx2_name']}}</td>
                        <td>{{$b['jx2_base']}}</td>
                        <td>{{$b['jx2_xishu']}}</td>
                        {{--<td><input type="checkbox" value="1" @if($b['jx2_if']==1) checked @endif></td>--}}
                        <td><div id="autoLogin"
                                 @if($b['jx2_if']==1) class="mui-switch mui-active"
                                 @else  class="mui-switch"
                                @endif onclick="changeIf(this,jx2_id='{{$b['jx2_id']}}')">
                                <div class="mui-switch-handle"></div>
                            </div></td>
                        <td>{{$b['jx2_float']}}</td>
                        <td><input type="text" value="{{$b['jx2_random']}}" style="width: 50px;"></td>
                        <td>{{$b['jx2_yue']}}</td>
                        <td>{{$b['jx2_shi']}}</td>
                    </tr>
                        @endforeach
                    @endif
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
        $(function(){
            $('input[name=lin]').css('background-color','#C9C9C9');
        })
        function changeIf(obj,jx2_id){
            var index =$(obj).attr('class');
            if(index.indexOf('mui-active')>0){//此时按下
                $.post('{{url('gongzi/changeif_jixiao')}}',{jx2_if:1,jx2_id:jx2_id,'_token':'{{csrf_token()}}'},function(data){
                       if(data.status==0){
                            alert(data.msg);
                           setTimeout(function(){
                               location.href = location.href;
                           },500)
                       }
                       if(data.status==1){
                           alert(data.msg);
                           setTimeout(function(){
                               location.href = location.href;
                           },500)
                       }
                })
            }else{
                $.post('{{url('gongzi/changeif_jixiao')}}',{jx2_if:0,jx2_id:jx2_id,'_token':'{{csrf_token()}}'},function(data){
                        if(data.status==0){
                            alert('下调为0%');
                            setTimeout(function(){
                                location.href = location.href;
                            },500)
                        }
                        if(data.status==1){
                            alert(data.msg);
                            setTimeout(function(){
                                location.href = location.href;
                            },500)
                        }
                })
            }


        }
        //删除分类
        {{--function deleteObj(mx_id){--}}
            {{--layer.confirm('确定删除这位仁兄？', {--}}
                {{--btn: ['确定','取消'] //按钮--}}
            {{--}, function(){--}}
                {{--$.post("{{url('admin/mx/')}}/"+mx_id,{'_method':'delete','_token':"{{csrf_token()}}"},function(data){--}}
                    {{--if(data.status=='0'){--}}
                        {{--layer.msg(data.msg, {icon: 6});--}}
                        {{--setTimeout(function(){--}}
                            {{--location.href = location.href;--}}
                        {{--},900)--}}
                    {{--}else if(data.status=='1'){--}}
                        {{--layer.msg(data.msg, {icon: 5});--}}
                        {{--setTimeout(function(){--}}
                            {{--location.href = location.href;--}}
                        {{--},900)--}}
                    {{--}else{--}}
                        {{--layer.msg(data.msg, {icon: 5});--}}
                        {{--setTimeout(function(){--}}
                            {{--location.href = location.href;--}}
                        {{--},900)--}}
                    {{--}--}}
                {{--});--}}
            {{--});--}}
        {{--}--}}

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
