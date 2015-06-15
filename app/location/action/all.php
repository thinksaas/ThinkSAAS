<?php
defined('IN_TS') or die('Access Denied.');

$arrLocation = $new['location']->findAll('location');

$title = '全部同城';
include template('all');