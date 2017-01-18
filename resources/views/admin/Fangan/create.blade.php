@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/self')}}">首页</a> &raquo; <a href="#">个人方案</a> &raquo; <a href="#">添加分系统</a>
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
            <h3>添加分系统</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->
    
    <div class="result_wrap">
        <form action="{{url('admin/fangan/create')}}" method="post" class="uk-form uk-form-stacked">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.25.0/css/uikit.css" />
                <link rel="stylesheet" href="//cdn.bootcss.com/uikit/2.27.2/css/uikit.gradient.css">
                <script src="//cdn.bootcss.com/uikit/2.25.0/js/uikit.js"></script>
                    <tr>
                        <th style="width:255px;">分系统</th>
                        <td>
                            <div class="uk-form-row">
                                <div class="uk-form-controls" onchange="changeVal()">
                                    <select id="form-s-s" name="sys">
                                        <option value="1">车载卫星通信分系统</option>
                                        <option value="2">行业分系统</option>
                                        <option value="3">音视频分系统</option>
                                        <option value="4">计算机网络办公分系统</option>
                                        <option value="5">话音指挥调度分系统</option>
                                        <option value="6">供配电分系统</option>
                                        <option value="7">车辆改装分系统</option>
                                        <option value="8">地面卫星站分系统</option>
                                    </select>
                                    <label for="select" id="mention"></label>
                                    <input type="hidden" name="father" value="0">
                                    <input type="hidden" name="son" value="" id="son">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <button type="submit" class="uk-button">提交</button>
                            {{--<input type="submit" value="提交" class="uk-button">--}}
                            <button type="submit" class="uk-button uk-button-primary" onclick="history.go(-1)">返回</button>
                            {{--<input type="button" class="back uk-button" onclick="history.go(-1)" value="返回">--}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <script>
        $(function () {
            //先自启一次
            changeVal()
        })

        function changeVal() {
            var sys = $('#form-s-s>option:selected').val();
            $('#son').val(sys);
            $.post("{{url('admin/fangan/already')}}/"+sys,{_token:"{{csrf_token()}}"},function(data){
              if (data.status ==1){
                  //todo 已经存在系统
                    $('#mention').html(data.text).removeClass().css('color','red');
                    $('button[type=submit]:eq(0)').attr('disabled',true)
              }
              if (data.status ==2){
                  //todo 不存在系统
                  $('#mention').html(data.text).removeClass().css('color','green');
                  $('button[type=submit]:eq(0)').attr('disabled',false)
              }
            })
        }


    </script>
@endsection