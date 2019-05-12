<?php 
defined('IN_TS') or die('Access Denied.');

$userid = intval($GLOBALS['TS_USER']['userid']);

switch($ts){

    case "":

        if($userid==0){
            echo 2;exit;
        }

        if($new['user']->signin()){
            echo 1;exit;
        }else{
            echo 0;exit;
        }
        break;


    case "ajax":

        $strSign = $new['user']->find('sign',array(
            'userid'=>$userid,
            'addtime'=>date('Y-m-d'),
        ));
        include template('signin_ajax');
        break;

}