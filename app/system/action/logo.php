<?php
defined('IN_TS') or die('Access Denied.');



$dest_dir = 'uploadfile/logo';

createFolders ( $dest_dir );

$arrType = explode ( '.', strtolower ( $_FILES ['filedata'] ['name'] ) ); // 转小写一下

$type = array_pop ( $arrType );

if (in_array ( $type, array('jpg','jpeg','gif','png') )) {
	
	$name =  'logo.' . $type;
	
	$dest = $dest_dir . '/' . $name;
	
	// 先删除
	unlink ( $dest );
	// 后上传
	move_uploaded_file ( $_FILES ['filedata'] ['tmp_name'], mb_convert_encoding ( $dest, "gb2312", "UTF-8" ) );
	
	chmod ( $dest, 0777 );
	
	$new['system']->delete('system_options',array(
		'optionname'=>'logo',
	));
	$new['system']->create('system_options',array(
		'optionname'=>'logo',
		'optionvalue'=>$name,
	));
	
	$arrOptions = $new['system']->findAll('system_options',null,null,'optionname,optionvalue');
	foreach($arrOptions as $item){
		$arrOption[$item['optionname']] = $item['optionvalue'];
	}
	
	fileWrite('system_options.php','data',$arrOption);
	$tsMySqlCache->set('system_options',$arrOption);
	
	echo SITE_URL.$dest.'?v='.rand();
	
}