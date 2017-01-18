<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('/resources/views/admin/style/img/logo.png')}}" type="image/x-icon">
    <title>方案自动化平台</title>
    <link rel="stylesheet" href="{{asset('/resources/views/admin/style/css/ch-ui.admin.css')}}">
    <link rel="stylesheet" href="{{asset('/resources/views/admin/style/font/css/font-awesome.min.css')}}">
    <script type="text/javascript" src="{{asset('/resources/views/admin/style/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/resources/views/admin/style/js/ch-ui.admin.js')}}"></script>
    <script type="text/javascript" src="{{asset('/resources/org/layer-v2.4/layer/layer.js')}}"></script>
</head>
<style>
    .self-border{
        border: solid 1px #CCCCCC;
        border-radius: 3px;
        width:150px;
        height: 28px;
    }
</style>
<body>
@yield('content')
</body>
</html>