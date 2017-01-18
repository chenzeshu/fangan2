@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/pros')}}">首页</a> &raquo; <a href="#">数据库管理</a>
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
	<!--结果集标题与导航组件 开始-->
	<div class="result_wrap">
        <div class="result_title">
            <h3>添加设备</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/pros/create')}}"><i class="fa fa-plus"></i>添加设备</a>
                <a href="{{url('admin/pros')}}"><i class="fa fa-recycle"></i>全部设备</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->
    
    <div class="result_wrap">
        <form action="{{url('admin/pros/')}}" method="post">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                        <tr>
                            <th>分系统：</th>
                            <td>
                                <select name="pros_sys" id="" class="self-border">
                                    <option value="1">系统</option>
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
                            </td>
                        </tr>
                        <tr>
                            <th>设备名称：</th>
                            <td>
                                <input type="text" class="md" name="pros_name" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>品牌：</th>
                            <td>
                                <input type="text" class="md" name="pros_brand" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>设备型号：</th>
                            <td>
                                <input type="text" class="md" name="pros_detail" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>简单描述：</th>
                            <td>
                                <textarea name="pros_less" id="" cols="30" rows="10"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>详细描述：</th>
                            <td>
                            <script type="text/javascript" charset="utf-8" src="{{asset('/resources/org/ueditor/ueditor.config.js')}}"></script>
                            <script type="text/javascript" charset="utf-8" src="{{asset('/resources/org/ueditor/ueditor.all.min.js')}}"></script>
                            <script id="editor" type="text/plain" name="pros_more" style="width:900px;height:600px;"></script>
                            <script>
                                var ue = UE.getEditor('editor');
                            </script>
                            <style>
                                .edui-default .edui-button-body, .edui-splitbutton-body, .edui-menubutton-body, .edui-combox-body,
                                .edui-default .edui-toolbar .edui-combox .edui-combox-body,
                                .edui-default .edui-toolbar .edui-splitbutton .edui-splitbutton-body, .edui-default .edui-toolbar .edui-menubutton .edui-menubutton-body{
                                    height:20px
                                }
                            </style>
                            </td>
                        </tr>
                        <tr>
                            <th>设备图片：</th>
                            <td id="upload">
                                <input type="text" class="md" name="pros_img" value="" size="50" style="display: none;">
                                <input type="text" id="file_upload" name="file_upload" multiple="true" >
                                <a href="javascript:"onclick="delImg()">删除图片</a>
                            </td>
                            <script src="{{asset('/resources/org/uploadify/jquery.uploadify.min.js')}}"></script>
                            <link rel="stylesheet" href="{{asset('/resources/org/uploadify/uploadify.css')}}">
                            <style>
                                .uploadify{
                                    display: inline-block;}
                                .uploadify-button{border:none;border-radius: 5px;
                                    margin-top:8px;}
                                table.add_tab tr td span.uploadify-button-text{color:#FFF;margin:0}
                            </style>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <img src="" alt="" id="preview" style="max-width:150px;max-height: 150px;">
                            </td>
                        </tr>
                        <tr>
                            <th>其他图片</th>
                            <td id="upload2">
                                <input type="text" id="file_upload2" name="file_upload2" multiple="true">
                                <a href="javascript:"onclick="delImg2()" >删除图片</a>
                            </td>
                            <script>
                                <?php $timestamp = time();?>
                                //设备图片
                                $(function() {
                                    $('#file_upload').uploadify({
                                        'buttonText':'上传图片',
                                        'formData'     : {
                                            'timestamp' : '<?php echo $timestamp;?>',
                                            '_token'     : "{{csrf_token()}}"
                                        },
                                        'swf'      : "{{asset('/resources/org/uploadify/uploadify.swf')}}",
                                        'uploader' : "{{url('upload')}}",
                                        'onUploadSuccess':function (file,data,response) {
                                            $('#upload').append('<input type="text" class="md" name="pros_img" size="50" style="display: none;">')
                                            $('input[name=pros_img]').val(data)
                                            $('#preview').attr('src','/'+data)
                                            $('#preview').show()
                                        }
                                    });
                                    //其他图片
                                    $('#file_upload2').uploadify({
                                        'buttonText':'上传图片',
                                        'formData'     : {
                                            'timestamp' : '<?php echo $timestamp;?>',
                                            '_token'     : "{{csrf_token()}}"
                                        },
                                        'swf'      : "{{asset('/resources/org/uploadify/uploadify.swf')}}",
                                        'uploader' : "{{url('upload')}}",
                                        'onUploadSuccess':function (file,data,response) {
                                            $('#upload2').append('<input type="text" class="md" name="pros_img_other[]" size="50" style="display: none;">')
                                            $('#img2').append('<img src="1" style="max-width:150px;max-height: 150px;">')
                                            $('#img2').append('<input type="text" name="pros_img_other_name[]" class="xs">')
                                            index = $('#upload2').children('input').length
                                            //index第一位从0开始，第一张图index=1,因为此前已有一个input按钮
                                            //所以index报1
                                            console.log(index);

                                            $('#upload2').find('input').eq(index-1).val(data)//不知为何罔顾input按钮
                                            $('#img2').find('img').eq(index-1).attr('src','/'+data)
                                            $('img[src="1"]').remove()
                                        }
                                    });
                                });

                                //删除一号的图片
                                function delImg() {
                                    imgName = $('input[name=pros_img]').val()
                                    $.post("{{url('delimg')}}",{imgName:imgName,_token:"{{csrf_token()}}"},function (data) {
                                        console.log(data)
                                        $('input[name=pros_img]').remove()// 不能设val('')为空，否则还是传空的去后台，应删除
                                        $('#preview').hide()
                                    })
                                }
                                //删除二号的最后一张图片（最新上传的图片）
                                function delImg2() {
                                    var length = $('input[name="pros_img_other[]"]').length
                                    var imgName = $('#upload2').find('input.md').eq(length-1).val()
                                    //length 为3
                                    $.post("{{url('delimg')}}",{imgName:imgName,_token:"{{csrf_token()}}"},function (data) {
                                        console.log(data)
                                        $('input[name="pros_img_other[]"]').eq(length-1).remove()//    第一张的input是eq(1)
                                        $('#img2').find('img').eq(length-1).remove()  //第一张的img是eq(0)
                                        $('#img2').find('input').eq(length-1).remove()  //第一张的img是eq(0)
                                        $('img[src="1"]').remove()
                                    })
                                }
                            </script>
                        </tr>
                        <tr>
                            <th></th>
                            <td id="img2">

                            </td>
                        </tr>
                        <tr>
                            <th>数量：</th>
                            <td>
                                <input type="text" class="xs" name="pros_number" value="">
                            　　单　位： <input type="text" class="xs" name="pros_unit" value="">
                            　体积(L*W*H)： <input type="text" class="xs" name="pros_vol" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>重量(kg)：</th>
                            <td>
                                <input type="text" class="xs" name="pros_kg" value="">
                        　机柜尺寸(U)： <input type="text" class="xs" name="pros_u" value="">
                            　功耗(W)：<input type="text" class="xs" name="pros_w" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>产地：</th>
                            <td>
                                <input type="text" class="md" name="pros_area" value="">
                            </td>
                        </tr>
                        <tr>
                            <th>成本单价（元）：</th>
                            <td>
                                <input type="text" class="md" name="pros_inprice" value="">
                                <input type="radio" name="pros_flag_money" value="1"> 美元
                                <input type="radio" name="pros_flag_money" value="2"> 欧元
                                 <input type="radio" name="pros_flag_money" value="3" checked> 人民币
                            </td>
                        </tr>
                        <tr>
                            <th>出厂价格（元）：</th>
                            <td>
                                <input type="text" class="md" name="pros_outprice" value="">
                                <input type="radio" name="pros_flag_bili" value="1"> 无
                                <input type="radio" name="pros_flag_bili" value="2" checked> 有比例
                            </td>
                        </tr>
                        <tr>
                            <th>备注：</th>
                            <td>
                                <textarea name="pros_remark" id="" cols="30" rows="10"></textarea>
                            </td>
                        </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="submit" value="提交">
                            <input type="button" class="back" onclick="history.go(-1)" value="返回">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

@endsection