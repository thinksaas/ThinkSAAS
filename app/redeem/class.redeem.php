<?php
defined('IN_TS') or die('Access Denied.');  

class redeem extends tsApp{
	
	//构造函数
	public function __construct($db){
        $tsAppDb = array();
		include 'app/redeem/config.php';
		//判断APP是否采用独立数据库
		if($tsAppDb){
			$db = new MySql($tsAppDb);
		}
	
		parent::__construct($db);
	}

    /*
     * 获取一个积分商品信息
     */
    public function getRedeemGoods($goodsid){
        $strGoods = $this->find('redeem_goods',array(
            'goodsid'=>$goodsid,
        ));

        if($strGoods['photo']){
            $strGoods['photo'] = tsXimg($strGoods['photo'],'redeem','150','150',$strGoods['path'],1);
        }else{
            $strGoods['photo'] = SITE_URL.'public/images/redeem.png';
        }

        return $strGoods;

    }
	
}
