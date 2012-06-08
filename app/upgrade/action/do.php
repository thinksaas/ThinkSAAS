<?php 

defined('IN_TS') or die('Access Denied.');

//升级数据库

$sql = trim($_POST['sql']);

$sqlFile = file_get_contents('update/'.$sql.'.sql');
$sqlFile = str_replace('ts_',dbprefix,$sqlFile);
$array_sql = preg_split("/;[\r\n]/", $sqlFile);

foreach($array_sql as $item){
	$item = trim($item);
	if ($item){
		if (strstr($item, 'CREATE TABLE')){
			preg_match('/CREATE TABLE ([^ ]*)/', $item, $matches);
			$ret = $db->query($item);
		} else {
			$ret = $db->query($item);
		}
	}
}

//////////////////////////////////此处向下不用修改////////////////////////////////////

//更新系统缓存信息
$arrOptions = $db->fetch_all_assoc("select optionname,optionvalue from ".dbprefix."system_options");
foreach($arrOptions as $item){
		$arrOption[$item['optionname']] = $item['optionvalue'];
}

fileWrite('system_options.php','data',$arrOption);

//清空升级文件
rmrf('update');

//删除模板缓存
rmrf('cache/template');

//升级完成后跳转到首页
header("Location: ".SITE_URL);