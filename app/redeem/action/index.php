<?php
defined('IN_TS') or die('Access Denied.');

//首页
$cateid = intval($_GET['cateid']);

$where = null;

if($cateid){
	$where = array(
		'cateid'=>$cateid,
	);
}

$arrGoods = $new['redeem']->findAll('redeem_goods',$where,'addtime desc');

$sitekey = $TS_APP['options']['appkey'];
$sitedesc = $TS_APP['options']['appdesc'];
$title = '积分兑换';
include template("index");