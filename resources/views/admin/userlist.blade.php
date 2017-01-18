@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/reward')}}">首页</a> &raquo; <a href="#">用户列表</a>
    </div>
    <!--面包屑导航 结束-->
    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h3>用户列表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('admin/register')}}"><i class="fa fa-plus"></i>添加用户</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th>用户名</th>
                        <th>用户权限</th>
                        <th>注册时间</th>
                        <th>最后改动时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach($data as $v)
                    <tr>
                        <td class="tc">{{$v->id}}</td>
                        <td>
                            <a href="#">{{$v->user_name}}</a>
                        </td>
                        <td>
                            <a href="#">{{$v->user_type}}</a>
                        </td>
                        <td>
                            <a href="#">{{$v->created_at}}</a>
                        </td>
                        <td>
                            <a href="#">{{$v->updated_at}}</a>
                        </td>
                        <td>
                            {{--<a href="{{url('admin/reward/'.$v->user_id.'/edit')}}">修改</a>--}}
                            <a href="javascript:"onclick="deleteObj({{$v->id}})">删除</a>
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
        function deleteObj(user_id){
            layer.confirm('确定删除这位仁兄？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/delete/')}}/"+user_id,{'_method':'delete','_token':"{{csrf_token()}}"},function(data){
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
                $.post('{{url('admin/reward/search')}}',{"name":name,"_token":"{{csrf_token()}}"},function(data){
                    $('table tr:gt(0)').empty();
                    $.each(data,function(k,v){
                        var time_begin = v.user_date_start*1000;  //unix时间戳*1000方便让js计算
                        var date_begin = new Date(time_begin);   //实例化Date对象
                        var begin = date_begin.getFullYear()+"-"+AppendZero(date_begin.getMonth()+1)+"-"+AppendZero(date_begin.getDate()); //组装日期
                        var time_end = v.user_date_end*1000;
                        var date_end = new Date(time_end);
                        var end = date_end.getFullYear()+"-"+AppendZero(date_end.getMonth()+1)+"-"+AppendZero(date_end.getDate());
                        var url = "reward/"+v.user_id+"/edit";
                        var url2 = 'javascript:onclick=deleteObj('+v.user_id+')';
                        if (v.user_reward==1){
                            v.user_reward="奖励";
                            v.user_float = "+"+v.user_float;
                        }else {
                            v.user_reward="惩罚";
                            v.user_float = "-"+v.user_float;
                        }
                        $('table').append("<tr><td class='tc'>"+v.user_id+"</td><td>"+v.user_name+"</td><td>"+v.user_reward+"</td>" +
                                "<td>"+v.user_float+"</td><td>"+begin+"</td><td>"+end+"</td>" +
                                "<td><a href='"+url+"'>修改</a><a href='"+url2+"'>删除</a></td></tr>")
                    })
                })
            }else{
                alert("请输入信息");
            }
        }
          {{--function endOrder(obj,user_id){--}}
              {{--var order = $(obj).val();--}}
              {{--$.post('{{url('admin/reward/endorder')}}',{'_token':"{{csrf_token()}}",'order':order,'user_id':user_id},function (data) {--}}
                  {{--if(data.status=='0'){--}}
                      {{--layer.alert(data.msg,{icon: 6});--}}
                  {{--}else{--}}
                      {{--layer.alert(data.msg,{icon: 5});--}}
                  {{--}--}}
              {{--})--}}
          {{--}--}}

    </script>
@endsection
