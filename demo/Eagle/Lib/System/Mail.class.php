<?php

namespace Lib\System;

use Lib\Plugin\PHPMailer\PHPMailer;

class Mail {
    public static function send( $subject, $content ) {
        try {
        	$mail = new PHPMailer(true); //New instance, with exceptions enabled
        	$mail->IsSMTP();                           // tell the class to use SMTP
        	$mail->CharSet    = 'UTF-8';               //设置邮件的字符编码，这很重要，不然中文乱码
        	$mail->SMTPAuth   = true;                  // enable SMTP authentication
        	$mail->Port       = 25;                    // set the SMTP server port
        	$mail->Host       = "smtp.163.com";        // SMTP server
        	$mail->Username   = "kreagle3@163.com";    // SMTP server username
        	$mail->Password   = "*********3";             // SMTP server password
        
        	//$mail->IsSendmail();  // tell the class to use Sendmail；如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示 
        
        	$mail->AddReplyTo("kreagle3@163.com","王亚坤");
        
        	$mail->From       = "kreagle3@163.com";
        	$mail->FromName   = "王：这是First Name"; //发件人在已发送里面可以看到；收件人右下角出现收到邮件提示时可以看到
        
        	$to = "876400276@qq.com";
        
        	$mail->AddAddress($to);
        
        	$mail->Subject  = $subject;
        
        	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test； 当邮件不支持html时备用显示，可以省略 
        	$mail->WordWrap   = 80; // set word wrap ，设置每行字符串的长度 
            
        	/* 发送的内容是纯字符串时  开始 */
        	$mail->Body = $content;
        	/* 发送的内容是纯字符串时 结束 */
        	
        	/* 发送的内容是html文件模板时  开始 */
        	//$body             = file_get_contents('./data/mailTpl/contents.html');
        	//$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
        	//$mail->MsgHTML($body);
        	//$mail->IsHTML(true); // send as HTML
        	/* 发送的内容是html文件模板时  结束*/
        	
        	$mail->Send();
        	
        	return '邮件已发送';
        } catch (phpmailerException $e) {
        	return $e->errorMessage();
        }
        
      
    }
}