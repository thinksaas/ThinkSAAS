<?php
defined('IN_TS') or die('Access Denied.');

class weiboAction extends weibo{

    /*
     * 首页
     */
    public function index(){
        //dump($GLOBALS);

        $page = isset($_GET['page']) ? intval($_GET['page']) : '1';

        $url = tsUrl('weibo','index',array('page'=>''));

        $lstart = $page*20-20;

        $arrWeibo = $this->findAll('weibo',array(
            'isaudit'=>0,
        ),'uptime desc',null,$lstart.',20');
        foreach($arrWeibo as $key=>$item){
            $arrWeibo[$key]['user'] = aac('user')->getOneUser($item['userid']);
            $arrWeibo[$key]['content'] = tsDecode($item['content']);
        }


        $weiboNum = $this->findCount('weibo',array(
            'isaudit'=>0,
        ));

        $pageUrl = pagination($weiboNum, 20, $page, $url);


        $title = '唠叨';
        include template('index');
    }

    /*
     * 发布唠叨
     */
    public function add(){
        $js = intval($_GET['js']);

        $userid = aac('user')->isLogin(1);

        if($_POST['token'] != $_SESSION['token']) {
            getJson('非法操作！',$js);
        }

        $content = tsClean($_POST['content']);

        if($content == '') {
            getJson('内容不能为空',$js);
        }

        $isaudit = 0;


        //过滤内容开始
        aac('system')->antiWord($content);
        //过滤内容结束


        $weiboid = $this->create('weibo',array(
            'userid'=>$userid,
            'locationid'=>aac('user')->getLocationId($userid),
            'content'=>$content,
            'isaudit'=>$isaudit,
            'addtime'=>date('Y-m-d H:i:s'),
            'uptime'=>date('Y-m-d H:i:s'),
        ));


        //feed开始
        $feed_template = '<span class="pl">说：</span><div class="quote"><span class="inq">{content}</span> <span><a class="j a_saying_reply" href="{link}" rev="unfold">回应</a></span></div>';
        $feed_data = array(
            'link'	=> tsurl('weibo','show',array('id'=>$weiboid)),
            'content'	=> cututf8(t($content),'0','50'),
        );
        aac('feed')->add($userid,$feed_template,$feed_data);
        //feed结束


        getJson('发布成功！',$js,2,tsurl('weibo','show',array('id'=>$weiboid)));
		
    }

	/*
	 *展示唠叨内容
	 */
    public function show(){
        $weiboid = intval($_GET['id']);

        $strWeibo = $this->getOneWeibo($weiboid);

        if($weiboid==0 || $strWeibo==''){
            ts404();
        }

        if($strWeibo['isaudit']==1){
            tsNotice('内容审核中...');
        }

        //comment
        $page = isset($_GET['page']) ? intval($_GET['page']) : '1';
        $url = tsUrl('weibo','show',array('id'=>$weiboid,'page'=>''));
        $lstart = $page*20-20;

        $arrComments = $this->findAll('weibo_comment',array(
            'weiboid'=>$weiboid,
        ),'addtime desc','commentid',$lstart.',20');

        foreach($arrComments as $key=>$item){
            $arrComment[] = $this->getOneComment($item['commentid']);
        }

        $commentNum = $this->findCount('weibo_comment',array(
            'weiboid'=>$weiboid,
        ));

        $pageUrl = pagination($commentNum, 20, $page, $url);

        $title = '微博#'.$weiboid;

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


		if($_POST['token'] != $_SESSION['token']) {
			echo 1;exit;//非法操作
		} 

		$content = tsClean($_POST['content']);

		if($GLOBALS['TS_USER']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
		}

		$weiboid = $this->create('weibo',array(
			'userid'=>$userid,
			'content'=>$content,
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
	 * 回复唠叨，添加评论
	 */
	public function addcomment(){
		if($_POST['token'] != $_SESSION['token']) {
			tsNotice('非法操作！');
		}

		//用户是否登录
		$userid = aac('user')->isLogin();
		$weiboid = intval($_POST['weiboid']);
		$touserid = intval($_POST['touserid']);
		$content = tsClean($_POST['content']);

		if($content == ''){
			tsNotice('内容不能为空');
		}

		if($GLOBALS['TS_USER']['isadmin']==0){
			//过滤内容开始
			aac('system')->antiWord($content);
			//过滤内容结束
		}

		$commentid = $this->create('weibo_comment',array(
			'userid'=>$userid,
			'touserid'=>$touserid,
			'weiboid'=>$weiboid,
			'content'=>$content,
			'addtime'=>date('Y-m-d H:i:s'),
		));

		//计算评论总数
		$commentNum = $this->findCount('weibo_comment',array(
			'weiboid'=>$weiboid,
		));

		$this->update('weibo',array(
			'weiboid'=>$weiboid,
		),array(
			'count_comment'=>$commentNum,
		));
		
		$strWeibo = $this->find('weibo',array(
			'weiboid'=>$weiboid,
		));
		
		if($strWeibo['userid'] != $userid){
			$msg_userid = '0';
			$msg_touserid = $strWeibo['userid'];
			$msg_content = '你的微博新增一条回复，快去看看给个回复吧^_^ <br />'.tsUrl('weibo','show',array('id'=>$weiboid));
			aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);
		}

		tsHeaderUrl(tsUrl('weibo','show',array('id'=>$weiboid)));
	}
	
	/*
	 * 删除评论
	 */
	public function deletecomment(){
		$userid = aac('user')->isLogin();
	
		$commentid = intval($_GET['commentid']);
		
		$strComment = $this->find('weibo_comment',array(
			'commentid'=>$commentid,
		));
	
		if($GLOBALS['TS_USER']['isadmin']==1 || $strComment['userid']==$userid){
			
			
			$this->delete('weibo_comment',array('commentid'=>$commentid));
			
			//统计
			$count_comment = $this->findCount('weibo_comment',array(
				'weiboid'=>$strComment['weiboid'],
			));
			
			$this->update('weibo',array(
				'weiboid'=>$strComment['weiboid'],
			),array(
				'count_comment'=>$count_comment,
			));
			
			tsHeaderUrl(tsUrl('weibo','show',array('id'=>$strComment['weiboid'])));
			
			
		}else{
			tsNotice('非法操作！');
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
			
			$this->delete('weibo_comment',array(
				'weiboid'=>$weiboid,
			));
			
			//删除图片
			if($strWeibo['photo']){
				unlink('uploadfile/weibo/'.$strWeibo['photo']);
			}
			
			tsHeaderUrl(tsUrl('weibo'));
			
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