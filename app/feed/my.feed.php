<?php
defined('IN_TS') or die('Access Denied.');

class feedMy extends feed{

    /*
     * 我的动态
     * */
    public function index(){


        $title = '我的动态';
        include template('my/index');
    }

}