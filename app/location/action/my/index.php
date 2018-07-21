<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


if($strUser['locationid']){

    $strLocation = $new['location']->find('location',array(
        'locationid'=>$strUser['locationid'],
    ));

}


$title = '我的同城';
include template('my/index');