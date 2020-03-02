<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//基本配置
	case "":
        $strOption = getAppOptions('pubs');
		
		include template("admin/options");
		
		break;
		
	case "do":

        $arrOption = $_POST['option'];

	    #更新app配置选项
        upAppOptions('pubs',$arrOption);
		
		qiMsg('修改成功！');
	
		break;
}