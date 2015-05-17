<?php
defined('IN_TS') or die('Access Denied.');


/**
 * 取得根域名
 * @param type $domain 域名
 * @return string 返回根域名
 */
function GetUrlToDomain($domain) {
    $re_domain = '';
    $domain_postfix_cn_array = array('com', 'net', 'org', 'gov', 'edu', 'com.cn', 'cn','cc','me','tv','la','net.cn','org.cn','top','wang','hk','co','pw','ren','asia','biz','gov.cn','tw','com.tw','us','tel','info','website','host','io','press','mobi');
    $array_domain = explode('.', $domain);
    $array_num = count($array_domain) - 1;
    if ($array_domain[$array_num] == 'cn') {
        if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
            $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
    } else {
        $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
    }
    return $re_domain;
}

$os = explode(" ", php_uname());
if(!function_exists("gd_info")){$gd = '不支持,无法处理图像';}
if(function_exists(gd_info)) {  $gd = @gd_info();  $gd = $gd["GD Version"];  $gd ? '&nbsp; 版本：'.$gd : '';}

				  
$systemInfo = array(
	'server'	=> $_SERVER['SERVER_SOFTWARE'],
	'phpos'	=> PHP_OS,
	'phpversion'	=> PHP_VERSION,
	'mysql'	=> $db->getMysqlVersion(),
	'os' =>$os[0] .''.$os[1].' '.$os[3],
	'gd'=>$gd,
	'upload' =>'表单允许'.ini_get('post_max_size').',上传总大小'.ini_get('upload_max_filesize')
);

//获取域名
$theAuthUrl = GetUrlToDomain($_SERVER['HTTP_HOST']);
//echo 111;exit;

include template("main");