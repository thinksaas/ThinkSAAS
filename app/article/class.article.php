<?php
defined('IN_TS') or die('Access Denied.');

class article extends tsApp {

    //构造函数
    public function __construct($db) {
        $tsAppDb = array();
        include 'app/article/config.php';
        //判断APP是否采用独立数据库
        if ($tsAppDb) {
            $db = new MySql($tsAppDb);
        }

        parent::__construct($db);
    }

    /**
     * 获取文章封面图
     *
     * @param [type] $strArticle
     * @return void
     */
    public function getArticlePhoto($strArticle){
        if($strArticle['photo']){
            $strFace = tsXimg($strArticle['photo'],'article',320,180,$strArticle['path'],1);
            
            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                $strFace .= '&v='.$strArticle['uptime'];
            }else{
                $strFace .= '?v='.$strArticle['uptime'];
            }

        }else{
            $strFace = SITE_URL.'public/images/group.jpg';
        }
        return $strFace;
    }

    /**
     * 删除文章
     *
     * @param [type] $strArticle
     * @return void
     */
    public function deleteArticle($strArticle){

        #删除封面图
        if($strArticle['photo']){

            if($GLOBALS['TS_SITE']['file_upload_type']==1){
                deleteAliOssFile('uploadfile/article/'.$strArticle['photo']);
            }else{
                unlink('uploadfile/article/'.$strArticle['photo']);
                tsDimg($strArticle['photo'],'article','320','180',$strArticle['path']);
            }

        } 

        #删除ts_article
        $this->delete('article',array(
            'articleid' => $strArticle['articleid'],
        ));

        #删除ts_article_content
        $this->delete('article_content',array(
            'articleid' => $strArticle['articleid'],
        ));

        #删除ts_article_user
        $this->delete('article_user',array(
            'articleid' => $strArticle['articleid'],
        ));

        #删除关联标签
        $this->delete('tag_article_index',array(
			'articleid'=>$strArticle['articleid'],
		));

        #删除评论ts_comment
        aac('pubs')->delComment('article','articleid',$strArticle['articleid']);

        #删除点赞ts_love
        aac('pubs')->delLove('article','articleid',$strArticle['articleid']);

        #删除ptable
        aac('pubs')->delPtable('article','articleid',$strArticle['articleid']);

    }


    //热门文章,1天，7天，30天
    /**
     * @param $day
     * @param int $cateid
     * @return mixed
     */
    public function getHotArticle($day, $cateid = 0) {
        $startTime = time() - ($day * 3600 * 24);
        $startTime = date('Y-m-d', $startTime);

        $endTime = date('Y-m-d');

        if ($day == 30) {
            $endTime = date('Y-m-d', time() - (7 * 3600 * 24));
        }

        if ($cateid) {
            $arr = "`cateid`='$cateid' and `count_view`>'0' and `addtime`>'$startTime' and `addtime`<'$endTime' and `isaudit`='0'";
        } else {
            $arr = "`addtime`>'$startTime' and `count_view`>'0' and `addtime`<'$endTime' and `isaudit`='0'";
        }

        $arrArticle = $this->findAll('article', $arr, 'addtime desc', 'articleid,title', 10);
        foreach ($arrArticle as $key => $item) {
            $arrArticle[$key]['title'] = tsTitle($item['title']);
        }

        return $arrArticle;
    }

    //推荐文章 $cateid
    /**
     * @param int $cateid
     * @return mixed
     */
    public function getRecommendArticle($cateid = 0) {

        if ($cateid) {
            $arr = array(
                'cateid' => $cateid,
                'isrecommend' => 1,
            );
        } else {
            $arr = array(
                'isrecommend' => 1,
            );
        }

        $arrArticle = $this->findAll('article', $arr, 'addtime desc', 'articleid,title', 10);
        foreach ($arrArticle as $key => $item) {
            $arrArticle[$key]['title'] = tsTitle($item['title']);
        }

        return $arrArticle;
    }

    /*
     * 是否文章作者
     */
    public function isArticleUser($articleid,$userid){
        $isUser = $this->findCount('article',array(
            'articleid'=>$articleid,
            'userid'=>$userid,
        ));

        if($isUser){
            return true;
        }else{
            return false;
        }

    }

}
