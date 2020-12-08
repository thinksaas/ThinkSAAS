<?php
defined('IN_TS') or die('Access Denied.');

/**
 * 存草稿箱
 */

$userid = tsIntval($TS_USER['userid']);

if($userid==0){
    getJson('非法操作！',1,0);
}

$types = trim($_POST['types']);
$title = trim($_POST['title']);
$content = tsClean($_POST['content']);

if($types && $title && $content){

    if(!in_array($types,array('topic'))){
        getJson('非法操作！',1,0);
    }

    $new['pubs']->replace('draft',array(
        'userid'=>$userid,
        'types'=>$types,
    ),array(
        'userid'=>$userid,
        'types'=>$types,
        'title'=>$title,
        'content'=>$content,
        'addtime'=>time(),
    ));

    getJson('已自动保存内容到草稿箱！',1);

}