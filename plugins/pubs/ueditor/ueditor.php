<?php 
defined('IN_TS') or die('Access Denied.');

function ueditor($loadjs='load'){
		if($loadjs!='load'){
			$loadjs='load_'.$loadjs;
		}
		echo '<script>window.UEDITOR_HOME_URL = "'.SITE_URL.'plugins/pubs/ueditor/";</script>';
		echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/ueditor/ueditor.config.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/ueditor/ueditor.all.min.js"> </script>';
		echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/ueditor/'.$loadjs.'.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/ueditor/lang/zh-cn/zh-cn.js"></script>';
}


addAction('tseditor','ueditor');