<?php 
defined('IN_TS') or die('Access Denied.');

function summernote($loadjs='load'){
    if($loadjs!='load'){
        $loadjs='load_'.$loadjs;
    }

    #判断手机访问
    if($loadjs=='load' && isMobile()==true){
        $loadjs = 'load_m';
    }

    if($GLOBALS['TS_USER']) {
        echo '<link href="' . SITE_URL . 'plugins/pubs/summernote/summernote-lite.css" rel="stylesheet">';
        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/summernote-lite.js"></script>';
        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/lang/summernote-zh-CN.js"></script>';
        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/plugin/summernote-ext-highlight.js"></script>';
        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/' . $loadjs . '.js?v=' . rand() . '"></script>';
    }
}

addAction('tseditor','summernote');