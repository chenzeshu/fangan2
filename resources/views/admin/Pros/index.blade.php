@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/pros')}}">首页</a> &raquo; <a href="#">数据库管理</a>
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
        <div class="result_wrap">
            <div class="result_title">
                <h3>数据库列表</h3>
            </div>
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="{{url('admin/pros/create')}}"><i class="fa fa-plus"></i>添加设备</a>
                    <a href="{{url('admin/pros')}}"><i class="fa fa-recycle"></i>全部设备</a>
                    <select name="" id="pros_sys" class="self-border" style="">
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
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>
        <div class="result_wrap">
            <div class="result_content" id="table-father">
                <table class="list_tab fancyTable" id="table">
                    <thead>
                    <tr>
                        <th class="tc">ID</th>
                        <th>分系统</th>
                        <th>名称</th>
                        <th>品牌</th>
                        <th style="width: 150px;">设备型号</th>
                        {{--大图、简单描述、详细描述--}}
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
                    </thead>
                    <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.25.0/css/uikit.css" />
                    <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.27.2/css/uikit.gradient.css">
                    <link rel="stylesheet" href="{{url('/resources/views/admin/style/css/defaultTheme.css')}}">
                    <script src="//cdn.bootcss.com/uikit/2.25.0/js/uikit.js"></script>
                    <script src="{{url('/resources/views/admin/style/js/jquery.fixedheadertable.min.js')}}"></script>
                <tbody id="hideTable" style="opacity: 0">
                    @foreach($data as $v)
                    <tr>
                        <td class="tc">{{$v->pros_id}}</td>
                        <td>
                            <a href="#">{{$v->pros_sys}}</a>
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
                            <button class="uk-button uk-button-mini uk-button-primary" onclick="showDesAndImg({{$v->pros_id}})">图文弹框</button>
                        </td>
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
                            <a href="#">{{$v->pros_number}}</a>
                        </td>
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
                        <td>
                            <a href="{{url('admin/pros/'.$v->pros_id.'/edit')}}">修改</a>
                            <a href="javascript:"onclick="deleteObj({{$v->pros_id}})">删除</a>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
                </table>
                <div class="page_list">
                    {{$data->links()}} &nbsp;
                    <span style="position: absolute;">
                        <input type="text" name="pageid" style="width: 50px;">
                        <button class="uk-button uk-button-danger" onclick="page2page()">go</button>
                    </span>
                </div>

            </div>
        </div>
    <!--搜索结果页面 列表 结束-->

    <style>
        .result_content ul li span{
            padding:6px 12px;
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
            //todo 初始渐变效果
            $('#hideTable').css('opacity',0).animate({
                opacity:1
            },500,'linear').css('opacity',1)
        })
        //todo 页面输入页码跳转
        function page2page() {
            var id = $('input[name=pageid]').val()
            var url = location.href
            var index =url.indexOf('=')
            if(index < 0 ){
                var new_url = url+"?page="+id
            }else{
                var new_url = url.substring(0,index+1)+id
            }
//            alert(new_url)
            location.href = new_url
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

            //todo 重新定义屏幕
            //todo 与 Fangan.blade 不同，这里有一个初始带页码的界面
            //todo 点击后的效果，可以参考fixedHeaderTable的Test的DEMO第一个例子
            var height = window.screen.height*0.618
//            console.log(height)
            $('#table-father').css("height",height+'px')
            $('tbody').css('opacity',0).animate({
                opacity:1
            },1000,'linear')
            $('#table').fixedHeaderTable()


            $.post("{{url('admin/prosearch')}}",{sys:sys,name:name,detail:detail,_token:"{{csrf_token()}}"},function (data) {
                if(data.status==0){
                    var res = data.msg
                    $('#table tr:gt(0)').empty()
                    $('.page_list').hide()
                    $.each(res,function (k,v) {
                        var id = v.pros_id
                        var sys = v.pros_sys
                        var name = v.pros_name
                        var brand = v.pros_brand
                        var detail = v.pros_detail
                        var less = v.pros_less
                        var number = v.pros_number
                        var unit = v.pros_unit
                        var area = v.pros_area
                        var inprice = v.pros_inprice
                        var outprice = v.pros_outpirce
//                        var remark = v.pros_remark
                        var editUrl = "{{url('admin/pros/')}}/"+id+'/edit';

                        $('#table').append('<tr>' +
                                '<td>' +id +'</td>'+
                                '<td><a href="#">'+sys+'</a></td>' +
                                '<td>' +
                                 '<a href="#">'+name+'</a>' +
                                '</td>' +
                                '<td><a href="#">'+brand+'</a></td>' +
                                '<td style="width: 150px;"><a href="#">'+detail+'</a></td>' +
                                '<td><a href="#">' +
                                '<button class="uk-button uk-button-mini uk-button-primary" onclick="showDesAndImg('+id+')">图文弹框</button>' +
                                '</a></td>' +
                                '<td><a href="#">'+number+'</a></td>' +
                                '<td><a href="#">'+unit+'</a></td>' +
                                '<td><a href="#">'+area+'</a></td>' +
                                '<td><a href="#">' +
                                '<button class="uk-button uk-button-mini uk-button-primary" onclick="showParams('+id+')">参数表</button>' +
                                '</a></td>' +
                                '<td><a href="#">'+inprice+'</a></td>' +
                                '<td><a href="#">'+outprice+'</a></td>' +
                                '<td>' +
                                '<button class="uk-button uk-button-mini uk-button-success" onclick="showRemark('+id+')">备注</button>' +
                                '</td>' +
                                '<td>' +
                                '<a href="'+editUrl+'">修改</a>' +
                                '<a href="javascript:"onclick="deleteObj('+id+')">删除</a>' +
                                '</td>' +
                                '</tr>')
                    })
                }
            })

        }
        //todo 删除分类
        function deleteObj(pros_id){
            layer.confirm('确定删除这位仁兄？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.post("{{url('admin/pros/')}}/"+pros_id,{'_method':'delete','_token':"{{csrf_token()}}"},function(data){
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


    </script>
@endsection
