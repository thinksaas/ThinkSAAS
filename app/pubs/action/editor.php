<?php
defined('IN_TS') or die('Access Denied.');

/**
 * 编辑器上传控制
 */

switch($ts){

    #图片上传
    case "photo":

        $js = tsIntval($_GET['js']);

        $userid = aac('user')->isLogin();

        $id = $new['pubs']->create('editor',array(
            'userid'=>$userid,
            'type'=>'photo',
            'addtime'=>time(),
        ));


        $arrUpload = tsUpload($_FILES['photo'], $id, 'editor', array('jpg', 'gif', 'png', 'jpeg'),'sy.png');
        if ($arrUpload) {
            $new['pubs'] -> update('editor', array(
                'id' => $id
            ), array(
                'title'=>$arrUpload['name'],
                'path' => $arrUpload['path'],
                'url' => $arrUpload['url']
            ));


            if($TS_SITE['file_upload_type']==1){
                #阿里云(对象云存储OSS)数据
                $url = $TS_SITE['alioss_bucket_url'].'/'.'uploadfile/editor/'.$arrUpload['url'].'?x-oss-process=image/resize,w_800';
            }else{
                #本地数据
                $url = SITE_URL.'uploadfile/editor/'.$arrUpload['url'];
            }


            if($js==1){

                echo json_encode(array(
                    'errno'=>0,
                    'data'=>array(
                        0=>$url,
                        //0=>tsXimg($arrUpload['url'],'editor','640','',$arrUpload['path']),
                    ),
                ));
                exit();

            }else{

                echo $url;
                //echo tsXimg($arrUpload['url'],'editor','640','',$arrUpload['path']);
                exit();

            }



        }else{

            $new['pubs']->delete('editor',array(
                'id'=>$id,
            ));

        }







        break;

}