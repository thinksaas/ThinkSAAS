<?php 
//插件编辑
switch($ts){
	case "set":
		$arrNav = fileRead('data.php','plugins','pubs','navs');
		
		include 'edit_set.html';
		break;
		
	case "do":
		$arrNavName = $_POST['navname'];
		$arrNavUrl = $_POST['navurl'];
		
		foreach($arrNavName as $key=>$item){
			$navname = trim($item);
			$navurl = trim($arrNavUrl[$key]);
			if($navname && $navurl){
				$arrNav[] = array(
					'navname'	=> $navname,
					'navurl'	=> $navurl,
				);
			}
			
		}
		
		fileWrite('data.php','plugins/pubs/navs',$arrNav);
		
		qiMsg("修改成功！");
		break;
}