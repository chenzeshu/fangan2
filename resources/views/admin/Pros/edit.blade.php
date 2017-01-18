@extends('layouts/admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/pros')}}">首页</a> &raquo; <a href="#">数据库</a>
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
            <h3>编辑设备</h3>
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
        {{--<form action="{{url('admin/pros/'.$field->pros_id)}}" method="post">--}}
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th>分系统：</th>
                    <td>
                        <select name="pros_sys" id="" class="self-border">
                            <option value="1" @if($field->pros_sys ==1 ) selected @endif>系统</option>
                            <option value='卫星通信天线' @if($field->pros_sys =='卫星通信天线' ) selected @endif>一、卫星通信天线</option>
                            <option value='卫星功放' @if($field->pros_sys =='卫星功放' ) selected @endif>二、卫星功放</option>
                            <option value='卫星LNB' @if($field->pros_sys =='卫星LNB' ) selected @endif>三、卫星LNB</option>
                            <option value='卫星通信机设备' @if($field->pros_sys =='卫星通信机设备' ) selected @endif>四、卫星通信机设备</option>
                            <option value='卫星通信的辅助设备和器材' @if($field->pros_sys =='卫星通信的辅助设备和器材' ) selected @endif>五、卫星通信的辅助设备和器材</option>
                            <option value='软件' @if($field->pros_sys =='软件' ) selected @endif>六、软件</option>
                            <option value='北斗设备' @if($field->pros_sys =='北斗设备' ) selected @endif>七、北斗设备</option>
                            <option value='TD-LTE专网设备' @if($field->pros_sys =='TD-LTE专网设备' ) selected @endif>八、TD-LTE专网设备</option>
                            <option value='卫星电话' @if($field->pros_sys =='卫星电话' ) selected @endif>九、卫星电话</option>
                            <option value='对讲设备' @if($field->pros_sys =='对讲设备' ) selected @endif>十、对讲设备</option>
                            <option value='短波设备' @if($field->pros_sys =='短波设备' ) selected @endif>十一、短波设备</option>
                            <option value='VOIP语音网关' @if($field->pros_sys =='VOIP语音网关' ) selected @endif>十二、VOIP语音网关</option>
                            <option value='语音调度及周边设备' @if($field->pros_sys =='语音调度及周边设备' ) selected @endif>十三、语音调度及周边设备</option>
                            <option value='计算机及网络设备' @if($field->pros_sys == '计算机及网络设备') selected @endif>十四、计算机及网络设备</option>
                            <option value='视讯会议和编解码器' @if($field->pros_sys =='视讯会议和编解码器' ) selected @endif>十五、视讯会议和编解码器</option>
                            <option value='图传设备' @if($field->pros_sys =='图传设备' ) selected @endif>十六、图传设备</option>
                            <option value='视音频输入输出设备' @if($field->pros_sys =='视音频输入输出设备' ) selected @endif>十七、视音频输入输出设备</option>
                            <option value='电源设备' @if($field->pros_sys =='电源设备' ) selected @endif>十八、电源设备</option>
                            <option value='辅助设备' @if($field->pros_sys == '辅助设备') selected @endif>十九、辅助设备</option>
                            <option value='载车' @if($field->pros_sys == '载车') selected @endif>二十、载车</option>
                            <option value='信道、服务费用' @if($field->pros_sys == '信道、服务费用') selected @endif>二十一、信道、服务费用</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>设备名称：</th>
                    <td>
                        <input type="text" class="md" name="pros_name" value="{{$field->pros_name}}">
                    </td>
                </tr>
                <tr>
                    <th>品牌：</th>
                    <td>
                        <input type="text" class="md" name="pros_brand" value="{{$field->pros_brand}}">
                    </td>
                </tr>
                <tr>
                    <th>设备型号：</th>
                    <td>
                        <input type="text" class="md" name="pros_detail" value="{{$field->pros_detail}}">
                    </td>
                </tr>
                <tr>
                    <th>简单描述：</th>
                    <td>
                        <textarea name="pros_less" id="" cols="30" rows="10">{{$field->pros_less}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>详细描述：</th>
                    <td>
                        <script type="text/javascript" charset="utf-8" src="{{asset('/resources/org/ueditor/ueditor.config.js')}}"></script>
                        <script type="text/javascript" charset="utf-8" src="{{asset('/resources/org/ueditor/ueditor.all.min.js')}}"></script>
                        <script id="editor" type="text/plain" name="pros_more" style="width:900px;height:600px;">{!! $field->pros_more !!}</script>
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
                        <input type="text" class="md" name="pros_img" value="{{$field->pros_img}}" size="50" style="display: none;">
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
                        <img src="/{{$field->pros_img}}" id="preview" style="max-width:150px;max-height: 150px;" alt="目前无图">
                    </td>
                </tr>
                <tr>
                    <th>其他图片</th>
                    <td id="upload2">
                        <input type="text" id="file_upload2" name="file_upload2" multiple="true">
                        <a href="javascript:"onclick="delImg2()">删除最新上传的图片</a>
                        @foreach($field->pros_img_other as $img)
                            <input type="text" name="pros_img_other[]" value="{{$img}}" style="display: none" class="md">
                        @endforeach
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
                                    index = $('#upload2').children('input').length
                                    //index第一位从0开始，第一张图index=1,因为此前已有一个input按钮
                                    //所以index报1
                                    console.log(index);

                                    $('#upload2').find('input').eq(index-1).val(data)//不知为何罔顾input按钮
                                    $('#img2').find('img').eq(index-1).attr('src','/'+data)
                                    $('img[src="1"]').remove()
                                }
                            });

                            //去掉blade模版导致的src="/"的img标签和相应的input
                            $('img[src="/"]').remove()
                            $('input[value=""]').remove()

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
                                $('img[src="1"]').remove()
                            })
                        }
                    </script>
                </tr>
                <tr>
                    <th></th>
                    <td id="img2">
                        @foreach($field->pros_img_other as $img)
                            <img src="/{{$img}}" style="max-width:150px;max-height: 150px;" alt="目前无图">
                        @endforeach

                    </td>
                </tr>
                <tr>
                    <th>数量：</th>
                    <td>
                        <input type="text" class="xs" name="pros_number" value="{{$field->pros_number}}">
                        　　单　位： <input type="text" class="xs" name="pros_unit" value="{{$field->pros_unit}}">
                        　体积(L*W*H)： <input type="text" class="xs" name="pros_vol" value="{{$field->pros_vol}}">
                    </td>
                </tr>
                <tr>
                    <th>重量(kg)：</th>
                    <td>
                        <input type="text" class="xs" name="pros_kg" value="{{$field->pros_kg}}">
                        　机柜尺寸(U)： <input type="text" class="xs" name="pros_u" value="{{$field->pros_u}}">
                        　功耗(W)：<input type="text" class="xs" name="pros_w" value="{{$field->pros_w}}">
                    </td>
                </tr>
                <tr>
                    <th>产地：</th>
                    <td>
                        <input type="text" class="md" name="pros_area" value="{{$field->pros_area}}">
                    </td>
                </tr>
                <tr>
                    <th>成本单价（元）：</th>
                    <td>
                        <input type="text" class="md" name="pros_inprice" value="{{$field->pros_inprice}}">
                        <input type="radio" name="pros_flag_money" value="1" @if($field->pros_flag_money == 1) checked @endif> 美元
                        <input type="radio" name="pros_flag_money" value="2" @if($field->pros_flag_money == 2) checked @endif> 欧元
                        <input type="radio" name="pros_flag_money" value="3" autofocus @if($field->pros_flag_money == 3) checked @endif> 人民币
                    </td>
                </tr>
                <tr>
                    <th>出厂价格（元）：</th>
                    <td>
                        <input type="text" class="md" name="pros_outprice" value="{{$field->pros_outprice}}">
                        <input type="radio" name="pros_flag_bili" value="1" @if($field->pros_flag_bili == 1) checked @endif> 无
                        <input type="radio" name="pros_flag_bili" value="2" @if($field->pros_flag_bili == 2) checked @endif> 有比例
                    </td>
                </tr>
                <tr>
                    <th>备注：</th>
                    <td>
                        <textarea name="pros_remark" id="" cols="30" rows="10">{{$field->pros_remark}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="button" value="提交" onclick="tijiao({{$field->pros_id}})">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        {{--</form>--}}
    </div>
    <script>
        function tijiao(pros_id) {
            var sys = $('.self-border>option:selected').val()
            var name = $('[name=pros_name]').val()
            var brand =  $('[name=pros_brand]').val()
            var detail =  $('[name=pros_detail]').val()
            var less =  $('[name=pros_less]').val()
            var more =  UE.getEditor('editor').getContent()
            var img  =  $('[name=pros_img]').val()
            var number    =  $('[name=pros_number]').val()
            var unit =  $('[name=pros_unit]').val()
            var vol  =  $('[name=pros_vol]').val()
            var kg   =  $('[name=pros_kg]').val()
            var u    =  $('[name=pros_u]').val()
            var w    =  $('[name=pros_w]').val()
            var area =  $('[name=pros_area]').val()
            var inprice  =  $('[name=pros_inprice]').val()
            var outprice =  $('[name=pros_outprice]').val()
            var money = $('[name=pros_flag_money]:checked').val()
            var bili = $('[name=pros_flag_bili]:checked').val()
            var remark = $('[name=pros_remark]').val()
            var img_other = ''
            img_other = $('input[name="pros_img_other[]"').map(function () {
                    return $(this).val()
            }).get().join(',')
            var img_other_name = ''
            img_other_name = $('input[name="pros_img_other_name[]"').map(function () {
                return $(this).val()
            }).get().join(',')
            var url ="{{url('admin/pros/')}}/"+pros_id
            $.post("{{url('admin/pros/')}}/"+pros_id,{
                'pros_sys' : sys,
                'pros_name' : name,
                'pros_brand' : brand,
                'pros_detail' : detail,
                'pros_less' : less,
                'pros_more' :more,
                'pros_img' :img,
                'pros_number' : number,
                'pros_unit' : unit,
                'pros_vol' : vol,
                'pros_kg':kg,
                'pros_u':u,
                'pros_w':w,
                'pros_area':area,
                'pros_inprice':inprice,
                'pros_outprice':outprice,
                'pros_flag_money':money,
                'pros_flag_bili':bili,
                'pros_remark':remark,
                'pros_img_other':img_other,
                'pros_img_other_name':img_other_name,
                '_token':"{{csrf_token()}}",
                '_method':'put'
            },function (res) {
                if (res.status == 0){
                    history.go(-1)
                }
                if (res.status  ==1){
                    alert(res.data)
                }
            })
        }
    </script>

@endsection