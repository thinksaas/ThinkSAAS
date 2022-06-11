<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){
    case "":
        $strOption = getAppOptions('tag');
        include template("admin/options");
    break;

    case "do":

        $arrOption = $_POST['option'];
        #更新app配置选项
        upAppOptions('tag',$arrOption);
        
        qiMsg('修改成功！');

    break;
}