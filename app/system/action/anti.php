<?php 
defined('IN_TS') or die('Access Denied.');


switch($ts){

	//垃圾词
	case "word":
		
		$arrWord = $new['system']->findAll('anti_word',null,'id desc');
		
		include template('anti_word');
		break;
		
	case "worddo":

		$word = trim($_POST['word']);
		
		if($word){
		
			$isWord = $new['system']->findCount('anti_word',array(
				'word'=>$word,
			));
			
			if($isWord == 0){
				$new['system']->create('anti_word',array(
					'word'=>$word,
					'addtime'=>date('Y-m-d H:i:s'),
				));
				
				//生成缓存
				$arrWords = $new['system']->findAll('anti_word');
				foreach($arrWords as $key=>$item){
					$arrWord[] = $item['word'];
				}
				
				$strWord = '';
				$count = 1;
				if(is_array($arrWord)){
					foreach ($arrWord as $item) {
						if ($count==1) {
							$strWord .= $item;
						} else {
							$strWord .= '|'.$item;
						}
							$count++;
					}
				}
				
				fileWrite('system_anti_word.php','data',$strWord);
				$tsMySqlCache->set('system_anti_word',$strWord);
				
				
			}
			
			qiMsg('垃圾词添加成功！');
			
		}else{
		
			qiMsg('垃圾词不能为空！');
			
		}
		
		break;
	
	case "worddel":
		$id = intval($_GET['id']);
		$new['system']->delete('anti_word',array(
			'id'=>$id,
		));
		
		//生成缓存
		$arrWords = $new['system']->findAll('anti_word');
		foreach($arrWords as $key=>$item){
			$arrWord[] = $item['word'];
		}
		
		$strWord = '';
		$count = 1;
		if(is_array($arrWord)){
			foreach ($arrWord as $item) {
				if ($count==1) {
					$strWord .= $item;
				} else {
					$strWord .= '|'.$item;
				}
					$count++;
			}
		}
		
		fileWrite('system_anti_word.php','data',$strWord);
		$tsMySqlCache->set('system_anti_word',$strWord);
		
		qiMsg('删除成功！');
		break;
		
	//垃圾IP 
	case "ip":
		
		$arrIp = $new['system']->findAll('anti_ip',null,'addtime desc');
		
		include template('anti_ip');
		break;
		
	case "ipdo":
	
		$ip = trim($_POST['ip']);
		if($ip){
		
			$isIp = $new['system']->findCount('anti_ip',array(
				'ip'=>$ip,
			));
			
			if($isIp==0){
			
				$new['system']->create('anti_ip',array(
					'ip'=>$ip,
					'addtime'=>date('Y-m-d H:i:s'),
				));
				
				//生成缓存
				$arrIps = $new['system']->findAll('anti_ip');
				foreach($arrIps as $key=>$item){
					$arrIp[] = $item['ip'];
				}
				fileWrite('system_anti_ip.php','data',$arrIp);
				$tsMySqlCache->set('system_anti_ip',$arrIp);
			
			}
			
			qiMsg('垃圾IP添加成功！');
		
		}else{
		
			qiMsg('垃圾IP不能为空！');
			
		}
	
		break;
		
	case "ipdel":

		$id = intval($_GET['id']);
		$new['system']->delete('anti_ip',array(
			'id'=>$id,
		));
		
		//生成缓存
		$arrIps = $new['system']->findAll('anti_ip');
		foreach($arrIps as $key=>$item){
			$arrIp[] = $item['ip'];
		}
		fileWrite('system_anti_ip.php','data',$arrIp);
		$tsMySqlCache->set('system_anti_ip',$arrIp);
		
		qiMsg('删除成功！');
	
		break;
		
	//云词 
	case "cloud":
		
		include template('anti_cloud');
		break;
		
}