<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

$cateid = intval ( $_GET ['id'] );

if($cateid==0){
    ts404();
}

//分类
$strCate = $new ['article']->find ( 'article_cate', array (
    'cateid' => $cateid
));

if($strCate==''){
    ts404();
}

if($strCate['referid']==0){
    $arrTwoCate = $new['article']->findAll('article_cate',array(
        'referid'=>$strCate['cateid'],
    ));

    if($arrTwoCate){

        foreach($arrTwoCate as $key=>$item){
            $arrCateId[] = $item['cateid'];
        }

        $cateids = $cateid.','.arr2str($arrCateId);

        $where = "`cateid` in ($cateids) and `isaudit`=0";

    }else{

        $where = array(
            'cateid'=>$cateid,
            'isaudit'=>0,
        );

    }



}else{

    $arrTwoCate = $new['article']->findAll('article_cate',array(
        'referid'=>$strCate['referid'],
    ));

    $where = array(
        'cateid'=>$cateid,
        'isaudit'=>0,
    );
}

// 列表
$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'article', 'cate', array ('id' => $cateid, 'page' => ''));
$lstart = $page * 10 - 10;

$arrArticle = $new ['article']->findAll ( 'article',$where, 'addtime desc', 'articleid,userid,cateid,title,gaiyao,path,photo,count_comment,count_recommend,count_view,addtime', $lstart . ',10' );

$articleNum = $new ['article']->findCount ( 'article',$where);

$pageUrl = pagination ( $articleNum, 10, $page, $url );

foreach ( $arrArticle as $key => $item ) {
    $arrArticle [$key]['title'] = tsTitle($item['title']);
    $arrArticle [$key]['gaiyao'] = tsTitle($item['gaiyao']);
    $arrArticle [$key] ['user'] = aac ( 'user' )->getSimpleUser ( $item ['userid'] );
    $arrArticle [$key] ['cate'] = array(
        'cateid'=>$strCate['cateid'],
        'catename'=>$strCate['catename'],
    );
}

// 推荐阅读
$arrRecommend = $new ['article']->getRecommendArticle ();

// 一周热门
$arrHot7 = $new ['article']->getHotArticle ( 7);
// 一月热门
$arrHot30 = $new ['article']->getHotArticle ( 30 );

$title = $strCate ['catename'];

// SEO优化
$sitekey = $strCate ['catename'];
$sitedesc = $strCate ['catename'] . ' - 文章';

include template ( 'index' );