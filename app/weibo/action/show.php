<?php
defined('IN_TS') or die('Access Denied.');

$weiboid = tsIntval($_GET['id']);
$strWeibo = $new['weibo']->getOneWeibo($weiboid);
if($weiboid==0 || $strWeibo==''){
    ts404();
}
if($strWeibo['isaudit']==1){
    tsNotice('内容审核中...');
}

#图片
$arrPhoto = $new['weibo']->findAll('weibo_photo',array(
    'weiboid'=>$weiboid,
));

foreach($arrPhoto as $key=>$item){
    if($TS_SITE['file_upload_type']==1){
        #阿里云oss
        $arrPhoto[$key]['photo_url'] = $TS_SITE['alioss_bucket_url'].'/uploadfile/weibo/photo/'.$item['photo'];
    }else{
        #本地
        $arrPhoto[$key]['photo_url'] = SITE_URL.'uploadfile/weibo/photo/'.$item['photo'];
    }
}

#评论
$page = tsIntval($_GET['page'],1);
$url = tsUrl('weibo','show',array('id'=>$weiboid,'page'=>''));
$lstart = $page*15-15;
$arrComment = aac('pubs')->getCommentList('weibo','weiboid',$strWeibo['weiboid'],$page,$lstart,$strWeibo['userid']);
$commentNum = aac('pubs')->getCommentNum('weibo','weiboid',$strWeibo['weiboid']);
$pageUrl = pagination($commentNum, 15, $page, $url);



//他的更多唠叨
$arrWeibo = $new['weibo']->findAll('weibo',array(
    'userid'=>$strWeibo['userid'],
),'addtime desc',null,20);

$weiboNum = $new['weibo']->findCount('weibo',array(
    'userid'=>$strWeibo['userid'],
));

if($weiboNum<20){

    $num = 20-$weiboNum;
    $userid = $strWeibo['userid'];
    $arrNewWeibo = $new['weibo']->findAll('weibo',"`userid`!='$userid'",'addtime desc',null,$num);

    if($arrNewWeibo){
        $arrWeibo = array_merge($arrWeibo, $arrNewWeibo);
    }

}

foreach($arrWeibo as $key=>$item){
    $arrWeibo[$key]['title'] = tsTitle($item['title']);
}


if($strWeibo['title']==''){
    $title = $strWeibo['user']['username'].'的唠叨('.$strWeibo['weiboid'].')';
}else{
    $title = cututf8($strWeibo['title'],0,100,false);
}


#热门唠叨
$arrHotWeibo = $new['weibo']->findAll('weibo',null,'count_comment desc',null,10);

foreach($arrHotWeibo as $key=>$item){
    $arrHotWeibo[$key]['title'] = tsTitle($item['title']);
    $arrHotWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
    $arrHotWeibo[$key]['photo'] = $new['weibo']->getWeiboPhoto($item['weiboid'],4);
}


include template('show');