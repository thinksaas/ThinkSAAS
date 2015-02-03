<?php 
defined('IN_TS') or die('Access Denied.');

class tsSession { 
     /** 
      * a database connection resource 
      * @var resource 
      */ 
     private static $_sess_db; 
	 
	function __destruct() {
		session_write_close();
	}
  
     /** 
      * Open the session 
      * @return bool 
      */ 
     public static function open() { 
         
		 include 'data/config.inc.php';
		 
         if (self::$_sess_db = mysql_connect($TS_DB['host'].':'.$TS_DB['port'], $TS_DB['user'], $TS_DB['pwd'])) { 
             return mysql_select_db($TS_DB['name'], self::$_sess_db); 
         } 
         return false; 
     } 
  
     /** 
      * Close the session 
      * @return bool 
      */ 
     public static function close() { 
         return mysql_close(self::$_sess_db); 
     } 
  
     /** 
      * Read the session 
      * @param int session id 
      * @return string string of the sessoin 
      */ 
     public static function read($id) {
		
         $id = mysql_real_escape_string($id); 
         $sql = sprintf("SELECT `session_data` FROM `".dbprefix."session` WHERE `session` = '%s'", $id); 
		 
		 //echo $sql;
		 
         if ($result = mysql_query($sql, self::$_sess_db)) { 
             if (mysql_num_rows($result)) { 
                 $record = mysql_fetch_assoc($result); 
                 return $record['session_data']; 
             } 
         } 
         return ''; 
     } 
  
     /** 
      * Write the session 
      * @param int session id 
      * @param string data of the session 
      */ 
     public function write($id, $data) { 
		$userid = intval($_SESSION['tsuser']['userid']);
         $sql = sprintf("REPLACE INTO `".dbprefix."session` VALUES('%s', '%s', '%s', '%s', '%s', '%s')", 
                        mysql_real_escape_string($id), 
                        mysql_real_escape_string($userid), 
                        mysql_real_escape_string(time()), 
                        mysql_real_escape_string(getIp()), 
                        mysql_real_escape_string($data) ,
                        time()
                        ); 
		 
         return mysql_query($sql, self::$_sess_db); 
     } 
  
     /** 
      * Destroy the session 
      * @param int session id 
      * @return bool 
      */ 
     public static function destroy($id) { 
         $sql = sprintf("DELETE FROM `".dbprefix."session` WHERE `session` = '%s'", $id); 
		 
         return mysql_query($sql, self::$_sess_db); 
     } 
  
     /** 
      * Garbage Collector 
      * @param int life time (sec.) 
      * @return bool 
      * @see session.gc_divisor      100 
      * @see session.gc_maxlifetime 1440 
      * @see session.gc_probability    1 
      * @usage execution rate 1/100 
      *        (session.gc_probability/session.gc_divisor) 
      */ 
     public static function gc($max) { 
         $sql = sprintf("DELETE FROM `".dbprefix."session` WHERE `session_expires`  < '%s'", 
                        mysql_real_escape_string(time() - $max)); 
         return mysql_query($sql, self::$_sess_db); 
     } 
 }