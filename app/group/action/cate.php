<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "":
		//一级分类
		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>0,
		));
		
		
		//分类下小组
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','cate',array('page'=>''));
		$lstart = $page*20-20;
		$arrGroup = $new['group']->findAll('group',null,'isrecommend desc,count_topic desc',null,$lstart.',20');
		$groupNum = $new['group']->findCount('group');
		$pageUrl = pagination($groupNum, 20, $page, $url);
		
		
		
		$title = '分类';
		
		include template('cate');
		
		break;
	
	//二级分类
	case "2":
		$cateid = intval($_GET['cateid']);
		$strCate = $new['group']->find('group_cate',array(
			'cateid'=>$cateid,
		));
	
		
		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>$cateid,
		));
		
		
		//分类下小组
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','cate',array('ts'=>'2','page'=>''));
		$lstart = $page*20-20;
		$arrGroup = $new['group']->findAll('group',array(
			'cateid'=>$cateid,
		),null,null,$lstart.',20');
		$groupNum = $new['group']->findCount('group',array(
			'cateid'=>$cateid,
		));
		$pageUrl = pagination($groupNum, 20, $page, $url);

		
		
		
		$title = $strCate['catename'];
		include template('cate2');
	
		break;
		
	//三级分类
	case "3":
	
		$cateid = intval($_GET['cateid']);
		
		$strCate = $new['group']->find('group_cate',array(
			'cateid'=>$cateid,
		));
		
		//上级分类
		$oneCate = $new['group']->find('group_cate',array(
			'cateid'=>$strCate['referid'],
		));
		
		//下级分类
		$arrCate = $new['group']->findAll('group_cate',array(
			'referid'=>$cateid,
		));
		
			
		//分类下小组
		$page = isset($_GET['page']) ? intval($_GET['page']) : '1';
		$url = tsUrl('group','cate',array('ts'=>'3','page'=>''));
		$lstart = $page*20-20;
		$arrGroup = $new['group']->findAll('group',array(
			'cateid2'=>$cateid,
		),null,null,$lstart.',20');

		$groupNum = $new['group']->findCount('group',array(
			'cateid'=>$cateid,
		));
		$pageUrl = pagination($groupNum, 20, $page, $url);

		$title = $strCate['catename'];
		include template('cate3');
		break;
		
	//展示小组
	case "group":
	
		$cateid = intval($_GET['cateid']);
		
		$strCate = $new['group']->find('group_cate',array(
			'cateid'=>$cateid,
		));
		
		$twoCate = $new['group']->find('group_cate',array(
			'cateid'=>$strCate['referid'],
		));
		
		$oneCate = $new['group']->find('group_cate',array(
			'cateid'=>$twoCate['referid'],
		));
		
		
		//分类下小组
		$arrGroup = $new['group']->findAll('group',array(
		
			'cateid3'=>$cateid,
		
		));
		
	
		$title = $strCate['catename'];
	
		include template('cate_group');
	
		break;

	//绑定分类
	case "do":
		
		$groupid = intval($_POST['groupid']);
		$cateid = intval($_POST['cateid']);
		$cateid2 = intval($_POST['cateid2']);
		$cateid3 = intval($_POST['cateid3']);
		
		$new['group']->update('group',array(
			'groupid'=>$groupid,
		),array(
			'cateid'=>$cateid,
			'cateid2'=>$cateid2,
			'cateid3'=>$cateid3,
		));
		
		//更新分类统计
		//更新一级
		if($cateid){
			$count_group = $new['group']->findCount('group',array(
				'cateid'=>$cateid,
			));
			$new['group']->update('group_cate',array(
				'cateid'=>$cateid,
			),array(
				'count_group'=>$count_group,
			));
		}
		//更新二级
		if($cateid2){
		
			$count_group = $new['group']->findCount('group',array(
				'cateid2'=>$cateid2,
			));
			
			$new['group']->update('group_cate',array(
				'cateid'=>$cateid2,
			),array(
				'count_group'=>$count_group,
			));
		}
		//更新三级
		if($cateid3){
			$count_group = $new['group']->findCount('group',array(
				'cateid3'=>$cateid3,
			));
			$new['group']->update('group_cate',array(
				'cateid'=>$cateid3,
			),array(
				'count_group'=>$count_group,
			));
		}
		
		tsNotice('分类修改成功！');
		
		break;
	

	//二级分类	
	case "two":
		$cateid = intval($_GET['cateid']);
		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."group_cate where referid='$cateid'");
		
		if($arrCate){
			echo '<select id="cateid2" name="cateid2">';
			echo '<option value="0">请选择</option>';
			foreach($arrCate as $item){
				echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;
	
	//三级分类
	case "three":
		$cateid2 = intval($_GET['cateid2']);
		$arrCate = $db->fetch_all_assoc("select * from ".dbprefix."group_cate where referid='$cateid2'");
		
		if($arrCate){
			echo '<select id="cateid3" name="cateid3">';
			echo '<option value="0">请选择</option>';
			foreach($arrCate as $item){
				echo '<option value="'.$item['cateid'].'">'.$item['catename'].'</option>';
			}
			echo "</select>";
		}else{
			echo '';
		}
		break;

}