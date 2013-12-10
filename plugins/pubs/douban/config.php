<?php
defined('IN_TS') or die('Access Denied.');

$arrDouban = fileRead('data/plugins_pubs_douban.php');
if($arrDouban==''){
$arrDouban = $tsMySqlCache->get('plugins_pubs_douban');
}

define('KEY', $arrDouban['key']);
define('SECRET', $arrDouban['secret']);
define('REDIRECT', SITE_URL.'index.php?app=pubs&ac=plugin&plugin=douban&in=callback');

define('SCOPE', 'douban_basic_common,book_basic_r,book_basic_w');
define('STATE', 'Something');
