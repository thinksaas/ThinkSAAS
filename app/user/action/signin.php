<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "":
        if($new['user']->signin()){
            echo 1;exit;
        }else{
            echo 0;exit;
        }
        break;


    case "ajax":

        $userid = intval($_SESSION['tsuser']['userid']);
        $strSign = $new['user']->find('sign',array(
            'userid'=>$userid,
            'addtime'=>date('Y-m-d'),
        ));
        include template('signin_ajax');
        break;

}