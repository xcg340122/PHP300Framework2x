<?php
/* Smarty version 3.1.32-dev-38, created on 2018-05-20 23:49:04
  from 'D:\GIT\PHP300Framework2x\Framework\Library\Process\Tpl\error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-38',
  'unifunc' => 'content_5b0198f0779470_61324245',
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
  'cache_lifetime' => '0',
),true)) {
function content_5b0198f0779470_61324245 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <title>PHP300Framework Error!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAADZ0lEQVRoQ+2Yj5ENQRDGv4sAEbiLABEgAkSACLgIEAEiQASIABG4iwARIALq92q+V61vdnb2zd2pV7VddXV3Oz3d/X39Z2b3QHsuB3sev1YA/zuDawbWDAwysJbQIIHD29cMDFM4aOAiM3Ao6XqJ74ek74OxVrePALgp6YYkAr0j6aoknvXIiaRfkj4XYKeSeLZYlgAgwHuS7oeAFztsbDCgD5I+FoCz9nsAwO6TEviswaLwJSje7t2U9ADyumRp0kQLAEy/LCWyNAbbpaS+Lt2c9CmtF5IAdEZqAKjpN6VMdvEN+2QNeVRs7WIn76FfHudhkAHAOsFT77sKaX9aNr8q5berrbyPPgHENhsRQIutB42mokwI2CMTB2+LZ9YiGfwNSQ/L+rugm4PNunF968MAMPp+gqbfnRmBFabUrY6R6MxATLW2QyxTxN6lwQEA0m+NIHNNmz3SGacEvYOd2MAMAQRd2Haw9MgnSUehpvnfgh767ENMTuSYtSOckX47qiWBCfC8LFAaBmDdWIY4IptIZi4SAQB0XV4GFP0fSyJTCP6fVYI7xjljrnWCxjQzCfJcjwBwZLDZKYwCygERtKdVjcRI3BSAE5z/mah9P45pzrrccSgdhN+wb9Yy2Mgo7DPfDbY2rSJxtRLaOO0B0DqU4sSBRYIicORn6qtN05U1egVA7okMlruRq4Iyw1ZVCA6nXMpqkuvWjNFA9IMD8CC4FrJBkFFMhAOKmTUw9LGJbTcw09F9lWM8nWvieChNkQBTHH7YMmt5LEdG3bA99zDsum9q/jdNDCPc1a9UNPKhFHV80BAsf8OiM4TT6Divwb4bOL43OAQfju6vWvCcT4dmoTbG2BQPpVzTUxnpeX4e143tQWaHtRPPAH1I9QTXoxMzWxvNczbOXCW8gXKggSiV2MCtq8acs9p6zOzcGI/7KRuIrl7mrAjbgGAy+TWPZ616XAoiTh33wpwNCCX4f96t515oaMqpETvn8LzWmWCcMRH01nbPKIMhkOc70HkFOGXHV+1q4N7UA8C6cWwCqjZ2R0BR3wRLffPjg6xpcwmAbIhZzY8/q7De+wLvl35/VqHXLvyzylJ243cifwdaamNWfyQDs8YvQ2EFcBkst3ysGVgzMMjAWkKDBA5vXzMwTOGggb3PwF/kIbPOIIIdMQAAAABJRU5ErkJggg=="/>
	<link rel="stylesheet" href="/Public/system/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/Public/system/css/zenburn.min.css" />
    <style>
        span{
            cursor: pointer;
        }
    </style>
</head>
<body>
<div style="padding: 48px 0;">
<div class="container">
            	<div class="alert alert-danger">错误原因：您请求的方法不存在</div>
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
                                <b>HTTP_HOST</b>  <font color='red'>>>></font>  <b>www.debug.com</b><br />
                                <b>HTTP_CONNECTION</b>  <font color='red'>>>></font>  <b>keep-alive</b><br />
                                <b>HTTP_USER_AGENT</b>  <font color='red'>>>></font>  <b>Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.26 Safari/537.36 Core/1.63.5221.400 QQBrowser/10.0.1125.400</b><br />
                                <b>HTTP_UPGRADE_INSECURE_REQUESTS</b>  <font color='red'>>>></font>  <b>1</b><br />
                                <b>HTTP_ACCEPT</b>  <font color='red'>>>></font>  <b>text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8</b><br />
                                <b>HTTP_ACCEPT_ENCODING</b>  <font color='red'>>>></font>  <b>gzip, deflate</b><br />
                                <b>HTTP_ACCEPT_LANGUAGE</b>  <font color='red'>>>></font>  <b>zh-CN,zh;q=0.9</b><br />
                                <b>HTTP_COOKIE</b>  <font color='red'>>>></font>  <b>pgv_pvi=7560587264; PHPSESSID=hsdaupaoebs3aba73q73ql71t1; Hm_lvt_ed38609fc79dd16e428d5a06610cfeb9=1526730181,1526821828; Hm_lpvt_ed38609fc79dd16e428d5a06610cfeb9=1526831329</b><br />
                                <b>PATH</b>  <font color='red'>>>></font>  <b>C:\windows\system32;C:\windows;C:\windows\System32\Wbem;C:\windows\System32\WindowsPowerShell\v1.0\;C:\Program Files (x86)\NVIDIA Corporation\PhysX\Common;C:\Program Files\dotnet\;C:\Program Files\TortoiseSVN\bin;D:\phpStudy\php70n;E:\Program Files\Git\cmd</b><br />
                                <b>SystemRoot</b>  <font color='red'>>>></font>  <b>C:\Windows</b><br />
                                <b>COMSPEC</b>  <font color='red'>>>></font>  <b>C:\Windows\system32\cmd.exe</b><br />
                                <b>PATHEXT</b>  <font color='red'>>>></font>  <b>.COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC</b><br />
                                <b>WINDIR</b>  <font color='red'>>>></font>  <b>C:\Windows</b><br />
                                <b>SERVER_SIGNATURE</b>  <font color='red'>>>></font>  <b></b><br />
                                <b>SERVER_SOFTWARE</b>  <font color='red'>>>></font>  <b>Apache/2.4.18 (Win32) OpenSSL/1.0.2e PHP/5.5.30</b><br />
                                <b>SERVER_NAME</b>  <font color='red'>>>></font>  <b>www.debug.com</b><br />
                                <b>SERVER_ADDR</b>  <font color='red'>>>></font>  <b>127.0.0.1</b><br />
                                <b>SERVER_PORT</b>  <font color='red'>>>></font>  <b>80</b><br />
                                <b>REMOTE_ADDR</b>  <font color='red'>>>></font>  <b>127.0.0.1</b><br />
                                <b>DOCUMENT_ROOT</b>  <font color='red'>>>></font>  <b>D:/GIT/PHP300Framework2x/Web</b><br />
                                <b>REQUEST_SCHEME</b>  <font color='red'>>>></font>  <b>http</b><br />
                                <b>CONTEXT_PREFIX</b>  <font color='red'>>>></font>  <b></b><br />
                                <b>CONTEXT_DOCUMENT_ROOT</b>  <font color='red'>>>></font>  <b>D:/GIT/PHP300Framework2x/Web</b><br />
                                <b>SERVER_ADMIN</b>  <font color='red'>>>></font>  <b>admin@phpStudy.net</b><br />
                                <b>SCRIPT_FILENAME</b>  <font color='red'>>>></font>  <b>D:/GIT/PHP300Framework2x/Web/index.php</b><br />
                                <b>REMOTE_PORT</b>  <font color='red'>>>></font>  <b>57324</b><br />
                                <b>GATEWAY_INTERFACE</b>  <font color='red'>>>></font>  <b>CGI/1.1</b><br />
                                <b>SERVER_PROTOCOL</b>  <font color='red'>>>></font>  <b>HTTP/1.1</b><br />
                                <b>REQUEST_METHOD</b>  <font color='red'>>>></font>  <b>GET</b><br />
                                <b>QUERY_STRING</b>  <font color='red'>>>></font>  <b></b><br />
                                <b>REQUEST_URI</b>  <font color='red'>>>></font>  <b>/index.php/home/index1</b><br />
                                <b>SCRIPT_NAME</b>  <font color='red'>>>></font>  <b>/index.php</b><br />
                                <b>PATH_INFO</b>  <font color='red'>>>></font>  <b>/home/index1</b><br />
                                <b>PATH_TRANSLATED</b>  <font color='red'>>>></font>  <b>redirect:\index.php\home\index1\index1</b><br />
                                <b>PHP_SELF</b>  <font color='red'>>>></font>  <b>/index.php/home/index1</b><br />
                                <b>REQUEST_TIME_FLOAT</b>  <font color='red'>>>></font>  <b>1526831344.454</b><br />
                                <b>REQUEST_TIME</b>  <font color='red'>>>></font>  <b>1526831344</b><br />
                            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/Public/system/js/jquery.min.js" ></script>
<script type="text/javascript" src="/Public/system/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="/Public/system/js/highlight.min.js" ></script>
<script>
    hljs.initHighlightingOnLoad();
</script>
</html><?php }
}
