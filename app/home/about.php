<?php
defined('IN_TS') or die('Access Denied.');
return array(
	'name'	=> '首页',
	'version'	=> '1.2',
	'desc'	=> '首页',
	'url' => 'http://www.thinksaas.cn',
	'email' => 'thinksaas@qq.com',
	'author' => '邱君',
	'author_url' => 'http://www.thinksaas.cn',
	'isoption'	=> '1',
	'isinstall'	=> '1',
	'issql' => '1',
	'issystem'	=> '1',
	'isappnav'	=> '1',
    'ismy'=>0,
    'hook'=>array(
        //插件钩子
        'home_index_header'=>'上通栏',
        'home_index_left'=>'中左栏',
        'home_index_right'=>'中右栏',
        'home_index_footer'=>'下通栏',
    ),
);