@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('gongzi/shui')}}">首页</a> &raquo; <a href="#">个人所得税表</a>
    </div>
    <!--面包屑导航 结束-->


    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h3>人员个税列表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('excel/export_shui')}}"><i class="fa fa-plus"></i>导出EXCEL表单</a>
                    <input type="text" name="name" class="xs" placeholder="填写查找姓名" id="search">
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
                        <th>纳税义务人姓名</th>
                        <th>身份证号</th>
                        <th>放发总额</th>
                        <th>扣除数</th>
                        <th>应扣养老公积金</th>
                        <th>计税基数</th>
                        <th>交税比例</th>
                        <th>扣除数</th>
                        <th>个税</th>
                    </tr>
                    @foreach($array as $k=>$v)
                    <tr>
                        <td class="tc">{{$v['id']}}</td>
                        <td>{{$v['name']}}</td>
                        <td>{{$v['credit']}}</td>
                        <td>{{$v['salary']}}</td>
                        <td>{{$v['kou']}}</td>
                        <td>{{$v['yingkou']}}</td>
                        <td>{{$v['jishui']}}</td>
                        <td>{{$v['bili']}}</td>
                        <td>{{$v['kou2']}}</td>
                        <td>{{$v['shui']}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="tc"></td>
                        <td>合计</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$whole}}</td>
                    </tr>
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
        //删除分类
        function deleteObj(id){
            layer.confirm('确定删除这位仁兄？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('gongzi/shui/')}}/"+id,{'_method':'delete','_token':"{{csrf_token()}}"},function(data){
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
            $.post("{{url('gongzi/shui/search')}}",{name:val,'_token':'{{csrf_token()}}'},function(data){
                $('tr:gt(0)').remove();
                    $(data).each(function(k,v){
                        var url1 ="base/"+v.id+"/edit";
                        var url2 = 'javascript:onclick=deleteObj('+v.id+')';
                        $('tr:first').after("<tr><td class='tc'>"+v.id+"</td>" +
                                "<td>"+v.name+"</td>" +
                                "<td>"+v.gong+"</td>" +
                                "<td>"+v.yang+"</td>" +
                                "<td><a href='"+url1+"'>修改</a><a href="+url2+">删除</a></td></tr>");
                    })
            });
        }
          {{--function changeOrder(obj,id){--}}
              {{--var order = $(obj).val();--}}
              {{--$.post('{{url('gongzi/shui/changeorder')}}',{'_token':"{{csrf_token()}}",'order':order,'id':id},function (data) {--}}
                  {{--if(data.status=='0'){--}}
                      {{--layer.alert(data.msg,{icon: 6});--}}
                  {{--}else{--}}
                      {{--layer.alert(data.msg,{icon: 5});--}}
                  {{--}--}}
              {{--})--}}
          {{--}--}}

    </script>
@endsection
