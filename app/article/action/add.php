<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$userid = aac ( 'user' )->isLogin ();

switch ($ts) {
	case "" :
		
		if ($TS_APP ['options'] ['allowpost'] == 0 && $TS_USER ['user'] ['isadmin'] == 0) {
			tsNotice ( '系统设置不允许会员发文章！' );
		}
		
		$cateid = intval ( $_GET ['cateid'] );
		
		if ($arrCate == '') {
			tsNotice ( '请创建分类后再写文章！' );
		}
		
		$title = '发布文章';
		include template ( 'add' );
		break;
	
	case "do" :
		
		if ($_POST ['token'] != $_SESSION ['token']) {
			tsNotice ( '非法操作！' );
		}
		
		$cateid = intval ( $_POST ['cateid'] );
		$title = tsClean ( $_POST ['title'] );
		$content = tsClean ( $_POST ['content'] );
		$tag = tsClean ( $_POST ['tag'] );
		$addtime = date ( 'Y-m-d H:i:s' );
		
		if (intval ( $TS_USER ['user'] ['isadmin'] ) == 0) {
			// 过滤内容开始
			aac ( 'system' )->antiWord ( $title );
			aac ( 'system' )->antiWord ( $content );
			aac ( 'system' )->antiWord ( $tag );
			// 过滤内容结束
		}
		
		if ($title == '' || $content == '')
			tsNotice ( "标题和内容都不能为空！" );
		
		if ($TS_USER ['user'] ['isadmin'] == 1) {
			$isaudit = 0;
		} else {
			$isaudit = 1;
		}
		
		$articleid = $new ['article']->create ( 'article', array (
				'userid' => $userid,
				'cateid' => $cateid,
				'title' => $title,
				'content' => $content,
				'isaudit' => $isaudit,
				'addtime' => date ( 'Y-m-d H:i:s' ) 
		) );
		
		// 上传帖子图片开始
		$arrUpload = tsUpload ( $_FILES ['picfile'], $articleid, 'article', array (
				'jpg',
				'gif',
				'png',
				'jpeg' 
		) );
		if ($arrUpload) {
			$new ['article']->update ( 'article', array (
					'articleid' => $articleid 
			), array (
					'path' => $arrUpload ['path'],
					'photo' => $arrUpload ['url'] 
			) );
		}
		// 上传帖子图片结束
		
		// 处理标签
		aac ( 'tag' )->addTag ( 'article', 'articleid', $articleid, $tag );
		
		// 对积分进行处理
		aac ( 'user' )->doScore ( $app, $ac, $ts );
		
		header ( "Location: " . tsUrl ( 'article', 'show', array (
				'id' => $articleid 
		) ) );
		
		break;
}