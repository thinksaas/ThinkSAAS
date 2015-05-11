<?php
defined('IN_TS') or die('Access Denied.');
class feed extends tsApp{


	//构造函数
	public function __construct($db){
		parent::__construct($db);
	}

    //添加feed
    function add($userid,$template,$data){
        $userid = intval($userid);

        foreach($data as $key=>$value){
            $testJSON[$key] = urlencode ( $value );
        }

        $data = json_encode($testJSON);

        $this->create('feed',array(
            'userid'=>$userid,
            'template'=>$template,
            'data'=>$data,
            'addtime'=>time(),
        ));

    }
	
}