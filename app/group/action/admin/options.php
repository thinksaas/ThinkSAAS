<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//基本配置
	case "":

        $strOption = getAppOptions('group');
		
		include template("admin/options");
		
		break;
		
	case "do":

        $arrOption = $_POST['option'];

        #更新app配置选项
        upAppOptions('group',$arrOption);

        #更新app导航和我的导航
        upAppNav('group',$arrOption['appname']);
		
		qiMsg('修改成功！');
	
		break;
}