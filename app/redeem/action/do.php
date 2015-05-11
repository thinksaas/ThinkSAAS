<?php 
defined('IN_TS') or die('Access Denied.');

switch($ts){

	case "user":
	
		$userid = intval($TS_USER['userid']);
		
		if($userid==0){
			echo '0';exit;//未登陆
		}
		
		$goodsid = intval($_POST['goodsid']);
		
		$strGoods = $new['redeem']->find('redeem_goods',array(
			'goodsid'=>$goodsid,
		),'nums,scores');
		
		if($strGoods['nums']){
		
			$strUser = $new['redeem']->find('user_info',array(
				'userid'=>$userid,
			),'count_score');
			
			if($strUser['count_score']>=$strGoods['scores']){
				
				//剩余积分
				$count_score = $strUser['count_score']-$strGoods['scores'];
				
			 
				$isUser = $new['redeem']->findCount('redeem_user',array(
					'userid'=>$userid,
					'goodsid'=>$goodsid,
				));
				
				if($isUser=='0'){
					
					$new['redeem']->create('redeem_user',array(
						'userid'=>$userid,
						'goodsid'=>$goodsid,
					));
					
					$new['redeem']->update('redeem_goods',array(
						'goodsid'=>$goodsid,
					),array(
						'nums'=>$strGoods['nums']-1,
					));
					
					// 扣除用户相应的积分
					aac('user')->delScore($userid,'积分兑换',$strGoods['scores']);
					
					echo '4';exit;//兑换成功
					
				}else{
				
					echo '3';exit;//已经兑换过不能再次兑换
				
				}
			
			}else{
				echo '2';exit;//积分不够
			}
		
		}else{
		
			echo '1';exit;//已经兑换完
		
		}
		

	
		break;
	
}