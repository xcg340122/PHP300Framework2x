<?php
/* Smarty version 3.1.32-dev-38, created on 2018-05-20 23:10:07
  from 'D:\GIT\PHP300Framework2x\Framework\Library\Process\Tpl\error_page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-38',
  'unifunc' => 'content_5b018fcfb08c28_03571689',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fd97c8d35190c3b0a6343df62568dd62529e9d4e' => 
    array (
      0 => 'D:\\GIT\\PHP300Framework2x\\Framework\\Library\\Process\\Tpl\\error_page.tpl',
      1 => 1526829001,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b018fcfb08c28_03571689 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '150675b018fcfab1824_33590824';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        html,body{
            height: 100%;
        }
        .transit {
            width: 100%;
            height: 100%;
            text-align: center;
            display: table;
        }
        .transit div{
            display: table-cell;
            vertical-align: middle;
        }
        h2 {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="transit">
    <div>
        <img src='data:image/png;base64,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'/>
        <h2><?php echo $_smarty_tpl->tpl_vars['data']->value['message'];?>
</h2>
        <p><?php echo $_smarty_tpl->tpl_vars['data']->value['describe'];?>
</p>
        <p></p>
        <p>将在 <span id="mes"><?php echo $_smarty_tpl->tpl_vars['data']->value['second'];?>
</span> 秒后返回上一页！</p>
    </div>
</div>
</body>
<?php echo '<script'; ?>
 type="text/javascript">
    var i = <?php echo $_smarty_tpl->tpl_vars['data']->value['second'];?>
 - 1;
    var url = "<?php echo $_smarty_tpl->tpl_vars['data']->value['url'];?>
";
    var intervalid;
    intervalid = setInterval("fun()", 1000);
    function fun() {
        if (i == 0) {
            if(url == ''){
                window.history.back(-1);
            }else{
                location.href = url;
            }
            clearInterval(intervalid);
        }
        document.getElementById("mes").innerHTML = i;
        i--;
    }
<?php echo '</script'; ?>
>
</html><?php }
}
