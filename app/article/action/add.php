<?php
defined('IN_TS') or die('Access Denied.');

//判断用户登录
$userid = aac('user') -> isLogin();

//判断用户是否存在
if(aac('user')->isUser($userid)==false) tsNotice('不好意思，用户不存在！');

//判断发布者状态
if(aac('user')->isPublisher()==false) tsNotice('不好意思，你还没有权限发布内容！');

//发布时间限制
if(aac('system')->pubTime()==false) tsNotice('不好意思，当前时间不允许发布内容！');


switch ($ts) {
	case "" :
		if ($TS_APP['allowpost'] == 0 && $TS_USER['isadmin'] == 0) {
			tsNotice('系统设置不允许会员发文章！');
		}

		$cateid = intval($_GET['cateid']);


        foreach ($arrCate as $key=>$item){
            $arrCate[$key]['two'] = $new['article']->findAll('article_cate',array(
                'referid'=>$item['cateid'],
            ));
        }


		$title = '发布文章';
		include  template('add');
		break;

	case "do" :


		$cateid = intval($_POST['cateid']);
		$cateid2 = intval($_POST['cateid2']);

		if($cateid2) $cateid = $cateid2;
        
		$title = trim($_POST['title']);
		$content = tsClean($_POST['content']);
		$content2 = emptyText($_POST['content']);
		$gaiyao = trim($_POST['gaiyao']);
		$tag = tsClean($_POST['tag']);
		$addtime = date('Y-m-d H:i:s');

		if (intval($TS_USER['isadmin']) == 0) {
			// 过滤内容开始
			aac('system') -> antiWord($title);
			aac('system') -> antiWord($content);
			aac('system') -> antiWord($tag);
			// 过滤内容结束
		}

		if ($title == '' || $content2 == '')
			tsNotice("标题和内容都不能为空！");

        $isTitle = $new['article']->findCount('article',array(
            'title'=>$title,
        ));

        if($isTitle){
            tsNotice("相同标题的文章已经存在！");
        }

        if($gaiyao){
            $gaiyao = cututf8($gaiyao,0,100);
        }else{
            $gaiyao = cututf8(t(tsDecode($content)),0,100);
        }

		//1审核后显示0不审核
		if ($TS_APP['isaudit'] == 1) {
			$isaudit = 1;
		} else {
			$isaudit = 0;
		}

        $articleid = $new['article'] -> create('article', array(
            'userid' => $userid,
            'locationid' => aac('user') -> getLocationId($userid),
            'cateid' => $cateid,
            'title' => $title,
            'content' => $content,
            'gaiyao' => $gaiyao,
            'isaudit' => $isaudit,
            'addtime' => date('Y-m-d H:i:s')
        ));

		// 上传图片开始
		$arrUpload = tsUpload($_FILES['photo'], $articleid, 'article', array('jpg', 'gif', 'png', 'jpeg'));
		if ($arrUpload) {
			$new['article'] -> update('article', array(
                'articleid' => $articleid
            ), array(
                'path' => $arrUpload['path'],
                'photo' => $arrUpload['url']
            ));


			#生成不同尺寸的图片
            tsXimg($arrUpload['url'],'article',320,180,$arrUpload['path'],'1');
            tsXimg($arrUpload['url'],'article',640,'',$arrUpload['path']);


		}
		// 上传图片结束

		// 处理标签
		aac('tag') -> addTag('article', 'articleid', $articleid, $tag);


		// 对积分进行处理
        if($isaudit==0){
            aac('user') -> doScore($TS_URL['app'], $TS_URL['ac'], $TS_URL['ts']);
        }


		header("Location: " . tsUrl('article', 'show', array('id' => $articleid)));

		break;
}
