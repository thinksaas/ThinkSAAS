<?php  
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
		$title = '反垃圾';
		include template('spam');
		break;
		
	case "get":
		
		$api = file_get_contents('http://www.thinksaas.cn/index.php?app=service&ac=spam&ts=api');
		
		$arrSpam = json_decode($api,true);
		
		$strSpam = '';
		$count = 1;
		if(is_array($arrSpam)){
			foreach ($arrSpam as $item) {
				if ($count==1) {
					$strSpam .= $item;
				} else {
					$strSpam .= '|'.$item;
				}
					$count++;
			}
		}
		
		fileWrite('data.php','plugins/pubs/wast_word',$strSpam);
		
		qiMsg('更新云垃圾词库成功！');
		
		break;

}