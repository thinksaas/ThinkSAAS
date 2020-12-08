<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "recomment":

        $referid = tsIntval($_GET['referid']);
        $userid = tsIntval($_GET['userid']);

        $arrRecomment = $new['pubs']->recomment($referid,$userid);

        include template('ajax_recomment');

    break;

}