<?php
defined('IN_TS') or die('Access Denied.');


/*
<script type="text/javascript" src="{SITE_URL}public/js/jquery.upload.v2.js"></script>
<script>
    $(function(){
        $("#upload").upload({
            action: "{SITE_URL}index.php?app=pubs&ac=photo", //上传地址
            fileName: "filedata",    //文件名称。用于后台接收
            params: {},         //参数
            accept: ".jpg",     //文件类型
            complete: function (rs) {  //上传完成
                $("#photo img").attr("src",rs);
            },
            submit: function () {   //提交之前
                //alert("submit");
            }
        });
    })
</script>
 */

//集合JS的临时上传

$userid = aac('user')->isLogin();

$dest_dir = 'cache/upload';

createFolders ( $dest_dir );

$arrType = explode ( '.', strtolower ( $_FILES ['filedata'] ['name'] ) );

$type = array_pop ( $arrType );

if (in_array ( $type, array('jpg','jpeg','gif','png') )) {

    $name =  $userid .'.'. $type;

    $dest = $dest_dir . '/' . $name;

    unlink ( $dest );

    move_uploaded_file ( $_FILES ['filedata'] ['tmp_name'], mb_convert_encoding ( $dest, "gb2312", "UTF-8" ) );

    chmod ( $dest, 0777 );

    echo SITE_URL.$dest.'?v='.rand();

}