<?php
defined('IN_TS') or die('Access Denied.');

class feedAdmin extends feed{

    /*
     * 配置选项
     * */
    public function options(){
        $arrOptions = $this->findAll('feed_options');
        foreach($arrOptions as $item){
            $strOption[$item['optionname']] = stripslashes($item['optionvalue']);
        }
        include template("admin/options");
    }

    /*
     * 保存配置
     */
    public function optionsdo(){
        //先清空数据
        $this->doSql("TRUNCATE TABLE `".dbprefix."feed_options`");

        foreach($_POST['option'] as $key=>$item){

            $optionname = $key;
            $optionvalue = trim($item);

            $this->create('feed_options',array(

                'optionname'=>$optionname,
                'optionvalue'=>$optionvalue,

            ));

        }

        $arrOptions = $this->findAll('feed_options',null,null,'optionname,optionvalue');
        foreach($arrOptions as $item){
            $arrOption[$item['optionname']] = $item['optionvalue'];
        }

        fileWrite('feed_options.php','data',$arrOption);
        $GLOBALS['tsMySqlCache']->set('feed_options',$arrOption);

        qiMsg('修改成功！');
    }

    /*
     * 唠叨列表
     * */
    public function feedlist(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=feed&ac=admin&mg=feed&ts=list&page=';
        $lstart = $page*20-20;

        $arrFeeds = $this->findAll('feed',null,'addtime desc',null,$lstart.',20');

        foreach($arrFeeds as $key=>$item){
            $data = json_decode($item['data'],true);
            if(is_array($data)){
                foreach($data as $key=>$itemTmp){
                    $tmpkey = '{'.$key.'}';
                    $tmpdata[$tmpkey] = urldecode($itemTmp);
                }
            }
            $arrFeed[] = array(
                'feedid'=>$item['feedid'],
                'userid'=>$item['userid'],
                'content' => strtr($item['template'],$tmpdata),
                'addtime' => $item['addtime'],
            );
        }

        $feedNum = $this->findCount('feed');

        $pageUrl = pagination($feedNum, 20, $page, $url);

        include template("admin/feed_list");
    }

    /*
     * 删除
     * */
    public function deletefeed(){
        $feedid = intval($_GET['feedid']);
        $this->delete('feed',array(
            'feedid'=>$feedid,
        ));

        qiMsg('删除成功！');
    }


}