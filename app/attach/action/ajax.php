<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
switch ($ts) {
	
	// 添加附件
	case "add" :
		
		include template ( "ajax_add" );
		break;
	
	case "flash" :
		
		include template ( "ajax_flash" );
		break;
	
	// Flash上传
	case "flash_do" :
		
		$userid = intval ( $_GET ['userid'] );
		if ($userid == '0') {
			echo '00000';
			exit ();
		}
		
		$attachid = $new ['attach']->create ( 'attach', array (
				'userid' => $userid,
				'addtime' => time () 
		) );
		
		// 上传
		$arrUpload = tsUpload ( $_FILES ['Filedata'], $attachid, 'attach', array (
				'jpg',
				'gif',
				'png',
				'rar',
				'zip',
				'doc',
				'ppt',
				'txt' 
		) );
		
		if ($arrUpload) {
			
			$new ['attach']->update ( 'attach', array (
					'attachid' => $attachid 
			), array (
					'attachname' => t ( $arrUpload ['name'] ),
					'attachtype' => t ( $arrUpload ['type'] ),
					'attachurl' => $arrUpload ['url'],
					'attachsize' => $arrUpload ['size'] 
			) );
		}
		
		echo $attachid;
		
		break;
	
	// 我的附件列表
	case "my" :
		// 用户是否登录
		$userid = aac ( 'user' )->isLogin ();
		
		$page = isset ( $_GET ['page'] ) ? $_GET ['page'] : '1';
		$url = tsUrl ( 'attach', 'ajax', array (
				'ts' => 'my',
				'page' => '' 
		) );
		$lstart = $page * 12 - 12;
		
		$attachNum = $new ['attach']->findCount ( 'attach', array (
				'userid' => $userid 
		) );
		
		$pageUrl = pagination ( $attachNum, 12, $page, $url );
		
		$arrAttach = $new ['attach']->findAll ( 'attach', array (
				'userid' => $userid 
		), 'addtime desc', null, $lstart . ',12' );
		
		include template ( "ajax_my" );
		break;
	
	// 下载
	case "down" :
		
		$attachid = intval ( $_GET ['attachid'] );
		
		$strAttach = $new ['attach']->find ( 'attach', array (
				'attachid' => $attachid 
		) );
		
		if (! is_file ( 'uploadfile/attach/' . $strAttach ['attachurl'] )) {
			tsNotice ( '文件已经不存在！' );
		}
		
		$new ['attach']->update ( 'attach', array (
				'attachid' => $strAttach ['attachid'] 
		), array (
				'count_down' => $strAttach ['count_down'] + 1 
		) );
		
		header ( "Location: " . SITE_URL . 'uploadfile/attach/' . $strAttach ['attachurl'] );
		
		break;
	
	// 删除附件
	case "delete" :
		$userid = aac ( 'user' )->isLogin ();
		
		$attachid = intval ( $_GET ['attachid'] );
		
		$strAttach = $new ['attach']->find ( 'attach', array (
				'attachid' => $attachid,
				'userid' => $userid 
		) );
		
		if ($strAttach) {
			unlink ( 'uploadfile/attach/' . $strAttach ['attachurl'] );
			$new ['attach']->delete ( 'attach', array (
					'attachid' => $attachid 
			) );
		}
		
		header ( 'Location: ' . tsUrl ( 'attach', 'ajax', array (
				'ts' => 'my' 
		) ) );
		
		break;
}
