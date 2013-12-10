<?php 
defined('IN_TS') or die('Access Denied.');
switch($ts){
	case "":
		
		//输出备份文件
		$arrSqlFile = tsScanDir('data/baksql','file');
		
		include template('sql');
		break;
		
	//优化
	case "optimize":
		$arrTables = $db->fetch_all_assoc("SHOW TABLES");
		foreach($arrTables as $key=>$item){
			$db->query("OPTIMIZE TABLE `".$item."` ");
		}
		qiMsg('优化数据库成功！');
		break;
		
	//备份导出
	case "export":
	
		require_once 'thinksaas/DbManage.php';
		$bakdb = new DBManage ( $TS_DB['host'].':'.$TS_DB['port'], $TS_DB['user'], $TS_DB['pwd'], $TS_DB['name'], 'utf8' );
		$bakdb->backup ('','data/baksql/');
		
		qiMsg('数据库备份完毕！');
	
		break;
		
	//恢复导入
	case "import":
		$sql = tsFilter($_GET['sql']);
		
		require_once 'thinksaas/DbManage.php';
		$bakdb = new DBManage ( $TS_DB['host'].':'.$TS_DB['port'], $TS_DB['user'], $TS_DB['pwd'], $TS_DB['name'], 'utf8' );
		$bakdb->restore ('data/baksql/'.$sql);
		
		qiMsg('数据库恢复成功！如果有其他卷文件请一同恢复');
	
		break;
		
	case "delete":
		$sql = tsFilter($_GET['sql']);
		rmrf('data/baksql/'.$sql);
		qiMsg('删除成功！如果有其他卷文件请一同删除!');
		break;
		
}