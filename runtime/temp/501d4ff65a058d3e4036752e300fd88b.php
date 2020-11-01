<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:47:"E:\asd1028\fszb1027\think\tpl\dispatch_jump.tpl";i:1603874470;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $msg;?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" href="/public/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/public/static/admin/css/global.css" media="all">
</head>
<body>
    <div class="system-message">
    </div>
    <script type="text/javascript" src="/public/static/plugins/layui/layui.js"></script>
    <script type="text/javascript">
        layui.use('layer', function() {
            var layer = layui.layer;
            layer.msg("<?php echo $msg;?>", {time: 2000,icon:"<?php echo $code;?>"},function(){
                location.href = "<?php echo($url);?>";
            });
        });
    </script>
</body>
</html>
