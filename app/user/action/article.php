<?php
defined('IN_TS') or die('Access Denied.');

include 'userinfo.php';

$page = isset ( $_GET ['page'] ) ? tsIntval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'user', 'article', array ('id'=>$strUser['userid'],'page' => '' ) );
$lstart = $page * 20 - 20;

$arrArticle = $new ['user']->findAll ( 'article', array (
    'userid' => $strUser['userid'],
), 'addtime desc','articleid,userid,cateid,title,gaiyao,score,path,photo,count_comment,count_love,count_view,addtime', $lstart . ',20' );

foreach($arrArticle as $key=>$item){
    $arrArticle [$key]['title'] = tsTitle($item['title']);
    $arrArticle [$key]['gaiyao'] = tsTitle($item['gaiyao']);
    $arrArticle [$key] ['cate'] = $new ['user']->find( 'article_cate', array (
        'cateid' => $item ['cateid']
    ) );
}

$articleNum = $new ['user']->findCount ( 'article', array (
    'userid' => $strUser['userid'],
) );

$pageUrl = pagination ( $articleNum, 20, $page, $url );

$title = $strUser['username'].'的文章';
include template('article');