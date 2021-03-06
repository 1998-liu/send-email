<?php

//处理跨域
header('Access-Control-Allow-Origin:*');  
header('Access-Control-Allow-Headers:Content-Type,dataType');

$dir = dirname(__FILE__);
require_once $dir . '/utils/MailUtils.php';

if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    return true;
}
//收信人，邮件主题，邮件内容，附件数量，附件地址
$receiver = $_GET['receiver'];
$subject = $_GET['subject'];
$body = $_GET['body'];
$num = $_GET['num'];
$url = $_GET['url'];
$ip = $_SERVER['REMOTE_ADDR'];
sendEmail($ip, $receiver, $subject, $body, $num, $url);

/**
 * 发送邮件
 */
function sendEmail($ip, $receiver, $subject, $body, $num, $url)
{
    if(empty($receiver)){
        echo 'receiver is empty';
        exit();
    }
    $receiver_arr = explode(',', $receiver);
    foreach($receiver_arr as $value){
        $toUserList[] = [
            'address' => $value,
            'name'    => ''
        ];
    }
    MailUtils::sendMail($ip, $toUserList, $subject, $body, $url, $num);
}

?>
