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
        #插入代码插件
        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/plugin/summernote-ext-highlight.js"></script>';
        #过滤html
        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/plugin/summernote-cleaner.js"></script>';

        #附件插件
        if(is_file('plugins/pubs/summernote/plugin/attach/attach.js')) echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/plugin/attach/attach.js"></script>';
        #视频插件
        if(is_file('plugins/pubs/summernote/plugin/video/video.js')) echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/plugin/video/video.js"></script>';
        #音频插件
        if(is_file('plugins/pubs/summernote/plugin/audio/audio.js')) echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/plugin/audio/audio.js"></script>';

        echo '<script src="' . SITE_URL . 'plugins/pubs/summernote/' . $loadjs . '.js?v=' . rand() . '"></script>';
    }
}

addAction('tseditor','summernote');