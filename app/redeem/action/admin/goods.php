<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){
	case "list":
	
		$arrGoods = $new['redeem']->findAll('redeem_goods',null,'addtime desc');
	
		include template('admin/goods_list');
		break;
		
	case "add":
		
		$arrCate = $new['redeem']->findAll('redeem_cate');
		include template('admin/goods_add');
		break;
		
	case "adddo":
	
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
			
			
			header('Location: '.SITE_URL.'index.php?app=redeem&ac=admin&mg=goods&ts=list');
			exit;
		
		}else{
			
			tsNotice('标题，内容和数量都不能为空！');
			
		}
	
		break;
		
	case "edit":
		
		$goodsid = intval($_GET['goodsid']);
		$arrCate = $new['redeem']->findAll('redeem_cate');
		$strGoods = $new['redeem']->find('redeem_goods',array(
			'goodsid'=>$goodsid,
		));
		
		include template('admin/goods_edit');
		break;
		
	case "editdo":
		
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
			'`return`'=>$return,
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
		
		
		header('Location: '.SITE_URL.'index.php?app=redeem&ac=admin&mg=goods&ts=list');
		
		break;
		
	case "delete":
	
		$goodsid = intval($_GET['goodsid']);

		$strGoods = $new['redeem']->find('redeem_goods',array(
			'goodsid'=>$goodsid,
		));


		unlink('uploadfile/redeem/'.$strGoods['photo']);

		$new['redeem']->delete('redeem_goods',array(
			'goodsid'=>$goodsid,
		));

		qiMsg('删除成功！');
	
		break;
}