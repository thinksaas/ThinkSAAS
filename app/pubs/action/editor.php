<?php
defined('IN_TS') or die('Access Denied.');

/**
 * 编辑器上传控制
 */

switch($ts){

    #图片上传
    case "photo":

        $js = intval($_GET['js']);

        $userid = aac('user')->isLogin();

        $id = $new['pubs']->create('editor',array(
            'userid'=>$userid,
            'type'=>'photo',
            'addtime'=>time(),
        ));


        $arrUpload = tsUpload($_FILES['photo'], $id, 'editor', array('jpg', 'gif', 'png', 'jpeg'));
        if ($arrUpload) {
            $new['pubs'] -> update('editor', array(
                'id' => $id
            ), array(
                'title'=>$arrUpload['name'],
                'path' => $arrUpload['path'],
                'url' => $arrUpload['url']
            ));


            if($js==1){

                echo json_encode(array(
                    'errno'=>0,
                    'data'=>array(
                        0=>SITE_URL.'uploadfile/editor/'.$arrUpload['url'],
                    ),
                ));
                exit();

            }else{

                echo SITE_URL.'uploadfile/editor/'.$arrUpload['url'];
                exit();

            }



        }else{

            $new['pubs']->delete('editor',array(
                'id'=>$id,
            ));

        }







        break;

}