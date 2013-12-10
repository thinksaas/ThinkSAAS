<?php 
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

if(intval($TS_USER['user']['isadmin'])==0){
	tsNotice('非法操作！');
}

switch($ts){

	case "":
	
		$title = '添加产品';
		include template('add');
	
		break;
		
	case "do":
	
		$cateid = intval($_POST['cateid']);
		$title = trim($_POST['title']);
		$content = tsClean($_POST['content']);
		$nums = intval($_POST['nums']);
		$scores = intval($_POST['scores']);
		$return = intval($_POST['return']);
		
		if($title && $content && $nums){
			
			aac('system')->antiWord($title);
			aac('system')->antiWord($content);
			
			$goodsid = $new['redeem']->create('redeem_goods',array(
				'cateid'=>$cateid,
				'title'=>$title,
				'content'=>$content,
				'nums'=>$nums,
				'scores'=>$scores,
				'`return`'=>$return,
				'addtime'=>date('Y-m-d H:i:s')
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
			exit;
		
		}else{
			
			tsNotice('标题，内容和数量都不能为空！');
			
		}
	
		break;

}