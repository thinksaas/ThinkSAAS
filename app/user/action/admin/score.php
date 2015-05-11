<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "list":
	
		$arrScore = $new['user']->findAll('user_score');
		
		include template('admin/score_list');
		break;
		
	case "adddo":
	
		$scorekey = trim($_POST['scorekey']);
		$scorename = trim($_POST['scorename']);
		$score = intval($_POST['score']);
		
		$app = trim($_POST['app']);
		$action = trim($_POST['action']);
		$ts = trim($_POST['ts']);
		$status = intval($_POST['status']);
		
		$new['user']->create('user_score',array(
			'scorekey'=>$scorekey,
			'scorename'=>$scorename,
			'score'=>$score,
			'app'=>$app,
			'action'=>$action,
			'ts'=>$ts,
			'status'=>$status,
		));
		
		header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=score&ts=list');
	
		break;
		
	case "editdo":
	
		$scoreid = intval($_POST['scoreid']);
		$score = intval($_POST['score']);
		$app = trim($_POST['app']);
		$action = trim($_POST['action']);
		$ts = trim($_POST['ts']);
		$status = intval($_POST['status']);
		
		$new['user']->update('user_score',array(
			'scoreid'=>$scoreid,
		),array(
			'score'=>$score,
			'app'=>$app,
			'action'=>$action,
			'ts'=>$ts,
			'status'=>$status,
		));
		
		header('Location: '.SITE_URL.'index.php?app=user&ac=admin&mg=score&ts=list');
	
		break;
		
	//加积分
	case "send":
		
		include template('admin/score_send');
		
		break;
		
	case "senddo":
	
		$userid = intval($_POST['userid']);
		$score = intval($_POST['score']);
		$scorename = trim($_POST['scorename']);
		
		if($userid && $score && $scorename){
			aac('user')->addScore($userid,$scorename,$score);
			qiMsg('操作成功！');
		}else{
			qiMsg('操作失败！');
		}
	
		break;
		
	case "delete":
		$scoreid = intval($_GET['scoreid']);
		
		$new['user']->delete('user_score',array(
			'scoreid'=>$scoreid,
		));
		
		qiMsg('删除成功！');
		
		break;

}