<?php
defined('IN_TS') or die('Access Denied.');

//判断目录可写
$f_cache =  iswriteable('cache');
$f_data = iswriteable('data');
$f_uploadfile =  iswriteable('uploadfile');
$f_tslogs =  iswriteable('tslogs');
$f_upgrade =  iswriteable('upgrade');



#检测必要组件
$e_mysql = 0;
$e_dom = 0;
$e_mbstring = 0;
$e_gd = 0;
$e_mysqli = 0;
$e_openssl = 0;
$e_session = 0;
$e_SimpleXML = 0;
#$e_mcrypt = 0;
$e_json = 0;
$e_iconv = 0;
$e_fileinfo = 0;
$e_curl = 0;

if(extension_loaded('dom')) $e_dom=1;#dom
if(extension_loaded('mbstring')) $e_mbstring=1;#mbstring
if(extension_loaded('gd')) $e_gd=1;#gd
if(extension_loaded('mysql')) $e_mysql=1;#mysql
if(extension_loaded('mysqli')) $e_mysqli=1;#mysqli
if(extension_loaded('openssl')) $e_openssl=1;#openssl
if(extension_loaded('session')) $e_session=1;#session
if(extension_loaded('SimpleXML')) $e_SimpleXML=1;#SimpleXML
#if(extension_loaded('mcrypt')) $e_mcrypt=1;#mcrypt
if(extension_loaded('json')) $e_json=1;#json
if(extension_loaded('iconv')) $e_iconv=1;#iconv
if(extension_loaded('Fileinfo')) $e_fileinfo=1;#Fileinfo
if(extension_loaded('curl')) $e_curl=1;#curl


include 'install/html/index.html';