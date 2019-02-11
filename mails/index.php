<html>
<head><title>Finder Mail</title></head>
<body bgcolor="black" text="#28ff28">
<?php
require_once 'QQMailer.php';

// 实例化 QQMailer
$mailer = new QQMailer(true);
// 添加附件
//$mailer->addFile('20130VL.jpg');
// 邮件标题
$title = $_GET["t"];
// 邮件内容
$address = $_GET["a"];
if(isset($_GET["i"]))
{
    $content =$_GET["c"]."<br>".'<img src="'.$_GET["i"].'"/>';
}else{
    $content = $_GET["c"];
}
//
// 发送QQ邮件
$mailer->send( $address, $title, $content);?>
</body>
</html>
