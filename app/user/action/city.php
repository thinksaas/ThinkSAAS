<?php 
defined('IN_TS') or die('Access Denied.');

$provinceid = intval($_GET['provinceid']);

$city = $new['user']->findAll('area_city',array(

	'fatherid'=>$provinceid,

));

echo '<select id="city" name="city" onchange="selectArea()">
	<option value="0">请选则市</option>';
	
		foreach($city as $k=>$v){

		echo '<option value="'.$v['cityid'].'">'.$v['city'].'</option>';
	
		}

echo '</select>';