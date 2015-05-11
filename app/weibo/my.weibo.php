<?php
defined('IN_TS') or die('Access Denied.');

class weiboMy extends weibo{

    /*
     * 我的唠叨
     * */
    public function index(){

        $strUser = aac('user')->getOneUser($GLOBALS['TS_USER']['userid']);

        $page = isset($_GET['page']) ? intval($_GET['page']) : '1';

        $url = tsUrl('weibo','my',array('my'=>'index','page'=>''));

        $lstart = $page*20-20;

        $arrWeibo = $this->findAll('weibo',array(
            'userid'=>$strUser['userid'],
        ),'uptime desc',null,$lstart.',20');

        $weiboNum = $this->findCount('weibo',array(
            'userid'=>$strUser['userid'],
        ));

        $pageUrl = pagination($weiboNum, 20, $page, $url);

        $title = '我的唠叨';
        include template('my/index');
    }
    
}