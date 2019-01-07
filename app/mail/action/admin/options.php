<?php
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "":

        $arrOptions = $db->fetch_all_assoc("select * from ".dbprefix."mail_options");

        foreach($arrOptions as $item){
            $strOption[$item['optionname']] = $item['optionvalue'];
        }

        include template("admin/options");

        break;

    //短信配置
    case "sms":

        $strOption = fileRead('data/sms_options.php');
        if($strOption==''){
            $strOption = $GLOBALS['tsMySqlCache']->get('sms_options');
        }


        include template("admin/options_sms");

        break;

}