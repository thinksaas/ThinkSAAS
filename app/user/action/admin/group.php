<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	
	//用户组列表
	case "list":
		
		$arrGroup = $new['user']->findAll('user_group',null,'ugid asc');
		
		include template('admin/group_list');
	break;

    //创建用户组
    case "add":

        $ugname = tsTrim($_POST['ugname']);
        $uginfo = tsTrim($_POST['uginfo']);

        if($ugname && $uginfo){
            $new['user']->create('user_group',array(
                'ugname'=>$ugname,
                'uginfo'=>$uginfo,
            ));
        }

        header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=group&ts=list');

    break;

    case "edit":

        $ugid = tsIntval($_POST['ugid']);
        $ugname = tsTrim($_POST['ugname']);
        $uginfo = tsTrim($_POST['uginfo']);

        if(in_array($ugid,array(1,2,3,4))) qiMsg('非法操作！');

        $new['user']->update('user_group',array(
            'ugid'=>$ugid,
        ),array(
            'ugname'=>$ugname,
            'uginfo'=>$uginfo,
        ));

        header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=group&ts=list');

        break;

    //删除用户组
    case "delete":
        $ugid = tsIntval($_GET['ugid']);

        if(in_array($ugid,array(1,2,3,4))){
            qiMsg('非法操作！');
        }

        $new['user']->delete('user_group',array(
            'ugid'=>$ugid,
        ));

        #降为普通用户
        $new['user']->update('user_info',array(
            'ugid'=>$ugid,
        ),array(
            'ugid'=>3,
        ));

        header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=group&ts=list');

    break;
    
}