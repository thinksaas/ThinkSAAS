<?php 
defined('IN_TS') or die('Access Denied.');

class tsSession { 
     /** 
      * a database connection resource 
      * @var resource 
      */ 
     private static $_sess_db; 
  
     /** 
      * Open the session 
      * @return bool 
      */ 
     public static function open() { 
          
         if (self::$_sess_db = mysql_connect('localhost', 'root', '123456')) { 
             return mysql_select_db('thinksaas2', self::$_sess_db); 
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
         $sql = sprintf("SELECT `session_data` FROM `ts_session` WHERE `session` = '%s'", $id); 
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
     public static function write($id, $data) { 
         $sql = sprintf("REPLACE INTO `ts_session` VALUES('%s', '%s', '%s', '%s')", 
                        mysql_real_escape_string($id), 
                        mysql_real_escape_string(time()), 
                        mysql_real_escape_string(getIp()), 
                        mysql_real_escape_string($data) 
                        ); 
         return mysql_query($sql, self::$_sess_db); 
     } 
  
     /** 
      * Destoroy the session 
      * @param int session id 
      * @return bool 
      */ 
     public static function destroy($id) { 
         $sql = sprintf("DELETE FROM `ts_session` WHERE `session` = '%s'", $id); 
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
         $sql = sprintf("DELETE FROM `ts_session` WHERE `session_expires`  < '%s'", 
                        mysql_real_escape_string(time() - $max)); 
         return mysql_query($sql, self::$_sess_db); 
     } 
 }