<?php
defined('IN_TS') or die('Access Denied.');

class weiboAction extends weibo{

    /*
     * 首页
     */
    public function index(){

        //dump($GLOBALS);

        $page = tsIntval($_GET['page'],1);

        $url = tsUrl('weibo','index',array('page'=>''));

        $lstart = $page*20-20;

        $arrWeibo = $this->findAll('weibo',array(
            'isaudit'=>0,
        ),'uptime desc',null,$lstart.',20');
        foreach($arrWeibo as $key=>$item){
            $arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
            $arrWeibo[$key]['title'] = tsTitle($item['title']);
        }


        $weiboNum = $this->findCount('weibo',array(
            'isaudit'=>0,
        ));

        $pageUrl = pagination($weiboNum, 20, $page, $url);


        #热门唠叨
        $arrHotWeibo = $this->findAll('weibo',null,'count_comment desc',null,10);

        foreach($arrHotWeibo as $key=>$item){
            $arrHotWeibo[$key]['title'] = tsTitle($item['title']);
            $arrHotWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
        }


        $title = '唠叨';
        include template('index');
    }

    /*
     * 发布唠叨
     */
    public function add(){

        $js = intval($_GET['js']);

        $userid = aac('user')->isLogin(1);

        //判断用户是否存在
        if(aac('user')->isUser($userid)==false) getJson('不好意思，用户不存在！',$js);

        //判断发布者状态
        if(aac('user')->isPublisher()==false) getJson('不好意思，你还没有权限发布内容！',$js);

        //发布时间限制
        if(aac('system')->pubTime()==false) getJson('不好意思，当前时间不允许发布内容！',$js);

        $title = trim($_POST['title']);

        if($title == '') {
            getJson('内容不能为空',$js);
        }

        $isaudit = 0;

        if($GLOBALS['TS_USER']['isadmin']==0){
            //过滤内容开始
            aac('system')->antiWord($title,$js);
            //过滤内容结束
        }

        $weiboid = $this->create('weibo',array(
            'userid'=>$userid,
            'title'=>$title,
            'isaudit'=>$isaudit,
            'addtime'=>date('Y-m-d H:i:s'),
        ));

        $daytime = date('Y-m-d 00:00:01');
        $count_weibo = $this->findCount('weibo',"`userid`='$userid' and `addtime`>'$daytime'");

        #每日前三条给积分
        if($count_weibo<4){
            aac('user') -> doScore($GLOBALS['TS_URL']['app'], $GLOBALS['TS_URL']['ac'], $GLOBALS['TS_URL']['ts']);
        }

        getJson('发布成功！',$js,2,tsurl('weibo','show',array('id'=>$weiboid)));
		
    }

	/*
	 *展示唠叨内容
	 */
    public function show(){
        $weiboid = tsIntval($_GET['id']);
        $strWeibo = $this->getOneWeibo($weiboid);
        if($weiboid==0 || $strWeibo==''){
            ts404();
        }
        if($strWeibo['isaudit']==1){
            tsNotice('内容审核中...');
        }

        //comment
        $page = tsIntval($_GET['page'],1);
        $url = tsUrl('weibo','show',array('id'=>$weiboid,'page'=>''));
        $lstart = $page*15-15;
        $arrComment = aac('pubs')->getCommentList('weibo','weiboid',$strWeibo['weiboid'],$page,$lstart,$strWeibo['userid']);
        $commentNum = aac('pubs')->getCommentNum('weibo','weiboid',$strWeibo['weiboid']);
        $pageUrl = pagination($commentNum, 15, $page, $url);



        //他的更多唠叨
        $arrWeibo = $this->findAll('weibo',array(
            'userid'=>$strWeibo['userid'],
        ),'addtime desc',null,20);

        $weiboNum = $this->findCount('weibo',array(
            'userid'=>$strWeibo['userid'],
        ));

        if($weiboNum<20){

            $num = 20-$weiboNum;
            $userid = $strWeibo['userid'];
            $arrNewWeibo = $this->findAll('weibo',"`userid`!='$userid'",'addtime desc',null,$num);

            $arrWeibo = array_merge($arrWeibo, $arrNewWeibo);

        }

        foreach($arrWeibo as $key=>$item){
            $arrWeibo[$key]['title'] = tsTitle($item['title']);
        }


        if($strWeibo['title']==''){
            $title = $strWeibo['user']['username'].'的唠叨('.$strWeibo['weiboid'].')';
        }else{
            $title = cututf8($strWeibo['title'],0,100,false);
        }

        include template('show');
    }
	
	
	/*
	 * 发布唠叨图片
	 */
	public function photo(){

		$userid = intval($GLOBALS['TS_USER']['userid']);

		if($userid==0){
			echo 0;exit;//请登录
		}


		$title = tsClean($_POST['title']);

		if($GLOBALS['TS_USER']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($title);
			//过滤内容结束
		}

		$weiboid = $this->create('weibo',array(
			'userid'=>$userid,
			'title'=>$title,
			'isaudit'=>0,
			'addtime'=>date('Y-m-d H:i:s'),
			'uptime'=>date('Y-m-d H:i:s'),
		));

		// 上传图片开始
		$arrUpload = tsUpload ( $_FILES ['filedata'], $weiboid, 'weibo', array ('jpg','gif','png','jpeg' ) );
		if ($arrUpload) {
			$this->update ( 'weibo', array (
					'weiboid' => $weiboid 
			), array (
					'path' => $arrUpload ['path'],
					'photo' => $arrUpload ['url'] 
			) );
			
			echo 3;exit;
			
		}else{
			
			echo 2;exit;

		}
	}
	
	/*
	 * 删除唠叨 
	 */
	public function deleteweibo(){
		$userid = aac('user')->isLogin();

		$weiboid = intval($_GET['weiboid']);

		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));

		if($userid == $strWeibo['userid'] || $GLOBALS['TS_USER']['isadmin']==1){

			$this->delete('weibo',array(
				'weiboid'=>$weiboid,
			));
            
			//删除图片
			if($strWeibo['photo']){
				unlink('uploadfile/weibo/'.$strWeibo['photo']);
            }
            
            #删除评论ts_comment
            aac('pubs')->delComment('weibo','weiboid',$strWeibo['weiboid']);

            #删除点赞ts_love
            aac('pubs')->delLove('weibo','weiboid',$strWeibo['weiboid']);
			
			tsNotice('删除成功！','点击返回唠叨首页',tsUrl('weibo'));
			
		}else{
			tsNotice('非法操作！');
		}
	}

    /*
     * 后台管理入口
     * */
    public function admin(){

        if($GLOBALS['TS_USER']['isadmin']==1){
            include 'app/'.$GLOBALS['TS_URL']['app'].'/admin.'.$GLOBALS['TS_URL']['app'].'.php';
            $appAdmin = $GLOBALS['TS_URL']['app'].'Admin';
            $newAdmin = new $appAdmin($GLOBALS['db']);
            #$newAdmin->$GLOBALS['TS_URL']['mg']();

            $amg = $GLOBALS['TS_URL']['mg'];
            $newAdmin->$amg();

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
            $myFun = $GLOBALS['TS_URL']['my'];
            $newMy->$myFun();
        }else{
            ts404();
        }
    }

}