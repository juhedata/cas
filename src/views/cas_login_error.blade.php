<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .main {
            text-align: center;
            padding: 80px;
            font-family: Tahoma, Helvetica, '幼圆', 'microsoft yahei', 'Hiragino Sans GB', Simsun, \5b8b\4f53, sans-serif;
        }

        .section {
            padding: 30px 0 20px;
            color: #00b4ff;
            font-size: 24px;
        }

        .link {
            font-size: 16px;
            color: #fff;
            display: block;
            width: 150px;
            height: 45px;
            line-height: 45px;
            background: #00b4ff;
            margin: 0 auto;
            text-decoration: none;
            border-radius: 4px;
        }

        .link-small {
            font-size: 14px;
            line-height: 50px;
            color: #717171;
        }
    </style>
</head>
<script type="text/javascript">
    // 重置短信发送按钮
    if ({{$timeout && $url ? 1 : 0}}) {
        resetSmsBtn({{$timeout}})
    }

    function resetSmsBtn(index) {
        var init = setInterval(function () {
            index--;
            if (index <= 0) {
                document.getElementById('timeout-show').innerHTML = 0;
                location.href = '{{$url}}'
                clearInterval(init);
            } else {
                document.getElementById('timeout-show').innerHTML = index;
            }
        }, 1000)
    }
</script>
<body>
<div class="main">
    <div>
        <img src="https://juheimg.oss-cn-hangzhou.aliyuncs.com/www/activity/404.png" width="560" height="277"
             alt="404"/>
    </div>
    <div class="section">
        {{$message}}~~~
    </div>
    <div>
        <a class="link" href="{{$url??'/'}}">返回首页</a>
    </div>
    <div class="link-small">
        @if($timeout && $url)页面将在<span id="timeout-show" class="timeout-show">{{$timeout}}</span>秒后跳转@endif
    </div>
</div>
</body>
</html>
