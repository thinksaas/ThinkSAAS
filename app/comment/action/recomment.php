<?php 
defined('IN_TS') or die('Access Denied.');

$referid = tsIntval($_GET['referid']);
$userid = tsIntval($_GET['userid']);

$arrRecomment = $new['comment']->recomment($referid,$userid);

include template('recomment');