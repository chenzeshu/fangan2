<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<style>
    th,td{
        border:1px solid black;
    }
    table{
        border-collapse: collapse;
    }
</style>
<body>
<h3>{{$user['name']}}，你好，请查看工资发放明细</h3>
<div class="result_wrap">
    <div class="result_content">
        <table class="list_tab">
            <tr>
                <th>姓名</th>
                <th>岗位工资</th>
                <th>工龄补贴</th>
                <th>补扣工资</th>
                <th>加班费</th>
                <th>临时项</th>
                <th>应发数</th>
                <th>工会会费</th>
                <th>公积金</th>
                <th>养老金</th>
                <th>失业保险</th>
                <th>医疗保险</th>
                <th>补交统筹</th>
                <th>补公积</th>
                <th>个人所得税</th>
                <th>房租</th>
                <th>实发数</th>
            </tr>
                <tr>
                    <td>{{$user['name']}}</td>
                    <td>{{$user['salary']}}</td>
                    <td>{{$user['bu']}}</td>
                    <td>@if($user['kou']==0)@else -@endif{{$user['kou']}}</td>
                    <td>{{$user['jia']}}</td>
                    <td>{{$user['lin']}}</td>
                    <td>{{$user['ying']}}</td>
                    <td>{{$user['gh']}}</td>
                    <td>{{$user['gong']}}</td>
                    <td>{{$user['yang']}}</td>
                    <td>{{$user['shi']}}</td>
                    <td>{{$user['yi']}}</td>
                    <td>{{$user['bujiao']}}</td>
                    <td>{{$user['bugong']}}</td>
                    <td>{{$user['shui']}}</td>
                    <td>{{$user['fang']}}</td>
                    <td>{{$user['shifa']}}</td>
                </tr>
        </table>
    </div>
</div>
</body>
</html>