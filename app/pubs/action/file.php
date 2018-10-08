<?php
defined('IN_TS') or die('Access Denied.');

//临时上传

$userid = aac('user')->isLogin();

$dest_dir = 'cache/upload';

createFolders ( $dest_dir );

$arrType = explode ( '.', strtolower ( $_FILES ['filedata'] ['name'] ) );

$type = array_pop ( $arrType );

if (in_array ( $type, array('doc','pdf','ppt','xls','txt') )) {

    $name =  $userid .'.'. $type;

    $dest = $dest_dir . '/' . $name;

    unlink ( $dest );

    move_uploaded_file ( $_FILES ['filedata'] ['tmp_name'], mb_convert_encoding ( $dest, "gb2312", "UTF-8" ) );

    chmod ( $dest, 0777 );

    echo SITE_URL.$dest;

}