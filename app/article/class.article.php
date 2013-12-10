<?php 
defined('IN_TS') or die('Access Denied.');
class article extends tsApp{

	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}
	
	//热门文章,1天，7天，30天
	public function getHotArticle($day,$cateid=0){
		$startTime = time()-($day*3600*60);
		$startTime = date('Y-m-d',$startTime);
		
		$endTime = date('Y-m-d');
		
		if($day==30){
			$endTime = date('Y-m-d',time()-(7*3600*60));
		}
		
		if($cateid){
			$arr = "`cateid`='$cateid' and `count_view`>'0' and `addtime`>'$startTime' and `addtime`<'$endTime'";
		}else{
			$arr = "`addtime`>'$startTime' and `count_view`>'0' and `addtime`<'$endTime'";
		}
		
		$arrArticle = $this->findAll('article',$arr,'addtime desc','articleid,title',10);
		foreach($arrArticle as $key=>$item){
			$arrArticle[$key]['title'] = htmlspecialchars($item['title']);
		}
		
		return $arrArticle;
		
	}

	//推荐文章 $cateid
	public function getRecommendArticle($cateid=0){
	
		if($cateid){
			$arr = array(
				'cateid'=>$cateid,
				'isrecommend'=>1,
			);
		}else{
			$arr = array(
				'isrecommend'=>1,
			);
		}
	
		$arrArticle = $this->findAll('article',$arr,'addtime desc','articleid,title',10);
		foreach($arrArticle as $key=>$item){
			$arrArticle[$key]['title'] = htmlspecialchars($item['title']);
		}
		
		return $arrArticle;
		
	}
	
}
