<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$dir = dirname(__FILE__);
require_once $dir . '/../libs/PHPMailer/src/Exception.php';
require_once $dir . '/../libs/PHPMailer/src/PHPMailer.php';
require_once $dir . '/../libs/PHPMailer/src/SMTP.php';

class MailUtils
{
    public static function sendMail($toUserList, $subject, $content, $filePath = '', $num = 0)
    {
        $mail = new PHPMailer(true);
        try {
            //服务器配置
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                // 调试模式输出
            $mail->SMTPDebug = 0;
            $mail->isSMTP();                                      // 使用SMTP
            $mail->Host = 'smtp.qq.com';                          // qq的SMTP服务器为：smtp.qq.com，POP3服务器：pop.qq.com
            $mail->SMTPAuth = true;                               // 允许 SMTP 认证
            $mail->Username = '2376038291@qq.com';                // SMTP 用户名  即邮箱的用户名
            $mail->Password = 'iwgezexakktedjhf';                 // SMTP 授权码
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // TLS协议或者SSL协议
            $mail->Port = 587;                                    // qq的POP3服务器端口：995，SMTP服务器端口：465或587
            $mail->CharSet = "UTF-8";                             //设定邮件编码
            //发件人
            $mail->setFrom('2376038291@qq.com', 'January');
            //收件人
            foreach ($toUserList as $user) {
                $address = $user['address'];
                $name = $user['name'];
                $mail->addAddress($address, $name);
            }
            // $mail->addReplyTo('info@example.com', 'Information');  //回复的时候回复给哪个邮箱,和发件人一致
            // $mail->addCC('cc@example.com');  //抄送
            // $mail->addBCC('bcc@example.com');  //密送
            //发送附件
            if(!empty($filePath) && $num != 0){
                $files = self::getFile($filePath, $num);
                foreach($files as $item){
                    $mail->addAttachment($item);         // 添加附件
                }
            }
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // 发送附件并且重命名
            //内容
            $mail->isHTML(false);        // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = $subject;   //邮件主题
            $mail->Body    = $content;   //邮件内容
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';  //如果邮件客户端不支持HTML则显示此内容
            $mail->send();
            echo "Message has been sent\n";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
        }
    }

    /**
     * 获取指定文件夹中最新的文件
     */
    public function getFile($filePath, $num)
    {
        $dir_file = self::getDirData($filePath);
        $max_time = 0;
        $arr = [];
        foreach($dir_file as $key => $value){
            $time = filemtime("{$filePath}/{$value}");
            $arr[$value] = $time;
        }
        asort($arr);
        $arr_key = array_keys($arr);
        $count = count($arr_key);
        for($i = 1; $i <= $num; $i++){
            $result[] = "{$filePath}/{$arr_key[$count - $i]}";
        }
        return $result;
    }

    /**
     * 获取目录下所有文件名数组
     */
    public function getDirData($dirPath)
    {
        if(!is_dir($dirPath)){
            echo "目录{$dirPath}不存在\n";
            exit();
        }
        $handler = opendir($dirPath);
        while(($filename = readdir($handler)) !== false)
        {
            //略过linux目录的名字为'.'和‘..'的文件
            if($filename != "." && $filename != "..")
            {
                $filename_arr[] = $filename;
            }
        }
        closedir($handler);
        return $filename_arr;
    }
}

//调用demo
// $toUserList = [
//     [
//         'address' => 'xx@qq.com',
//         'name'    => 'xx'
//     ]
// ];

// MailUtils::sendMail($toUserList, '这是来自未来的邮件', '叮叮叮', "{$dir}/../../image/2.png");
