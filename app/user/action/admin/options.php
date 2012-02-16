<?php
	/* 
	 * 配置选项
	 */	
	
	switch($ts){
		//配置
		case "":
			$arrOptions = $db->findAll("select * from ".dbprefix."user_options");
			foreach($arrOptions as $item){
				$strOption[$item['optionname']] = $item['optionvalue'];
			}
			include template("admin/options");
			break;
		//配置执行
		case "do":
			$arrData = array(
				'appname' => trim($_POST['appname']),
				'appdesc' => trim($_POST['appdesc']),
				'isenable' => trim($_POST['isenable']),
				'isregister' => trim($_POST['isregister']),
				'isvalidate' => trim($_POST['isvalidate']),
				'isgroup'	=> trim($_POST['isgroup']),
			);
			foreach ($arrData as $key => $val){

				$db->update('user_options',array(
					'optionvalue'=>$val,
				),array(
					'optionname'=>$key,
				));
				
			}
			
			//更新缓存
			$arrOptions = $db->findAll("select optionname,optionvalue from ".dbprefix."user_options");
			foreach($arrOptions as $item){
				$arrOption[$item['optionname']] = $item['optionvalue'];
			}
			
			fileWrite('user_options.php','data',$arrOption);
			
			qiMsg("用户APP配置成功！");
			
			break;
	}