<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	//基本配置
	case "":


		$arrSearch = array(
			'group'=>'小组',
			'topic'=>'帖子',
			'user'=>'用户',
			'article'=>'文章',
		);


        $strOption = getAppOptions('search');
		
		include template("admin/options");
		
		break;
		
	case "do":

        $arrOption = $_POST['option'];

	    #更新app配置选项
        upAppOptions('search',$arrOption);

        #更新app导航和我的导航
        upAppNav('search',$arrOption['appname']);
		
		qiMsg('修改成功！');
	
		break;
}