<?php  
defined('IN_TS') or die('Access Denied.');



switch($ts){

	case "":
		$title = '反垃圾';
		include template('spam');
		break;
		
	case "get":
		
		$api = file_get_contents('http://www.thinksaas.cn/index.php?app=service&ac=spam&ts=api');
		
		$arrSpam = json_decode($api,true);
		
		foreach($arrSpam as $key=>$item){
		
			$isword = $new['system']->findCount('anti_word',array(
				'word'=>$item,
			));
			
			if($isword==0){
				$new['system']->create('anti_word',array(
					'word'=>$item,
					'addtime'=>date('Y-m-d H:i:s'),
				));
			}
		
		}
		
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
		
		
		header('Location: '.SITE_URL.'index.php?app=system&ac=anti&ts=word');
		
		break;

}