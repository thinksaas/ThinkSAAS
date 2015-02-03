<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


//资料库
$arrAlbum = $new['attach']->findAll('attach_album',array(
	'userid'=>$strUser['userid'],
),'addtime desc');


$title = '我的资料';
include template('my/index');