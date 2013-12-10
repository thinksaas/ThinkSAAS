<?php
defined('IN_TS') or die('Access Denied.');

//首页

$arrGoods = $new['redeem']->findAll('redeem_goods',null,'addtime desc');

$title = '积分兑换';
include template("index");