<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

/**
 * 文章列表【api接口示例】
 * index.php?app=article&ac=api&api=list
 * get
 *
 * @cateid  分类ID
 * @page
 * @limit
 * 
 */

$arrCate1 = $new['article']->findAll('article_cate',array(
    'referid'=>0,
));
foreach($arrCate1 as $key=>$item){
    $arrCate[$item['cateid']] = $item['catename'];
}

$cateid = tsIntval($_GET['cateid']);
$locationid = tsIntval($_GET['locationid']);

$kw = trim($_GET['kw']);

$page = isset($_GET['page']) ? $_GET['page'] : '1';
$limit = isset($_GET['limit']) ? $_GET['limit'] : '10';

$lstart = $limit*$page-$limit;

$where = "`isaudit`='0'";

if($cateid){
    $where .= " and `cateid`='$cateid'";
}

$articleNum = $new['article']->findCount('article',$where);

$arrArticle = $new['article']->findAll('article',$where,'addtime desc','articleid,cateid,title,gaiyao,photo,path,count_comment,count_view,count_love,addtime',$lstart.','.$limit);

foreach($arrArticle as $key=>$item){

    $arrArticle[$key]['title'] = tsTitle($item['title']);
    $arrArticle[$key]['gaiyao'] = tsTitle($item['gaiyao']);

    if($item['photo']){
        $arrArticle[$key]['photourl'] = tsXimg($item['photo'],'article',320,180,$item['path'],'1');
    }else{
        $arrArticle[$key]['photourl'] = '';
    }

    if($item['cateid']){
        $arrArticle[$key]['catename'] = $arrCate[$item['cateid']];
    }

}

$jsonData = json_encode(array(

    'status'=> 1,
    'msg'=> 'success',
    'data'=> array(

        'previous'=>tsIntval($page-1),
        'page'=>tsIntval($page),
        'next'=>tsIntval($page+1),
        'total'=>tsIntval(ceil($articleNum/$limit)),
        'list'=>$arrArticle,

    ),

));

if($_GET['callback']){
    echo $_GET['callback'].'('.$jsonData.')';
    exit;
}else{
    echo $jsonData;
}