<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

switch($ts){

    //图片上传
    case "upload":

        $photoid = $new['weibo']->create('weibo_photo',array(
            'userid'=>$userid,
            'addtime'=>time(),
        ));

        // 上传图片开始
		$arrUpload = tsUpload ( $_FILES ['filedata'], $photoid, 'weibo/photo', array ('jpg','png','jpeg' ) );
		if ($arrUpload) {
			$new['weibo']->update('weibo_photo', array(
				'photoid' => $photoid
			),array(
				'path' => $arrUpload ['path'],
				'photo' => $arrUpload ['url'] 
			));
		}else{
			$new['weibo']->delete('weibo_photo',array(
                'photoid'=>$photoid,
            ));
        }
        
        echo 11111;

    break;

    //未发布的图片列表
    case "list":

        $arrPhoto = $new['weibo']->findAll('weibo_photo',array(
            'userid'=>$userid,
            'weiboid'=>0,
        ));

        include template('photo_list');

    break;

    //删除未发布的图片
    case "delete":

        $photoid = tsIntval($_POST['photoid']);

        $strPhoto = $new['weibo']->find('weibo_photo',array(
            'photoid'=>$photoid,
            'userid'=>$userid,
        ));
        if($strPhoto){
            $new['weibo']->deletePhoto($strPhoto);
        }

    break;

}