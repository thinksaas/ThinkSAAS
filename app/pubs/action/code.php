<?php
defined('IN_TS') or die('Access Denied.');

require_once('thinksaas/Image.class.php');

$Image = new Image();

echo $Image->buildImageVerify($width=48,$height=22,$randval=NULL,$verifyName='verify');