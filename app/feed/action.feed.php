<?php
defined('IN_TS') or die('Access Denied.');

class feedAction extends feed{
    
    /*
     * 动态首页
     */
    public function index(){

        $page = isset($_GET['page']) ? intval($_GET['page']) : '1';
        $url = tsurl('feed','index',array('page'=>''));
        $lstart = $page*20-20;

        $arrFeeds = $this->findAll('feed',null,'addtime desc',null,$lstart.',20');

        $feedNum = $this->findCount("feed");
        $pageUrl = pagination($feedNum, 20, $page, $url);

        if($page > 1){
            $title = '社区动态 - 第'.$page.'页';
        }else{
            $title = '社区动态';
        }

        foreach($arrFeeds as $key=>$item){
            $data = json_decode($item['data'],true);

            if(is_array($data)){
                foreach($data as $key=>$itemTmp){
                    $tmpkey = '{'.$key.'}';
                    $tmpdata[$tmpkey] = stripslashes(urldecode($itemTmp));
                }
            }
            $arrFeed[] = array(
                'user'	=> aac('user')->getOneUser($item['userid']),
                'content' => strtr($item['template'],$tmpdata),
                'addtime' => $item['addtime'],
            );
        }

        include template('index');
        
    }




    /*
 * 后台管理入口
 * */
    public function admin(){

        if($GLOBALS['TS_USER']['isadmin']==1){
            include 'app/'.$GLOBALS['TS_URL']['app'].'/admin.'.$GLOBALS['TS_URL']['app'].'.php';
            $appAdmin = $GLOBALS['TS_URL']['app'].'Admin';
            $newAdmin = new $appAdmin($GLOBALS['db']);
            $newAdmin->$GLOBALS['TS_URL']['mg']();
        }else{
            ts404();
        }
    }

    /*
     * 我的社区入口
     * */
    public function my(){
        if($GLOBALS['TS_USER']){
            include 'app/'.$GLOBALS['TS_URL']['app'].'/my.'.$GLOBALS['TS_URL']['app'].'.php';
            $appMy = $GLOBALS['TS_URL']['app'].'My';
            $newMy = new $appMy($GLOBALS['db']);
            $newMy->$GLOBALS['TS_URL']['my']();
        }else{
            ts404();
        }
    }
    
}