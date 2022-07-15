<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//APP配置选项
	case "options":
		$arrData = array(
			'appname' => tsTrim($_POST['appname']),
			'appdesc' => tsTrim($_POST['appdesc']),
			'isenable' => tsTrim($_POST['isenable']),
			'mailhost' => tsTrim($_POST['mailhost']),
			'ssl' => tsIntval($_POST['ssl']),
			'mailport' => tsTrim($_POST['mailport']),
			'mailuser' => tsTrim($_POST['mailuser']),
			'mailpwd' => tsTrim($_POST['mailpwd']),
		);

		//先清空数据 
		$db->query("TRUNCATE TABLE `".dbprefix."mail_options`");
		
		foreach($arrData as $key=>$item){
			
			$optionname = $key;
			$optionvalue = $item;
			
			$new['mail']->create('mail_options',array(
			
				'optionname'=>$optionname,
				'optionvalue'=>$optionvalue,
			
			));
		
		}
		
		//更新缓存
		$arrOptions = $new['mail']->findAll('mail_options',null,null,'optionname,optionvalue');
		foreach($arrOptions as $item){
			$arrOption[$item['optionname']] = $item['optionvalue'];
		}
		
		fileWrite('mail_options.php','data',$arrOption);
		$tsMySqlCache->set('mail_options',$arrOption);
		
		qiMsg("邮件配置更新成功，并重置了缓存文件^_^");
		
		break;


    case "sms":

        $arrData = array(
            'sms_server' => tsTrim($_POST['sms_server']),
            'sms_appid' => tsTrim($_POST['sms_appid']),
            'sms_appkey' => tsTrim($_POST['sms_appkey']),
            'sms_tpid' => tsTrim($_POST['sms_tpid']),
            'sms_sign' => tsTrim($_POST['sms_sign']),
        );

        //更新缓存
        fileWrite('sms_options.php','data',$arrData);
        $GLOBALS['tsMySqlCache']->set('sms_options',$arrData);

        qiMsg("短信配置更新成功，并重置了缓存文件^_^");

        break;
		 
}