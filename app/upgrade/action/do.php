<?php 
defined('IN_TS') or die('Access Denied.');

$sql = trim($_POST['sql']);
$sqlFile = file_get_contents('upgrade/'.$sql.'.sql');
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

//处理2.1以下版本的logo问题
if($TS_CF['info']['version']=='2.1'){
	$new['upgrade']->create('system_options',array(
		'optionname'=>'logo',
		'optionvalue'=>'logo.gif',
	));
}
//更新系统缓存信息
$arrOptions = $new['upgrade']->findAll('system_options');
foreach ( $arrOptions as $item ) {
	$arrOption [$item ['optionname']] = $item ['optionvalue'];
}
fileWrite ( 'system_options.php', 'data', $arrOption );
$tsMySqlCache->set ( 'system_options', $arrOption );
$tsMySqlCache->file();

//清空升级文件
rmrf('upgrade/up.php');
rmrf('upgrade/'.$sql.'.sql');

//删除模板缓存
rmrf('cache/template');

//升级完成后跳转到首页
header("Location: ".SITE_URL);