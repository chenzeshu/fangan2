@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/self')}}">首页</a> &raquo; <a href="#">个人方案——《{{$table_name}}》</a>
    </div>
    <!--面包屑导航 结束-->

    <style>

    </style>
    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h5><b>方案内容</b></h5>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('admin/fangan/create')}}"><i class="fa fa-plus"></i>添加分系统</a>
                    <a href="javascript:"onclick="export_excel()"><i class="fa fa-plus"></i>导出EXCEL</a>
                    <a href="javascript:"onclick="export_word()"><i class="fa fa-plus"></i>导出WORD</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
        <div class="result_wrap">
            <div class="result_content">
                <script src="{{asset('/resources/views/admin/style/js/jquery.tablednd.min.js')}}"></script>
                <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.25.0/css/uikit.css" />
                <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.27.2/css/uikit.gradient.css">
                <script src="//cdn.bootcss.com/uikit/2.25.0/js/uikit.js"></script>
                <table class="list_tab" id="table">
                    <tr>
                        <th class="tc">ID</th>
                        <th>名称</th>
                        <th>品牌</th>
                        <th>设备型号</th>
                        {{--大图、详细描述--}}
                        <th>图文弹框</th>
                        <th>数量</th>
                        <th>单位</th>
                        <th>产地</th>
                        {{--参数表：体积、机柜尺寸、重量、功耗、--}}
                        <th>参数表</th>
                        <th>成本单价（元）</th>
                        <th>出厂价格（元）</th>
                        <th>备注</th>
                        <th col="3">操作</th>
                    </tr>
                    <div style="display: none;">{{$a = 0}}</div>
                <tbody style="opacity: 0;" id="hideTable">
                    @foreach($data as $m)
                        @if($m->father==0)
                        <tr id="{{$m->id}}">
                            <td class="tc" colspan="12"><b>{{$m->name}}</b></td>
                            <td>
                                <a href="{{url('admin/fangan/edit')."/$m->sys"}}">添加</a>
                                <a href="javascript:"onclick="deleteObj({{$m->id}})">删除</a>
                            </td>
                        </tr>
                        @foreach($data as &$v)
                            @if($v->father == $m->son)
                        <tr id="{{$v->id}}">
                            <td class="tc">{{$a=$a+1}}</td>
                            <td>
                                <a href="#">{{$v->name}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->brand}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->detail}}</a>
                            </td>
                            <td>
                                <button class="uk-button uk-button-mini uk-button-primary" onclick="showDesAndImg({{$v->sysid}})">图文弹框</button>
                            </td>
                            {{--<td>--}}
                            {{--<a href="#">{{$v->more}}</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<a href="#">{{$v->thumb}}</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<a href="#">{{$v->img}}</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<a href="#">{{$v->remark}}</a>--}}
                            {{--</td>--}}
                            <td>
                                <input type="text" value="{{$v->number}}" class="renumber" onclick="reNumber(this,{{$v->id}})">
                            </td>
                            <td>
                                <a href="#">{{$v->unit}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->area}}</a>
                            </td>
                            <td><button class="uk-button uk-button-mini uk-button-primary" onclick="showParams({{$v->sysid}})">参数表</button></td>
                            <td>
                                <a href="#">{{$v->display_inprice}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->display_outprice}}</a>
                            </td>
                            <td><button class="uk-button uk-button-mini uk-button-success" onclick="showRemark({{$v->sysid}})">备注</button></td>
                            <td>
                                <a href="javascript:"onclick="deleteObj({{$v->id}})">删除</a>
                            </td>
                        </tr>
                                    @endif
                            @endforeach
                        @endif
                    @endforeach
                    </tbody>
                </table>
                {{--<div class="page_list">--}}
                   {{--{{$data->links()}}--}}
                {{--</div>--}}
            </div>
        </div>
    <!--搜索结果页面 列表 结束-->

    <style>
        .result_content ul li span{
            padding:6px 12px;
        }
        table,td
        {border:1px solid #e1e1e1;border-collapse: collapse}
    </style>
    <script>
        $(function () {
            //todo 初始渐变效果
            $('#hideTable').css('opacity',0).animate({
                opacity:1
            },500,'linear').css('opacity',1)
            //todo 保存order
            saveOrder();
            //todo 拖拽排序
            $('#table').tableDnD({
                //当拖动排序完成后
                onDrop:function () {
                    //获取id为table的元素
                    var order = '';
                    $('tr[id]').each(function () {
                        order += $(this).attr('id') + ',';
                    })
                    order = order.substring(0,order.length-1);
                    $.post("{{url('admin/fangan/reorder')}}",{order:order,_token:"{{csrf_token()}}"},function (data) {
                        window.location=window.location
                    })
                }
            })
        })
        //todo 图文弹窗：详细描述 大图
        function showDesAndImg(id) {
            $.post("{{url('admin/prosshowdesandimg')}}",{id:id,_token:"{{csrf_token()}}"},function (data) {
                //获取当前网址，如： http://localhost:8083/proj/meun.jsp
                console.log(data.pros_img)
                var curWwwPath = window.document.location.href;
                var pathName = window.document.location.pathname;
                var pos = curWwwPath.indexOf(pathName);
                //获取主机地址，如： http://localhost:8083
                var localhostPath = curWwwPath.substring(0, pos);
                //图片存储地址
                var img = data.pros_img
                //拼接为图片地址
                var imgUrl = localhostPath+'/'+img
                layer.tab({
                    area: ['800px', '600px'],
                    tab: [{
                        title: '简单描述',
                        content: '<br><div style="color:blue">'+data.pros_less+'</div>'
                    }, {
                        title: '详细描述',
                        content: '<br>'+data.pros_more
                    }, {
                        title: '设备图片',
                        content: '<br>&nbsp;<img src="'+imgUrl+'">后面并入《详细描述》'
                    }]
                });
            })
        }
        //todo 参数弹窗
        function showParams(id) {
            $.post("{{url('admin/prosshowdesandimg')}}",{id:id,_token:"{{csrf_token()}}"},function (data) {
                layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['420px', '240px'], //宽高
                    content: '<table class="list_tab">' +
                    '<tr><th>体积(l*w*h)</th><th>机柜尺寸(U)</th><th>重量</th><th>功耗(W)</th></tr>' +
                    '<tr><td>'+data.pros_vol+'</td><td>'+data.pros_u+'</td><td>'+data.pros_kg+'</td><td>'+data.pros_w+'</td></tr>' +
                    '</table>'
                });
            })
        }
        //todo 备注弹窗
        function showRemark(id) {
            $.post("{{url('admin/prosshowdesandimg')}}",{id:id,_token:"{{csrf_token()}}"},function (data) {
                layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['630px', '420px'], //宽高
                    content: '备注：<p>'+data.remark+'</p>'
                });
            })
        }
        //添加设备又未tableDnd时，存order
        function saveOrder() {
            var order = '';
            $('tr[id]').each(function () {
                order += $(this).attr('id') + ',';
            })
            order = order.substring(0,order.length-1);
            $.post("{{url('admin/fangan/reorder')}}",{order:order,_token:"{{csrf_token()}}"},function (data) {

            })
        }
        //todo excel导出
        function export_excel() {
            //询问框
            layer.confirm('是否导出EXCEL参数表？', {
                btn: ['no','导出'] //按钮
            }, function(){
                window.history.go(0)
            },function(){
                location.href = "{{url('excel/export_fangan')}}"+"/{{$table_name}}"
            });
        }
        //todo word导出
        function export_word() {
            //询问框
            layer.confirm('是否导出word方案？', {
                btn: ['no','导出'] //按钮
            }, function(){
                window.history.go(0)
            },function(){
                location.href = "{{url('word/export_fangan')}}"+"/{{$table_name}}"
            });
        }
        //todo 修改数量
        function reNumber(obj,id) {
//            $(obj).prop('readonly',!$(obj).prop('readonly'))

                obj.onkeydown =function () {
                    var e = event || window.event || arguments.callee.caller.arguments[0];
                    if(e&&e.keyCode ==13){
                        var number = $(obj).val()

                        $.post("{{url('admin/fangan/renumber')}}",{id:id,number:number,_token:"{{csrf_token()}}"},function (data) {
                            if (data.status == '0') {
                                layer.msg(data.msg, {icon: 6});

                            } else {
                                layer.msg(data.msg, {icon: 5});

                            }
                        })
                    }
                }

            }

        //todo 删除分类
        function deleteObj(id){
            layer.confirm('确定删除这位仁兄？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/fangan/delete')}}/"+id,{'_token':"{{csrf_token()}}"},function(data){
                    if(data.status=='0'){
                        layer.msg(data.msg, {icon: 6});
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



    </script>
@endsection
