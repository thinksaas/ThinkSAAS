<?php 
defined('IN_TS') or die('Access Denied.');

function umeditor($loadjs='load'){
		if($loadjs!='load'){
			$loadjs='load_'.$loadjs;
		}
		echo '<link href="'.SITE_URL.'plugins/pubs/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/umeditor/umeditor.config.js"></script>
		<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/umeditor/umeditor.js"></script>
		<script type="text/javascript" src="'.SITE_URL.'plugins/pubs/umeditor/lang/zh-cn/zh-cn.js"></script>
		<script type="text/javascript" charset="utf-8" src="'.SITE_URL.'plugins/pubs/umeditor/'.$loadjs.'.js"></script>';
}

if($_SESSION['tsuser']){
	addAction('tseditor','umeditor');
}