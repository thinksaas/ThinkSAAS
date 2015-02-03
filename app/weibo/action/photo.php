<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = intval($TS_USER['user']['userid']);

if($userid==0){
	echo 0;exit;//请登录
}


if($_POST['token'] != $_SESSION['token']) {
	echo 1;exit;//非法操作
} 

$content = t($_POST['content']);

if($TS_USER['user']['isadmin']==0){
	//过滤内容开始
	aac('system')->antiWord($content);
	//过滤内容结束
}

$weiboid = $new['weibo']->create('weibo',array(
	'userid'=>$userid,
	'content'=>$content,
	'isaudit'=>0,
	'addtime'=>date('Y-m-d H:i:s'),
	'uptime'=>date('Y-m-d H:i:s'),
));

// 上传图片开始
$arrUpload = tsUpload ( $_FILES ['filedata'], $weiboid, 'weibo', array ('jpg','gif','png','jpeg' ) );
if ($arrUpload) {
	$new ['weibo']->update ( 'weibo', array (
			'weiboid' => $weiboid 
	), array (
			'path' => $arrUpload ['path'],
			'photo' => $arrUpload ['url'] 
	) );
	
	echo 3;exit;
	
}else{
	
	echo 2;exit;

}
