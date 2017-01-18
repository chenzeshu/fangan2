@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/params')}}">首页</a> &raquo; <a href="#">参数管理</a>
    </div>
    <!--面包屑导航 结束-->


    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h3>参数列表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('admin/params/create')}}"><i class="fa fa-plus"></i>添加参数信息</a>
                    <a href="{{url('admin/params')}}"><i class="fa fa-recycle"></i>全部参数信息</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th>美元汇率</th>
                        <th>欧元汇率</th>
                        <th>报价比例</th>
                        <th>最后更新日期</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $v)
                    <tr>
                        <td>
                            <a href="#">{{$v->pa_dollar}}</a>
                        </td>
                        <td>
                            <a href="#">{{$v->pa_eu}}</a>
                        </td>
                        <td>
                            <a href="#">{{$v->pa_bili}}</a>
                        </td>
                        <td>
                            {{$v->updated_at}}
                        </td>
                        <td>
                            <a href="{{url('admin/params/'.$v->pa_id.'/edit')}}">修改</a>
                            <a href="javascript:"onclick="deleteObj({{$v->pa_id}})">删除</a>
                        </td>
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
        //删除分类
        function deleteObj(pa_id){
            layer.confirm('确定删除这位仁兄？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/params/')}}/"+pa_id,{'_method':'delete','_token':"{{csrf_token()}}"},function(data){
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
        //补0函数
        function AppendZero(obj)
        {
            if(obj<10) return "0" +""+ obj;
            else return obj;
        }
        //搜索符合条件的+Unix时间戳=>Js时间戳=>得到日期（补0)
        function searchName(){
            var name = $('#search').val();
            if(name){
                $.post('{{url('admin/params/search')}}',{"name":name,"_token":"{{csrf_token()}}"},function(data){
                    $('table tr:gt(0)').empty();
                    $.each(data,function(k,v){
                        var time_begin = v.pa_date_start*1000;  //unix时间戳*1000方便让js计算
                        var date_begin = new Date(time_begin);   //实例化Date对象
                        var begin = date_begin.getFullYear()+"-"+AppendZero(date_begin.getMonth()+1)+"-"+AppendZero(date_begin.getDate()); //组装日期
                        var time_end = v.pa_date_end*1000;
                        var date_end = new Date(time_end);
                        var end = date_end.getFullYear()+"-"+AppendZero(date_end.getMonth()+1)+"-"+AppendZero(date_end.getDate());
                        var url = "params/"+v.pa_id+"/edit";
                        var url2 = 'javascript:onclick=deleteObj('+v.pa_id+')';
                        if (v.pa_params==1){
                            v.pa_params="奖励";
                            v.pa_float = "+"+v.pa_float;
                        }else {
                            v.pa_params="惩罚";
                            v.pa_float = "-"+v.pa_float;
                        }
                        $('table').append("<tr><td class='tc'>"+v.pa_id+"</td><td>"+v.pa_name+"</td><td>"+v.pa_params+"</td>" +
                                "<td>"+v.pa_float+"</td><td>"+begin+"</td><td>"+end+"</td>" +
                                "<td><a href='"+url+"'>修改</a><a href='"+url2+"'>删除</a></td></tr>")
                    })
                })
            }else{
                alert("请输入信息");
            }
        }
          {{--function endOrder(obj,pa_id){--}}
              {{--var order = $(obj).val();--}}
              {{--$.post('{{url('admin/params/endorder')}}',{'_token':"{{csrf_token()}}",'order':order,'pa_id':pa_id},function (data) {--}}
                  {{--if(data.status=='0'){--}}
                      {{--layer.alert(data.msg,{icon: 6});--}}
                  {{--}else{--}}
                      {{--layer.alert(data.msg,{icon: 5});--}}
                  {{--}--}}
              {{--})--}}
          {{--}--}}

    </script>
@endsection
