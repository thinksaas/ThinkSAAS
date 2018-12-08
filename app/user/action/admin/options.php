<?php
defined('IN_TS') or die('Access Denied.');

	
	switch($ts){
		//配置
		case "":
			$arrOptions = $new['user']->findAll('user_options');
			
			foreach($arrOptions as $item){
				$strOption[$item['optionname']] = $item['optionvalue'];
			}
			include template("admin/options");
			break;
		//配置执行
		case "do":
		
			//先清空数据 
			$db->query("TRUNCATE TABLE `".dbprefix."user_options`");
		
			foreach($_POST['option'] as $key=>$item){
				
				$optionname = $key;
				$optionvalue = trim($item);
				
				$new['user']->create('user_options',array(
				
					'optionname'=>$optionname,
					'optionvalue'=>$optionvalue,
				
				));
			
			}
			
			$arrOptions = $new['user']->findAll('user_options',null,null,'optionname,optionvalue');
			foreach($arrOptions as $item){
				$arrOption[$item['optionname']] = $item['optionvalue'];
			}

            fileWrite('user_options.php','data',$arrOption);
            $GLOBALS['tsMySqlCache']->set('user_options',$arrOption);



            //更新APP导航名称
            if($arrOption['appname']){
                $appkey = 'user';
                $appname = $arrOption['appname'];
                $arrNav = include 'data/system_appnav.php';
                if(is_array($arrNav)){
                    $arrNav[$appkey] = $appname;
                }else{
                    $arrNav = array(
                        $appkey=>$appname,
                    );
                }
                fileWrite('system_appnav.php','data',$arrNav);
                $GLOBALS['tsMySqlCache']->set('system_appnav',$arrNav);
            }

			
			qiMsg("用户APP配置成功！");
			
			break;
	}