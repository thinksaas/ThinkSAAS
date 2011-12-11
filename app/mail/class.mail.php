<?php 
/*
 *模型：邮件
 *class.mail.php
 *By QINIAO
 */
 
class mail{

	var $db;

	function mail($dbhandle){
		$this->db = $dbhandle;
	}
	
	//发送邮件
	function postMail($sendmail,$subject,$content){
		$options = fileRead('options.php','data','mail');
		date_default_timezone_set('Asia/Shanghai');
		require_once THINKSAAS.'/PHPMailer/class.phpmailer.php';
		$mail = new PHPMailer();

		//邮件配置
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP(); 
		$mail->SMTPDebug  	= 1;
		$mail->SMTPAuth   	= true;           
		$mail->Host       		= $options['mailhost']; 
		$mail->Port       		= $options['mailport']; 
		$mail->Username   	= $options['mailuser']; 
		$mail->Password   	= $options['mailpwd']; 

		//POST过来的信息
		$frommail		= $options['mailuser'];
		$fromname	= 'ThinkSAAS';
		$replymail		= $options['mailuser'];
		$replyname	= 'ThinkSAAS';
		$sendname	= '';

		if(empty($frommail) || empty($subject) || empty($content) || empty($sendmail)){
			return '0';
		}else{

			//邮件发送
			$mail->SetFrom($frommail, $fromname);
			$mail->AddReplyTo($replymail,$replyname);
			$mail->Subject    = $subject;
			$mail->AltBody    = "要查看邮件，请使用HTML兼容的电子邮件阅读器!"; 
			$mail->MsgHTML(eregi_replace("[\]",'',$content));
			$mail->AddAddress($sendmail, $sendname);
			$mail->Send();
			
			return '1';
			
		}
	}

}