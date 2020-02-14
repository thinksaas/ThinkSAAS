<?php
defined('IN_TS') or die('Access Denied.');

$skin = 'default';

$TS_APP['appname']	= '同城';


//需要更新locationid的数据表，可根据情况继续添加，但需保证表中有userid和locationid字段存在
$TS_APP['table'] = array(
    'article',
    'attach',
    'group_topic',
    'photo',
    'user_info',
    'weibo',
);