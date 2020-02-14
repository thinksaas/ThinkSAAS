<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "join":
		
		
		$userid = intval($TS_USER['userid']);
		
		$locationid = intval($_POST['locationid']);
		
		if($userid==0){
			getJson('请登录后再加入！');
		}

		$arrTable = $TS_APP['table'];
		
		foreach($arrTable as $key=>$item){
            $new['location']->update($item,array(
                'userid'=>$userid,
            ),array(
                'locationid'=>$locationid,
            ));
        }
		
		getJson('加入成功！',1,2,tsUrl('location','show',array('id'=>$locationid)));
		
		break;
	
	case "exit":
		
		$userid = intval($TS_USER['userid']);
		
		$locationid = intval($_POST['locationid']);
		
		if($userid==0 || $locationid == 0){
			getJson('非法操作！');
		}
		
		$arrTable = $TS_APP['table'];
		
		foreach($arrTable as $key=>$item){
            $new['location']->update($item,array(
                'userid'=>$userid,
            ),array(
                'locationid'=>0,
            ));
        }
		
		getJson('退出成功！',1,2,tsUrl('location'));
		
		break;
	
}