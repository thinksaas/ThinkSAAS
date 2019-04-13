<?php
defined('IN_TS') or die('Access Denied.');

$userid = intval($GLOBALS['TS_USER']['userid']);

if(!$userid) {
    echo '0';
}

$newMsgNum = $new['message']->findCount('message',array(
    'touserid'=>$userid,
    'isread'=>0,
));

if($newMsgNum == '0'){
    echo '0';
}else{
    echo $newMsgNum;
}