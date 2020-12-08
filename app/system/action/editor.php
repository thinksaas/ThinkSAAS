<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

    case "list":

        $page = isset($_GET['page']) ? tsIntval($_GET['page']) : 1;
		$url = SITE_URL.'index.php?app=system&ac=editor&ts=list&page=';
		$lstart = $page*20-20;
		$arrEditor = $new['system']->findAll('editor',null,'addtime desc',null,$lstart.',20');
		
		$editorNum = $new['system']->findCount('editor');
		$pageUrl = pagination($editorNum, 20, $page, $url);
		
		include template('editor_list');

    break;

    case "delete":

        $id = tsIntval($_GET['id']);

        $strEditor = $new['system']->find('editor',array(
            'id'=>$id,
        ));

        if($strEditor['url']){
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                deleteAliOssFile('uploadfile/editor/'.$strEditor['url']);
            }else{
                unlink('uploadfile/editor/'.$strEditor['url']); 
            }
        }

        $new['system']->delete('editor',array(
            'id'=>$id,
        ));

        qiMsg('删除成功！');

    break;

}