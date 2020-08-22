<?php 
defined('IN_TS') or die('Access Denied.');

include 'thinksaas/vaptcha.class.php';

$msg = '';
if(isset($_REQUEST['offline_action'])){

    $vaptcha = new Vaptcha($GLOBALS['TS_SITE']['vaptcha_vid'],$GLOBALS['TS_SITE']['vaptcha_key']);

    if(isset($_GET['v'])) {
        echo $vaptcha->offline($_GET['offline_action'], $_GET['callback'], $_GET['v'], $_GET['knock']);
    } else {
        echo $vaptcha->offline($_GET['offline_action'], $_GET['callback']);
    }
}