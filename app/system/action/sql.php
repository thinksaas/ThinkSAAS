<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		$arrTables = $db->fetch_all_assoc("SHOW TABLES");
		foreach($arrTables as $key=>$item){
			$arrTable[] = $item['Tables_in_'.$TS_DB['name']];
		}
		include template('sql');
		break;
		
	//优化
	case "optimize":
		
		$table = trim($_GET['table']);
		
		$db->query("OPTIMIZE TABLE `".$table."` ");
		
		qiMsg('优化数据表'.$table.'成功！');
		
		break;
}