<?php

//苹果机 
$appleid = $_GET['appleid'];

$strApple = $db->once_fetch_assoc("select * from ".dbprefix."apple where `appleid`='$appleid'");

$index = $db->fetch_all_assoc("select * from ".dbprefix."apple_index where `appleid`='$appleid'");
foreach($index as $key=>$item){
	$strApple['model'][] = array(
		'virtuename'	=> $new['apple']->getVirtueName($item['virtueid']),
		'parameter'	=> $item['parameter'],
	);
}

//print_r($strApple);

$title = $strApple['title'];

include template('show');