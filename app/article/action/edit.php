<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

// 用户是否登录
$userid = aac ( 'user' )->isLogin ();

//普通不用不允许编辑内容
if($TS_SITE['isallowedit'] && $TS_USER ['isadmin'] == 0) tsNotice('系统不允许用户编辑内容，请联系管理员编辑！');

switch ($ts) {
	
	case "" :
		
		$articleid = tsIntval ( $_GET ['articleid'] );
		
		$cateid = tsIntval ( $_GET ['cateid'] );
		
		$strArticle = $new ['article']->find ( 'article', array (
				'articleid' => $articleid 
		) );
		
		if ($strArticle ['userid'] == $userid || $TS_USER ['isadmin'] == 1) {
		
			$strArticle['title'] = tsTitle($strArticle['title']);
			//$strArticle['content'] = tsDecode($strArticle['content']); //为有效防止xss攻击，如果前端通过textarea标签加载的编辑器，请注释掉本行；如果编辑器有其他的加载方式，请视情况解除本行注释。
			$strArticle['gaiyao'] = tsTitle($strArticle['gaiyao']);

			#封面图
			$strArticle['photo_url'] = $new['article']->getArticlePhoto($strArticle);
			
			// 找出TAG
			$arrTags = aac ( 'tag' )->getObjTagByObjid ( 'article', 'articleid', $articleid );
			foreach ( $arrTags as $key => $item ) {
				$arrTag [] = $item ['tagname'];
			}
			$strArticle ['tag'] = arr2str ( $arrTag );

            foreach ($arrCate as $key=>$item){
                $arrCate[$key]['two'] = $new['article']->findAll('article_cate',array(
                    'referid'=>$item['cateid'],
                ));
            }

			
			$title = '修改文章';
			include template ( 'edit' );
		} else {
			
			tsNotice ( '非法操作！' );
		}
		
		break;
	
	case "do" :
		
		$articleid = tsIntval ( $_POST ['articleid'] );
		
		$strArticle = $new ['article']->find ( 'article', array (
				'articleid' => $articleid 
		) );
		
		if($strArticle['userid']!=$userid && $TS_USER['isadmin']==0){
			tsNotice('非法操作！');
		}
		
		$cateid = tsIntval ( $_POST ['cateid'] );
		$cateid2 = tsIntval ( $_POST ['cateid2'] );

		if($cateid2) $cateid = $cateid2;

		$title = trim ( $_POST ['title'] );
		$content = tsClean ( $_POST ['content'] );
		$content2 = emptyText ( $_POST ['content'] );
		$gaiyao = trim ( $_POST ['gaiyao'] );

		$re_gaiyao = tsIntval ( $_POST ['re_gaiyao'] );

        $score = tsIntval($_POST ['score']);#积分

		if ($TS_USER ['isadmin'] == 0) {
			// 过滤内容开始
			$title = antiWord ( $title );
			$content = antiWord ( $content );
			// 过滤内容结束
		}
		
		if ($title == '' || $content2 == '' || $content=='')
			qiMsg ( "标题和内容都不能为空！" );

        if($score<0){
            tsNotice ( '积分填写有误！' );
		}
		
		if($re_gaiyao==1){
			$gaiyao = cututf8(t(tsDecode($content)),0,100);
		}

		$new ['article']->update ( 'article', array (
			'articleid' => $articleid,
		), array (		
			//'cateid' => $cateid,
			'title' => $title,
			'content' => $content ,
			'gaiyao' => $gaiyao,
            'score'=>$score,
		));

		#更新分类
		if($cateid){
            $new['article']->update('article',array(
                'articleid' => $articleid,
            ),array(
                'cateid' => $cateid,
            ));
        }
		
		// 处理标签
		$tag = trim ( $_POST ['tag'] );
		if ($tag) {
			aac ( 'tag' )->delIndextag ( 'article', 'articleid', $articleid );
			aac ( 'tag' )->addTag ( 'article', 'articleid', $articleid, $tag );
		}

		$pjson = '';
		if($strArticle['photo']){
			$pjson = json_encode(array(
				tsXimg($strArticle['photo'],'article',320,180,$strArticle['path'],1)
			));
		}
		
		// 上传封面图片
		$arrUpload = tsUpload ( $_FILES ['photo'], $articleid, 'article', array ('jpg','gif','png','jpeg' ) );
		if ($arrUpload) {
			$new ['article']->update ( 'article', array (
                'articleid' => $articleid
			), array (
                'path' => $arrUpload ['path'],
				'photo' => $arrUpload ['url'],
				'uptime'=>time(),
			) );

            #生成不同尺寸的图片
			tsDimg ($arrUpload ['url'], 'article', '320', '180', $arrUpload ['path']);
			
			$pjson = json_encode(array(
				tsXimg($arrUpload['url'],'article',320,180,$arrUpload['path'],1)
			));

		}

		#更新ptable
		aac('pubs')->editPtable('article','articleid',$articleid,$pjson,$title,$gaiyao);


		#用户记录
		aac('pubs')->addLogs('article','articleid',$articleid,$userid,$title,$content,1);

		
		header ("Location: " . tsUrl ( 'article', 'show', array ('id' => $articleid)));
		
		break;
}