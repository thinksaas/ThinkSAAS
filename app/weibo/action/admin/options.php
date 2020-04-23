<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
    case "":
        $strOption = getAppOptions('weibo');
        include template("admin/options");
    break;

    case "do":

        $arrOption = $_POST['option'];
        #更新app配置选项
        upAppOptions('weibo',$arrOption);
        #更新app导航和我的导航
        upAppNav('weibo',$arrOption['appname']);
        qiMsg('修改成功！');

    break;


    

}