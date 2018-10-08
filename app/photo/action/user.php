<?php
/**
 * Created by PhpStorm.
 * User: qiniao
 * Date: 2018/6/12
 * Time: 下午7:58
 */
defined('IN_TS') or die('Access Denied.');

$userid = intval($_GET['id']);

if($userid == 0) header("Location: ".SITE_URL."index.php");

$strUser = aac('user')->getOneUser($userid);

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('photo','user',array('id'=>$userid,'page'=>''));

$lstart = $page*6-6;

$arrAlbum = $new['photo']->findAll('photo_album',array(
    'userid'=>$userid,
),'albumid desc',null,$lstart.',6');

foreach($arrAlbum as $key=>$item){
    $arrAlbum[$key]['albumname'] = stripslashes($item['albumname']);
    $arrAlbum[$key]['albumdesc'] = stripslashes($item['albumdesc']);
}

$albumNum = $new['photo']->findCount('photo_album',array(

    'userid'=>$userid,

));

$pageUrl = pagination($albumNum, 6, $page, $url);


$title = $strUser['username'].'的相册';

include template("user");