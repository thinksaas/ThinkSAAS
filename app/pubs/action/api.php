<?php
defined('IN_TS') or die('Access Denied.');
header('Content-type: application/json');
/*
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
$allow_origin = array($GLOBALS['TS_SITE']['link_url'], 'https://www.thinksaas.cn', 'https://api.thinksaas.cn', 'https://www.qiniao.com', 'https://api.qiniao.com');
if(in_array($origin, $allow_origin)) header('Access-Control-Allow-Origin:'.$origin);
*/
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: X-Requested-With');
switch($ts){
    case "qiniao":
        $input = json_decode(file_get_contents("php://input"));
        $sitekey = trim($input->sitekey);
        if($sitekey==''){
            echo json_encode(array(
                'status'=>0,
                'msg'=> 'fails001',
                'data'=> '',
            ));
            exit;
        }
        if($sitekey!=$GLOBALS['TS_SITE']['site_pkey']){
            echo json_encode(array(
                'status'=>0,
                'msg'=> 'fails002',
                'data'=> '',
            ));
            exit;
        }
        $jsonData = json_encode($TS_DB);
        $strCode = $new['pubs']->strCode($jsonData);
        echo json_encode(array(
            'status'=>1,
            'msg'=> 'success',
            'data'=> $strCode,
        ));
        exit;
        break;
    case "photo-net":
        $input = json_decode(file_get_contents("php://input"));
        $sitekey = trim($input->sitekey);
        if($sitekey==''){
            echo json_encode(array(
                'status'=>0,
                'msg'=> 'fails001',
                'data'=> '',
            ));
            exit;
        }
        if($sitekey!=$GLOBALS['TS_SITE']['site_pkey']){
            echo json_encode(array(
                'status'=>0,
                'msg'=> 'fails002',
                'data'=> '',
            ));
            exit;
        }
        $url = trim($input->url);
        $sub = trim($input->sub);
        $sid = intval($input->sid);
        if($url=='' || $sub=='' || $sid==''){
            echo json_encode(array(
                'status'=>0,
                'msg'=> '参数不完整',
                'data'=> '',
            ));
            exit;
        }
        if(!in_array($sub,array('user'))){
            echo json_encode(array(
                'status'=>0,
                'msg'=> '非法操作',
                'data'=> '',
            ));
            exit;
        }
        $menu2=intval($sid/1000);
        $menu1=intval($menu2/1000);
        $menu = $menu1.'/'.$menu2;
        $photo = $sid.'.jpg';
        $photourl = $menu.'/'.$photo;
        if($sub=='user') $dir = 'uploadfile/'.$sub.'/'.$menu;
        $dfile = $dir.'/'.$photo;
        createFolders($dir);
        if(!is_file($dfile)){
            $img = file_get_contents($url);
            file_put_contents($dfile,$img);
        };
        if($sub=='user'){
            tsXimg($photourl,'user',120,120,$menu,1);
            $new['pubs']->update('user_info',array(
                'userid'=>$sid,
            ),array(
                'path'=>$menu,
                'face'=>$photourl,
            ));
        }
        echo json_encode(array(
            'status'=>1,
            'msg'=> 'success',
            'data'=> '',
        ));
        exit;
        break;
    case "user-face":
        $userkey = trim($_POST['userkey']);
        $userid = $new['pubs']->getUserId($userkey);
        if($_FILES['photo']==''){
            $jsonData = json_encode(array(
                'status'=>0,
                'msg'=> '上传图片不能为空！',
                'data'=> '',
            ));
            exit;
        }
        $arrUpload = tsUpload($_FILES['photo'],$userid,'user',array('jpg','png','jpeg'));
        if($arrUpload){
            $new['pubs']->update('user_info',array(
                'userid'=>$userid,
            ),array(
                'path'=>$arrUpload['path'],
                'face'=>$arrUpload['url'],
                'uptime'=>time()
            ));
            tsDimg($arrUpload['url'],'user',120,120,$arrUpload['path']);
            $jsonData = json_encode(array(
                'status'=>1,
                'msg'=> '图片上传成功',
                'data'=> tsXimg($arrUpload['url'],'user',120,120,$arrUpload['path'],1).'?v='.rand(),
            ));
        }else{
            $jsonData = json_encode(array(
                'status'=>0,
                'msg'=> '图片上传失败',
                'data'=> '',
            ));
        }
        echo $jsonData;
        break;
    case "topic-photo-add":
        $userkey = trim($_POST['userkey']);
        $userid = $new['pubs']->getUserId($userkey);
        if($_FILES['photo']==''){
            $jsonData = json_encode(array(
                'status'=>0,
                'msg'=> '上传图片不能为空！',
                'data'=> '',
            ));
            exit;
        }
        $photoid = $new['pubs']->create('group_topic_photo',array(
            'userid'=>$userid,
            'addtime'=>time(),
        ));
        $arrUpload = tsUpload($_FILES['photo'],$photoid,'group/topic/photo',array('jpg','png','jpeg','gif'));
        if($arrUpload){
            $new['pubs']->update('group_topic_photo',array(
                'photoid'=>$photoid,
            ),array(
                'path'=>$arrUpload['path'],
                'photo'=>$arrUpload['url'],
            ));

            tsXimg($arrUpload['url'],'group/topic/photo','320','320',$arrUpload['path'],1);
            tsXimg($arrUpload['url'],'group/topic/photo','640','',$arrUpload['path']);


            $jsonData = json_encode(array(
                'status'=>1,
                'msg'=> 'success',
                'data'=> '',
            ));
        }else{
            $new['pubs']->delete('group_topic_photo',array(
                'photoid'=>$photoid,
            ));
            $jsonData = json_encode(array(
                'status'=>0,
                'msg'=> '图片上传失败',
                'data'=> '',
            ));
        }
        if($_GET['callback']){
            echo $_GET['callback'].'('.$jsonData.')';
        }else{
            echo $jsonData;
        }
        break;
    /**
     * 上传视频
     * index.php?app=pubs&ac=api&ts=video-add
     * post
     * @userkey
     * @_FILE['video']
     */
    case "video-add":
        $userkey = trim($_POST['userkey']);
        $userid = $new['pubs']->getUserId($userkey);
        if($_FILES['video']==''){
            $jsonData = json_encode(array(
                'status'=>0,
                'msg'=> '上传视频不能为空！',
                'data'=> '',
            ));
            exit;
        }
        $videoid = $new['pubs']->create('video',array(
            'userid'=>$userid,
            'siteid'=>0,
            'isaudit'=>1,
            'addtime'=>date('Y-m-d H:i:s'),
        ));
        $arrUpload = tsUpload($_FILES['video'],$videoid,'video',array('mp4'));
        if($arrUpload){
            $new['pubs']->update('video',array(
                'videoid'=>$videoid,
            ),array(
                'vid'=>$videoid,
                'path'=>$arrUpload['path'],
                'video'=>$arrUpload['url'],
            ));

            $jsonData = json_encode(array(
                'status'=>1,
                'msg'=> 'success',
                'data'=> '',
            ));

        }else{
            $new['pubs']->delete('video',array(
                'videoid'=>$videoid,
            ));
            $jsonData = json_encode(array(
                'status'=>0,
                'msg'=> '视频上传失败',
                'data'=> '',
            ));
        }
        if($_GET['callback']){
            echo $_GET['callback'].'('.$jsonData.')';
        }else{
            echo $jsonData;
        }
        break;
}