<?php

class Vaptcha{

  // 验证单元vid
  public static $vid = '';
  // 验证单元key
  public static $key = '';
  // 二次验证地址
  private static $validata_url = 'http://0.vaptcha.com/verify';
  // offline_key地址
  private static $offline_url = 'https://channel2.vaptcha.com/config/';

  private static $offline_imgurl = 'https://offline.vaptcha.com/';

  function __construct($vid1,$key1){
    self::$vid = $vid1;
    self::$key = $key1;
  }

  /**
   * 二次验证
   * @param String $knock 流水号
   * @param String $token 验证成功后返回的标记
   * @param Int $scene 场景号
   * @return void
   */
  public static function validate($token, $scene = 0)
  {
    $str = 'ffline-';
    if(empty($scene)) $scene = 0;
    if(strpos($token, $str, 0)==1) {
      $res = self::offlineValidate($token);
      if($res == 1) {
        $data = json_encode(array(
          'success' => 1
        ));
      } else {
        $data = json_encode(array(
          'success' => 0,
          'msg' => "二次验证失败"
        ));
      }
      return $data;
    } else {
      return self::normalValidate($token, $scene);
    }
  }

  public static function normalValidate($token, $scene)
  {
    if(empty($token)) return false;
    $query = array(
      'id' => self::$vid,
      'secretkey' => self::$key,
      'scene' => $scene,
      'token' => $token,
      'ip' => self::getClientIp()
    );
    $response = self::_post(self::$validata_url, $query);
    return $response;
  }
  
  private static function offlineValidate($token)
  {
    $strs = explode('-', $token);
    if (count($strs) < 2) {
      return false;
    } else {
      $time = (int) $strs[count($strs) - 1];
      $storageTIme = self::get($token);
      $now = self::getCurrentTime();
      if ($now - $time > 600000) {
        return false;
      } else {
        if ($storageTIme && $storageTIme==$time) {
            return true;
        } else {
            return false;
        }
      }
    }
  }

  public static function getChannelData()
  {
    $url = self::$offline_url. self::$vid;
    //前端mode: offline时 请求地址：
    // $url = self::$offline_url. 'offline';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    return $data;
  }

  private static function getOfflineCaptcha($callback)
  {
    $time = self::getCurrentTime();
    $md5 = md5($time . self::$key);
    $captcha = substr($md5, 0, 3);
    $data = self::getChannelData();
    $knock = md5($captcha . $time);
    $ul = self::getImgUrl();
    $url = md5($data['offline_key'] . $ul);
    // self::$imgid = $url;
    self::set($knock, $url);
    return $callback===null?array(
        "time" => $time,
        "url" => $url,
    ): $callback.'('.json_encode(array(
        "time" => $time,
        "imgid" => $url,
        "code" => '0103',
        "knock" => $knock,
        "msg" => "",
    )).')';
  }

  private static function offlineCheck($callback, $v, $knock)
  {
    $data = self::getChannelData();
    $offline_key = $data['offline_key'];
    $imgs = self::get($knock);
    self::delete($knock);
    $address = md5($v.$imgs);
    $url = self::$offline_imgurl.$offline_key.'/'.$address;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpCode == 200) {
        $token = 'offline-'.$knock.'-'.self::create_uuid().'-'.self::getCurrentTime();;
        self::set($token,self::getCurrentTime());
        return $callback.'('.json_encode(array(
            "code" => '0103',
            "msg" => "",
            "token" => $token
        )).')';
    }
    else {
        return $callback.'('.json_encode(array(
            "code" => '0104',
            "msg" => "0104",
            "token" => "",
            "v" => $v,
        )).')';
    }
  }

  public static function offline($data, $callback, $v = null, $knock = null)
  {
    if (!$data) {
      return array("error" => "params error");
    }
    switch($data) {
      case 'get': 
        return self::getOfflineCaptcha($callback);
      case 'verify':
        if ($v == null) {
            return array("error" => "params error");
        } else {
            return self::offlineCheck($callback ,$v, $knock);
        }
      default:
        return array("error" => "parms error");
    }
  }

  private static function _post($url, $data)
  {
    if (function_exists('curl_exec')) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      $errno = curl_errno($ch);
      $response = curl_exec($ch);
      curl_close($ch);
      return $errno > 0 ? 'error' : $response;
    } else {
      $opts = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data,
            'timeout' => 5 * 1000,
        ),
        'content' => $data,
      );
      $context = stream_context_create($opts);
      $response = @file_get_contents($url, false, $context);
      return $response ? $response : 'error';
    }
  }

  private static function getCurrentTime()
  {
      return number_format(floor(microtime(true)), 0, '', '');
  }

  private static function getImgUrl()
  {
      $str = '0123456789abcdef';
      $data = '';
      for ($i=0; $i < 4; $i++) { 
          # code...
          $data = $data.$str[rand(0, 15)];
      }
      return $data;
  }

  public static function set($key, $value, $expire = 600)
    {
        return $_SESSION[$key] = array(
            'value' => $value,
            'create' => time(),
            'readcount' => 0,
            'expire' => $expire,
        );
    }

    public static function get($key, $default = null)
    {
        $data = $_SESSION[$key];
        $now = time();
        if (!$data) {
            return $default;
        } else if ($now - $data['create'] > $data['expire']) {
            return $default;
        } else {
            $_SESSION[$key]['readcount']++;
            return $data['value'];
        }
    }

    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public static function create_uuid($prefix = ""){
      $str = md5(uniqid(mt_rand(), true));   
      $uuid  = substr($str,0,8) . '-';   
      $uuid .= substr($str,8,4) . '-';   
      $uuid .= substr($str,12,4) . '-';   
      $uuid .= substr($str,16,4) . '-';   
      $uuid .= substr($str,20,12);   
      return $prefix . $uuid;
  }

  public static function getClientIp()
  {
    if (getenv('HTTP_X_REAL_IP')) {
        $ip = getenv('HTTP_X_REAL_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ip);
        $ip = $ips[0];
    } elseif (getenv('REMOTE_ADDR')) {
        $ip = getenv('REMOTE_ADDR');
    } else {
        $ip = '0.0.0.0';
    }

    return $ip;
  }

}