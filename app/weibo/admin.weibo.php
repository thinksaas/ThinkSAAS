<?php
defined('IN_TS') or die('Access Denied.');

class weiboAdmin extends weibo{

    /*
     * 配置选项
     * */
    public function options(){

        $strOption = getAppOptions('weibo');

        include template("admin/options");
    }
	
	/*
	 * 保存配置
	 */
    public function optionsdo(){
        $arrOption = $_POST['option'];
        #更新app配置选项
        upAppOptions('weibo',$arrOption);
        #更新app导航和我的导航
        upAppNav('weibo',$arrOption['appname']);
        qiMsg('修改成功！');
    }

    /*
     * 唠叨列表
     * */
    public function weibolist(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $url = SITE_URL.'index.php?app=weibo&ac=admin&mg=weibolist&page=';
        $lstart = $page*20-20;
        $arrWeibo = $this->findAll('weibo',null,'addtime desc',null,$lstart.',20');
        foreach($arrWeibo as $key=>$item){
            $arrWeibo[$key]['title'] = tsTitle($item['title']);
        }

        $weiboNum = $this->findCount('weibo');
        $pageUrl = pagination($weiboNum, 20, $page, $url);

        include template("admin/weibo_list");
    }

    /*
     * 审核
     * */
    public function isaudit(){
        $weiboid = intval($_GET['weiboid']);

        $strWeibo = $this->find('weibo',array(
            'weiboid'=>$weiboid,
        ));

        if($strWeibo['isaudit'] == 0){

            $this->update('weibo',array(
                'weiboid'=>$weiboid,
            ),array(
                'isaudit'=>1,
            ));

        }

        if($strWeibo['isaudit'] == 1){

            $this->update('weibo',array(
                'weiboid'=>$weiboid,
            ),array(
                'isaudit'=>0,
            ));

        }

        qiMsg('操作成功！');
    }

    /*
     * 删除
     * */
    public function deleteweibo(){
        $weiboid=intval($_GET['weiboid']);

        $strWeibo = $this->find('weibo',array(
            'weiboid'=>$weiboid,
        ));

        unlink('uploadfile/weibo/'.$strWeibo['photo']);

        $this->delete('weibo',array(
            'weiboid'=>$weiboid,
        ));

        
        #删除评论ts_comment
        aac('pubs')->delComment('weibo','weiboid',$strWeibo['weiboid']);

        #删除点赞ts_love
        aac('pubs')->delLove('weibo','weiboid',$strWeibo['weiboid']);


        qiMsg('删除成功！');
    }


}