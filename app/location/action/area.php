<?php 

$areaid = intval($_GET['areaid']);

$arrArea = $new['location']->getAreaForApp($areaid);

$strArea = $new['location']->getOneArea($areaid);

$referArea = $new['location']->getReferArea($areaid);

$title = $strArea['areaname'];

include template("area");