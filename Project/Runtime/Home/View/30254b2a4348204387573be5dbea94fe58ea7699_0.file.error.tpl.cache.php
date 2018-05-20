<?php
/* Smarty version 3.1.32-dev-38, created on 2018-05-20 22:18:57
  from 'D:\GIT\PHP300Framework2x\Framework\Library\Process\Tpl\error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-38',
  'unifunc' => 'content_5b0183d18f7911_18188917',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '30254b2a4348204387573be5dbea94fe58ea7699' => 
    array (
      0 => 'D:\\GIT\\PHP300Framework2x\\Framework\\Library\\Process\\Tpl\\error.tpl',
      1 => 1526711215,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b0183d18f7911_18188917 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '265015b0183d16c4663_41930049';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <title>PHP300Framework Error!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAADZ0lEQVRoQ+2Yj5ENQRDGv4sAEbiLABEgAkSACLgIEAEiQASIABG4iwARIALq92q+V61vdnb2zd2pV7VddXV3Oz3d/X39Z2b3QHsuB3sev1YA/zuDawbWDAwysJbQIIHD29cMDFM4aOAiM3Ao6XqJ74ek74OxVrePALgp6YYkAr0j6aoknvXIiaRfkj4XYKeSeLZYlgAgwHuS7oeAFztsbDCgD5I+FoCz9nsAwO6TEviswaLwJSje7t2U9ADyumRp0kQLAEy/LCWyNAbbpaS+Lt2c9CmtF5IAdEZqAKjpN6VMdvEN+2QNeVRs7WIn76FfHudhkAHAOsFT77sKaX9aNr8q5berrbyPPgHENhsRQIutB42mokwI2CMTB2+LZ9YiGfwNSQ/L+rugm4PNunF968MAMPp+gqbfnRmBFabUrY6R6MxATLW2QyxTxN6lwQEA0m+NIHNNmz3SGacEvYOd2MAMAQRd2Haw9MgnSUehpvnfgh767ENMTuSYtSOckX47qiWBCfC8LFAaBmDdWIY4IptIZi4SAQB0XV4GFP0fSyJTCP6fVYI7xjljrnWCxjQzCfJcjwBwZLDZKYwCygERtKdVjcRI3BSAE5z/mah9P45pzrrccSgdhN+wb9Yy2Mgo7DPfDbY2rSJxtRLaOO0B0DqU4sSBRYIicORn6qtN05U1egVA7okMlruRq4Iyw1ZVCA6nXMpqkuvWjNFA9IMD8CC4FrJBkFFMhAOKmTUw9LGJbTcw09F9lWM8nWvieChNkQBTHH7YMmt5LEdG3bA99zDsum9q/jdNDCPc1a9UNPKhFHV80BAsf8OiM4TT6Divwb4bOL43OAQfju6vWvCcT4dmoTbG2BQPpVzTUxnpeX4e143tQWaHtRPPAH1I9QTXoxMzWxvNczbOXCW8gXKggSiV2MCtq8acs9p6zOzcGI/7KRuIrl7mrAjbgGAy+TWPZ616XAoiTh33wpwNCCX4f96t515oaMqpETvn8LzWmWCcMRH01nbPKIMhkOc70HkFOGXHV+1q4N7UA8C6cWwCqjZ2R0BR3wRLffPjg6xpcwmAbIhZzY8/q7De+wLvl35/VqHXLvyzylJ243cifwdaamNWfyQDs8YvQ2EFcBkst3ysGVgzMMjAWkKDBA5vXzMwTOGggb3PwF/kIbPOIIIdMQAAAABJRU5ErkJggg=="/>
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['Path']->value;?>
system/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['Path']->value;?>
system/css/zenburn.min.css" />
    <style>
        span{
            cursor: pointer;
        }
    </style>
</head>
<body>
<div style="padding: 48px 0;">
<div class="container">
    <?php if (!empty($_smarty_tpl->tpl_vars['Error']->value['type'])) {?>
	<div class="alert alert-danger">错误级别：<?php echo $_smarty_tpl->tpl_vars['Error']->value['type'];?>
</div>
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['Error']->value['file'])) {?>
	<div class="alert alert-danger">错误文件：<?php echo $_smarty_tpl->tpl_vars['Error']->value['file'];?>
</div>
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['Error']->value['message'])) {?>
	<div class="alert alert-danger">错误原因：<?php echo $_smarty_tpl->tpl_vars['Error']->value['message'];?>
</div>
    <?php }?>
    <?php if (!empty($_smarty_tpl->tpl_vars['Error']->value['line'])) {?>
	<div class="alert alert-danger">错误行数：<?php echo $_smarty_tpl->tpl_vars['Error']->value['line'];?>
</div>
    <?php }
if (!empty($_smarty_tpl->tpl_vars['Error']->value['code'])) {?>
<pre>
<span class="label label-default">区域预览</span>
<code class="php">
<?php echo $_smarty_tpl->tpl_vars['Error']->value['code'];?>

</code>
<span class="label label-info" data-toggle="modal" data-target="#myModal">$_SERVER</span>&nbsp;
</pre>
<?php }?>
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
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Server']->value, 'value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$__foreach_value_0_saved = $_smarty_tpl->tpl_vars['value'];
?>
                <b><?php echo $_smarty_tpl->tpl_vars['value']->key;?>
</b>  <font color='red'>>>></font>  <b><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</b><br />
                <?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
            </div>
        </div>
    </div>
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['Path']->value;?>
system/js/jquery.min.js" ><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['Path']->value;?>
system/js/bootstrap.min.js" ><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['Path']->value;?>
system/js/highlight.min.js" ><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    hljs.initHighlightingOnLoad();
<?php echo '</script'; ?>
>
</html><?php }
}
