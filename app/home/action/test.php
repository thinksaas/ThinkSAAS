<?php 

$arrData = $db->findCount('group',array('groupid'=>1));

echo $arrData;