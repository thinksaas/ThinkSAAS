<?php 
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

if(intval($TS_USER['user']['isadmin'])==0){
	tsNotice('非法操作！');
}

switch($ts){

	case "":
		$goodsid = intval($_GET['goodsid']);
		
		$strGoods = $new['redeem']->find('redeem_goods',array(
			'goodsid'=>$goodsid,
		));
	
		$title = '编辑产品';
		include template('edit');
	
		break;
		
	case "do":
	
		$goodsid = intval($_POST['goodsid']);
		$cateid = intval($_POST['cateid']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$nums = intval($_POST['nums']);
		$scores = intval($_POST['scores']);
		$return = intval($_POST['return']);
		
		$new['redeem']->update('redeem_goods',array(
			'goodsid'=>$goodsid,
		),array(
			'cateid'=>$cateid,
			'title'=>$title,
			'content'=>$content,
			'nums'=>$nums,
			'scores'=>$scores,
			'return'=>$return,
		));
		
		$arrUpload = tsUpload($_FILES['photo'], $goodsid, 'redeem', array('jpg', 'gif', 'png','jpeg'));
		if ($arrUpload) {
			$new['redeem'] -> update('redeem_goods', array(
				'goodsid' => $goodsid,
			), array(
				'path' => $arrUpload['path'],
				'photo' => $arrUpload['url'],
			));
		} 
		
		
		header('Location: '.tsUrl('redeem','goods',array('id'=>$goodsid)));
	
		break;

}