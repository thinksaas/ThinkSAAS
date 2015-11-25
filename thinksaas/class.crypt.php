<?php 
defined('IN_TS') or die('Access Denied.');
//加密解密函数
class  crypt{ 

	protected $key = "";    //公钥 

	private function keyED($txt,$encrypt_key) { 
		$encrypt_key = md5($encrypt_key); 
		$ctr=0; 
		$tmp = ""; 
		for ($i=0;$i<strlen($txt);$i++) { 
			if ($ctr==strlen($encrypt_key)){ 
				$ctr=0; 
			} 
			$tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1); 
			$ctr++; 
		} 
		return $tmp; 
	} 

	public function encrypt($txt,$key="") { 
		if(empty($key)){ 
			$key=$this->key; 
		} 
		srand((double)microtime()*1000000); 
		$encrypt_key = md5(rand(0,32000)); 
		$ctr=0; 
		$tmp = ""; 
		for ($i=0;$i<strlen($txt);$i++) { 
			if ($ctr==strlen($encrypt_key)){ 
				$ctr=0; 
			} 
			$tmp.= substr($encrypt_key,$ctr,1) . 
			(substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1)); 
			$ctr++; 
		} 
		return $this->SetToHexString($this->keyED($tmp,$key)); 
	} 

	public function decrypt($txt,$key="") { 
		if(empty($key)){ 
			$key=$this->key; 
		} 

		$txt = $this->UnsetFromHexString($txt);
		
		$txt = $this->keyED($txt,$key); 
		$tmp = ""; 
		for ($i=0;$i<strlen($txt);$i++) { 
			$md5 = substr($txt,$i,1); 
			$i++; 
			$tmp.= (substr($txt,$i,1) ^ $md5); 
		} 
		return $tmp; 
	} 

	public function setKey($key) { 
		if(empty($key)){ 
			return null; 
		} 
		$this->key=$key; 
	} 

	public function getKey() { 
		return $this->key; 
	} 
	
	
	
	//////////////////////////此处一下转16进制
	public function SingleDecToHex($dec)
	{
		$tmp="";
		$dec=$dec%16;
		if($dec<10)
			return $tmp.$dec;
		$arr=array("a","b","c","d","e","f");
		return $tmp.$arr[$dec-10];
	}
	public function SingleHexToDec($hex)
	{
		$v=ord($hex);
		if(47<$v&&$v<58)
			return $v-48;
		if(96<$v&&$v<103)
			return $v-87;
	}
	public function SetToHexString($str)
	{
		if(!$str)return false;
		$tmp="";
		for($i=0;$i<strlen($str);$i++)
		{
			$ord=ord($str[$i]);
			$tmp.=$this->SingleDecToHex(($ord-$ord%16)/16);
			$tmp.=$this->SingleDecToHex($ord%16);
		}
		return $tmp;
	}
	public function UnsetFromHexString($str)
	{
		if(!$str)return false;
		$tmp="";
		for($i=0;$i<strlen($str);$i+=2)
		{
			$tmp.=chr($this->SingleHexToDec(substr($str,$i,1))*16+$this->SingleHexToDec(substr($str,$i+1,1)));
		}
		return $tmp;
	} 
	
/*
echo SetToHexString("hello,大家好123");
echo "<hr>";
echo UnsetFromHexString(SetToHexString("hello,大家好123"));
*/
	

} 



/*
$crypt= new crypt(); 
$enc_text = $crypt->encrypt('123456','wwwthinksaascn2012'); 
$dec_text = $crypt->decrypt($enc_text,'wwwthinksaascn2012'); 
*/