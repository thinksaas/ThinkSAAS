<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "list":

        $page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=system&ac=logs&ts=list&page=';
		$lstart = $page*20-20;
		$arrLogs = $new['system']->findAll('logs',null,'addtime desc',null,$lstart.',20');
		
		$logsNum = $new['system']->findCount('logs');
		$pageUrl = pagination($logsNum, 20, $page, $url);
		
		include template('logs_list');

    break;

    case "show":

        $logid = tsIntval($_GET['logid']);

        $strLog = $new['system']->find('logs',array(
            'logid'=>$logid,
        ));

        include template('logs_show');

    break;

    case "delete":

        $logid = tsIntval($_GET['logid']);

        $new['system']->delete('logs',array(
            'logid'=>$logid,
        ));

        qiMsg('删除成功！');
        
    break;

    case "clean":
        
        $db->query("TRUNCATE `".dbprefix."logs`;");

        qiMsg('操作成功！');

    break;

}