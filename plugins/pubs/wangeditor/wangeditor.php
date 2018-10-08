<?php 
defined('IN_TS') or die('Access Denied.');

function wangeditor($loadjs='load'){
		if($loadjs!='load'){
			$loadjs='load_'.$loadjs;
		}

		#判断手机访问
		if($loadjs=='load' && isMobile()==true){
		    $loadjs = 'load_m';
        }

        if($GLOBALS['TS_USER']){
		    echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'public/js/xss.js"></script>';
            echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/wangeditor/wangEditor.js"></script>';
            echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/wangeditor/'.$loadjs.'.js?v=20181008"></script>';
        }
}


addAction('tseditor','wangeditor');