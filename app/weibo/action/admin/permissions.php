<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "":

        $ugid = tsIntval($_GET['ugid'],1);

        $arrUg = $new['weibo']->findAll('user_group',null,'ugid asc');

        


        include template('admin/permissions');

        break;

    case "do":

        /**
         * 权限参数说明，app,action必须，其他参数可选
         * app-action-ts
         * app-action-mg-ts     当action=admin
         * app-action-api-ts    当action=api
         */
        
        $ugid = tsIntval($_POST['ugid']);

        $arrOption = $_POST['option'];

        aac('pubs')->upAppPermissions($ugid,'weibo',$arrOption);

        qiMsg('操作成功！');


        break;

}