<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user') -> isLogin();


switch ($ts) {
	case "" :
		if ($TS_APP['allowpost'] == 0 && $TS_USER['isadmin'] == 0) {
			tsNotice('系统设置不允许会员发文章！');
		}

		$cateid = intval($_GET['cateid']);

		$title = '发布文章';
		include  template('add');
		break;

	case "do" :
		if ($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}

		$cateid = intval($_POST['cateid']);
		$title = trim($_POST['title']);
		$content = tsClean($_POST['content']);
		$tag = tsClean($_POST['tag']);
		$addtime = date('Y-m-d H:i:s');

		if (intval($TS_USER['isadmin']) == 0) {
			// 过滤内容开始
			aac('system') -> antiWord($title);
			aac('system') -> antiWord($content);
			aac('system') -> antiWord($tag);
			// 过滤内容结束
		}

		if ($title == '' || $content == '')
			tsNotice("标题和内容都不能为空！");

		//1审核后显示0不审核
		if ($TS_APP['isaudit'] == 1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}

		$articleid = $new['article'] -> create('article', array('userid' => $userid, 'locationid' => aac('user') -> getLocationId($userid), 'cateid' => $cateid, 'title' => $title, 'content' => $content, 'isaudit' => $isaudit, 'addtime' => date('Y-m-d H:i:s')));

		// 上传帖子图片开始
		$arrUpload = tsUpload($_FILES['photo'], $articleid, 'article', array('jpg', 'gif', 'png', 'jpeg'));
		if ($arrUpload) {
			$new['article'] -> update('article', array('articleid' => $articleid), array('path' => $arrUpload['path'], 'photo' => $arrUpload['url']));
		}
		// 上传帖子图片结束

		// 处理标签
		aac('tag') -> addTag('article', 'articleid', $articleid, $tag);

		// 对积分进行处理
		aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts']);

		header("Location: " . tsUrl('article', 'show', array('id' => $articleid)));

		break;
}
