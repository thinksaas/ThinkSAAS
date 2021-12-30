<?php
defined('IN_TS') or die('Access Denied.');

/**
 * 图形验证码
 */

require_once('thinksaas/Image.class.php');

$Image = new Image();

echo $Image->buildImageVerify($width=65,$height=30,$randval=NULL,$verifyName='verify');