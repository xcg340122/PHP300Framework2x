<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <title>PHP300Framework Error!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=$staticPath?>css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="<?=$staticPath?>css/zenburn.min.css"/>
    <script src="<?=$staticPath?>js/jquery.min.js"></script>
    <script src="<?=$staticPath?>js/bootstrap.min.js"></script>
    <script src="<?=$staticPath?>js/highlight.min.js"></script>
    <style>
        body{
            background-color:#eee;
        }
        span{
            cursor: pointer;
        }
    </style>
</head>
<body>
<div style="padding: 48px 0;">
    <div class="container">
<?php
if(isset($error['type'])){
?>
<div class="alert alert-danger">错误级别：<?=$error['type'];?></div>
<?php
}
?>
<div class="alert alert-danger">错误文件：<?=$error['file'];?></div>
<div class="alert alert-danger">错误原因：<?=$error['message'];?></div>
<div class="alert alert-danger">错误行数：<?=isset($error['line']) ? $error['line'] : 0;?></div>
<?php
if(!empty($code)){
?>
<pre>
<span class="label label-default">区域预览</span>
<code class="php">
<?=$code;?>
</code>
<span class="label label-info" data-toggle="modal" data-target="#myModal">$_SERVER</span>&nbsp;
</pre>
<?php
}
?>
        </div>
    </div>
</body>
<div class="text-center">
    <a href="http://framework.php300.cn" target="_blank">PHP300Framework2.0 - 更灵活,更强大</a>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    所有$_SERVER变量 (按CTRL+F即可搜索)
                </h4>
            </div>
            <div class="modal-body">
                <?php
                $str = '';
                foreach($_SERVER as $key=>$value){
                    $str .= "<b>{$key}</b>  <font color='red'>>>></font>  <b>{$value}</b><br />";
                }
                echo $str;
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script>
    hljs.initHighlightingOnLoad();
</script>
</html>