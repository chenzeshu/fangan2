@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/self')}}">首页</a> &raquo; <a href="#">个人方案</a>
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h5 id="pros-mention">数据过多，有<b>【{{$numrows}}】</b>条，本页只显示40条，请使用搜索功能</h5>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <select name="pros_sys" id="pros_sys" class="self-border">
                        <option value="">系统</option>
                        <option value='卫星通信天线'>一、卫星通信天线</option>
                        <option value='卫星功放'>二、卫星功放</option>
                        <option value='卫星LNB'>三、卫星LNB</option>
                        <option value='卫星通信机设备'>四、卫星通信机设备</option>
                        <option value='卫星通信的辅助设备和器材'>五、卫星通信的辅助设备和器材</option>
                        <option value='软件'>六、软件</option>
                        <option value='北斗设备'>七、北斗设备</option>
                        <option value='TD-LTE专网设备'>八、TD-LTE专网设备</option>
                        <option value='卫星电话'>九、卫星电话</option>
                        <option value='对讲设备'>十、对讲设备</option>
                        <option value='短波设备'>十一、短波设备</option>
                        <option value='VOIP语音网关'>十二、VOIP语音网关</option>
                        <option value='语音调度及周边设备'>十三、语音调度及周边设备</option>
                        <option value='计算机及网络设备'>十四、计算机及网络设备</option>
                        <option value='视讯会议和编解码器'>十五、视讯会议和编解码器</option>
                        <option value='图传设备'>十六、图传设备</option>
                        <option value='视音频输入输出设备'>十七、视音频输入输出设备</option>
                        <option value='电源设备'>十八、电源设备</option>
                        <option value='辅助设备'>十九、辅助设备</option>
                        <option value='载车'>二十、载车</option>
                        <option value='信道、服务费用'>二十一、信道、服务费用</option>
                    </select>
                    <input type="text" id="pros_name" placeholder="输入设备名称">
                    <input type="text" id="pros_detail" placeholder="输入型号">
                    <button class="uk-button uk-button-primary" onclick="searchPros()">搜索</button>

                    <button class="uk-button uk-button-success" onclick="checkId()" style="float: right;">提交设备</button>
                    <button onclick="history.go(-1)" class="uk-button" style="margin-left:5px;position: relative;">返回</button>

                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
        <div class="result_wrap">
            <div class="result_content" id="table-father">
                <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.25.0/css/uikit.css" />
                <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.27.2/css/uikit.gradient.css">
                <link rel="stylesheet" href="{{url('/resources/views/admin/style/css/defaultTheme.css')}}">
                <script src="//cdn.bootcss.com/uikit/2.25.0/js/uikit.js"></script>
                <script src="{{url('/resources/views/admin/style/js/jquery.fixedheadertable.min.js')}}"></script>
                <table class="list_tab" id="table">
                    <thead>
                        <tr>
                            {{--<th class="tc">ID</th>--}}
                            <th>选择</th>
                            {{--<th>分系统</th>--}}
                            <th>名称</th>
                            <th>品牌</th>
                            <th>设备型号</th>
                            <th style="width: 250px;">简单描述</th>
                            {{--大图、详细描述--}}
                            <th>图文弹框</th>
                            <th>单位</th>
                            <th>产地</th>
                            {{--参数表：体积、机柜尺寸、重量、功耗、--}}
                            <th>参数表</th>
                            <th>成本单价（元）</th>
                            <th>出厂价格（元）</th>
                            <th>备注</th>
                        </tr>
                    </thead>
                    <div style="display: none;">{{$a = 0}}</div>
                    <tbody>
                    @foreach($pros as $v)
                        <tr>
                            <td onclick="checkboxBug(this)">
                                <input type="checkbox" name="pros_id[]" value="{{$v->pros_id}}" style="display: none;">
                                <a href="#" class="check-false" onclick="return false">□</a>
                            </td>

                            <td>
                                <a href="#">{{$v->pros_name}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->pros_brand}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->pros_detail}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->pros_less}}</a>
                            </td>
                            <td><button class="uk-button uk-button-mini uk-button-primary" onclick="showDesAndImg({{$v->pros_id}})">图文弹框</button></td>
                            {{--<td>--}}
                            {{--<a href="#">{{$v->pros_more}}</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<a href="#">{{$v->pros_thumb}}</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<a href="#">{{$v->pros_img}}</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<a href="#">{{$v->pros_remark}}</a>--}}
                            {{--</td>--}}
                            <td>
                                <a href="#">{{$v->pros_unit}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->pros_area}}</a>
                            </td>
                            <td><button class="uk-button uk-button-mini uk-button-primary" onclick="showParams({{$v->pros_id}})">参数表</button></td>
                            <td>
                                <a href="#">{{$v->pros_display_inprice}}</a>
                            </td>
                            <td>
                                <a href="#">{{$v->pros_display_outprice}}</a>
                            </td>
                            <td><button class="uk-button uk-button-mini uk-button-success" onclick="showRemark({{$v->pros_id}})">备注</button></td>
                        </tr>
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
        .check-false{
            color:#000;
            font-size:3em;
        }
        .check-success{
            font-size:2em;
            color:green;
        }
        ::-webkit-scrollbar{
            width: 10px;
            height: 6px;
            background-color: #f5f5f5;
        }
        /*定义滚动条的轨道，内阴影及圆角*/
        ::-webkit-scrollbar-track{
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            border-radius: 10px;
            background-color: #f5f5f5;
        }
        /*定义滑块，内阴影及圆角*/
        ::-webkit-scrollbar-thumb{
            /*width: 10px;*/
            height: 20px;
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }
        table,td
        {border:1px solid #e1e1e1;border-collapse: collapse}
    </style>
    <script>
        $(function () {
            //当前屏幕的黄金分割高度刚好使界面可以不出现最右滚动条
            var height = window.screen.height*0.618
//            console.log(height)
            $('#table-father').css("height",height+'px')
            $('#table').fixedHeaderTable()
        })
        //todo 所有被选中的设备的checkbox的AJAX
        function checkId() {
            //系统的身份
            var sys_id = "{{$sys_id}}";
            var sys_name = "{{$sys_name}}"

            //todo 获得所有被选设备的IDs
            var ids =''
            $('input[type=checkbox]:checked').each(function () {
                ids += $(this).val()+','
            })
            ids = ids.substring(0,ids.length-1)
            if (!ids){
                layer.msg('请至少选择一个设备');
                return false
            }

            $.post("{{url('admin/fangan/checkid')}}",
                    {ids:ids,sys_id:sys_id,sys_name:sys_name,_token:"{{csrf_token()}}"},
                    function (data) {
                        location.href = "{{url('admin/fangan/index').'/'.session('table_id')}}"

            })
        }

        //todo checkbox的自定义样式
        function checkboxBug(obj) {
            //因为checkbox有全选的bug，所以用了其他方法
            //todo 改变目标checkbox状态
            var flag = $(obj).children('input').attr('checked')
            $(obj).children('input').attr('checked',!flag)

            //todo 改变文字的状态
            if (!flag){
                $(obj).children('a').html('√').removeClass().addClass('check-success');
            }else{
                $(obj).children('a').html('□').removeClass().addClass('check-false');
            }

        }
        //todo 搜索
        function searchPros() {
            var sys=$('#pros_sys').val();
            var name=$('#pros_name').val();
            var detail=$('#pros_detail').val();

            //todo 防止提交空表单爆破数据库
            if(!(sys||name||detail)) {
                alert("请至少输入一项数据！")
                return false
            }
            //隐藏提示语
            $('#pros-mention').hide()

            $.post("{{url('admin/fangan/searchPros')}}",{sys:sys,name:name,detail:detail,_token:"{{csrf_token()}}"},function (data) {
                if(data.status==0){
                    var res = data.msg
                    $('#table tr:gt(0)').empty()
                    $.each(res,function (k,v) {
                        var id = v.pros_id
                        var name = v.pros_name
                        var brand = v.pros_brand
                        var detail = v.pros_detail
                        var less = v.pros_less
                        var number = v.pros_number
                        var unit = v.pros_unit
                        var area = v.pros_area
                        var inprice = v.pros_display_inprice
                        var outprice = v.pros_display_outpirce
                        var remark = v.pros_remark

                        $('#table').append('<tbody><tr>' +
                                '<td onclick="checkboxBug(this)">' +
                                    '<input type="checkbox" name="pros_id[]" value='+v.pros_id+' style="display: none;">' +
                                    '<a href="#" class="check-false" onclick="return false">□</a></td>' +
                                '<td>' +
                                '<a href="#">'+name+'</a></td>' +
                                '<td><a href="#">'+brand+'</a></td>' +
                                '<td><a href="#">'+detail+'</a></td>' +
                                '<td><a href="#">'+less+'</a></td>' +
                                '<td><a href="#"><button class="uk-button uk-button-mini uk-button-primary" onclick="showDesAndImg('+id+')">图文弹框</button></a></td>' +
                                '<td><a href="#">'+unit+'</a></td>' +
                                '<td><a href="#">'+area+'</a></td>' +
                                '<td><a href="#"><button class="uk-button uk-button-mini uk-button-primary" onclick="showParams('+id+')">参数表</button></a></td>' +
                                '<td><a href="#">'+inprice+'</a></td>' +
                                '<td><a href="#">'+outprice+'</a></td>' +
                                '<td><button class="uk-button uk-button-mini uk-button-success" onclick="showRemark('+id+')>备注</button></td>' +
                                '</tr></tbody>')
                    })
                    $('tbody').css('opacity',0).animate({
                        opacity:1
                    },1000,'linear')
                }
            })

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
        //补0函数
        function AppendZero(obj)
        {
            if(obj<10) return "0" +""+ obj;
            else return obj;
        }
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

    </script>
@endsection
