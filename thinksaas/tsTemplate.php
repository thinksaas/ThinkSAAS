<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );
class tsTemplate {
	var $var_regexp = "\@?\\\$[a-zA-Z_][\\\$\w]*(?:\[[\w\-\.\"\'\[\]\$]+\])*";
	var $vtag_regexp = "\<\?php echo (\@?\\\$[a-zA-Z_][\\\$\w]*(?:\[[\w\-\.\"\'\[\]\$]+\])*)\;\?\>";
	var $const_regexp = "\{([\w]+)\}";
	
	/**
	 * 读模板页进行替换后写入到cache页里
	 *
	 * @param string $tplfile
	 *        	：模板源文件地址
	 * @param string $objfile
	 *        	：模板cache文件地址
	 * @return string
	 */
	function complie($tplfile, $objfile) {
		$template = "<?php defined('IN_TS') or die('Access Denied.'); ?>";
		$template .= file_get_contents ( $tplfile );
		$template = $this->parse ( $template );
		makedir ( dirname ( $objfile ) );
		isWriteFile ( $objfile, $template, $mod = 'w', TRUE );
	}
	
	/**
	 * 解析模板标签
	 *
	 * @param string $template
	 *        	：模板源文件内容
	 * @return string
	 */
	function parse($template) {
		
		// 清除模板中换行
		// $template = @preg_replace('/[\n\r\t]/', '', $template);
		
		// BY QIUJUN 2011-10-22 增加tsurl路由模板标签
		$template = @preg_replace ( "/\{tsUrl(.*?)\}/s", "{php echo tsurl\\1}", $template );
		//BY QIUJUN 2014 增加tsTitle过滤标题输出
		$template = @preg_replace ( "/\{tsTitle(.*?)\}/s", "{php echo tsTitle\\1}", $template );
		
		$template = @preg_replace ( "/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template ); // 去除html注释符号<!---->
		$template = @preg_replace ( "/\{($this->var_regexp)\}/", "<?php echo \\1;?>", $template ); // 替换带{}的变量
		$template = @preg_replace ( "/\{($this->const_regexp)\}/", "<?php echo \\1;?>", $template ); // 替换带{}的常量
		$template = @preg_replace ( "/(?<!\<\?php echo |\\\\)$this->var_regexp/", "<?php echo \\0;?>", $template ); // 替换重复的<?php echo
		$template = @preg_replace ( "/\{php (.*?)\}/ies", "\$this->stripvTag('<?php \\1?>')", $template ); // 替换php标签
		$template = @preg_replace ( "/\{for (.*?)\}/ies", "\$this->stripvTag('<?php for(\\1) {?>')", $template ); // 替换for标签
		
		$template = @preg_replace ( "/\{elseif\s+(.+?)\}/ies", "\$this->stripvTag('<?php } elseif (\\1) { ?>')", $template ); // 替换elseif标签
		for($i = 0; $i < 3; $i ++) {
			$template = @preg_replace ( "/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopSection('\\1', '\\2', '\\3', '\\4')", $template );
			$template = @preg_replace ( "/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopSection('\\1', '', '\\2', '\\3')", $template );
		}
		$template = @preg_replace ( "/\{if\s+(.+?)\}/ies", "\$this->stripvTag('<?php if(\\1) { ?>')", $template ); // 替换if标签
		$template = @preg_replace ( "/\{include\s+(.*?)\}/is", "<?php include \\1; ?>", $template ); // 替换include标签
		
		$template = @preg_replace ( "/\{template\s+(\w+?)\}/is", "<?php include template('\\1'); ?>", $template ); // 替换template标签
		$template = @preg_replace ( "/\{block (.*?)\}/ies", "\$this->stripBlock('\\1')", $template ); // 替换block标签
		$template = @preg_replace ( "/\{else\}/is", "<?php } else { ?>", $template ); // 替换else标签
		$template = @preg_replace ( "/\{\/if\}/is", "<?php } ?>", $template ); // 替换/if标签
		$template = @preg_replace ( "/\{\/for\}/is", "<?php } ?>", $template ); // 替换/for标签
		$template = @preg_replace ( "/$this->const_regexp/", "<?php echo \\1;?>", $template ); // note {else} 也符合常量格式，此处要注意先后顺??
		$template = @preg_replace ( "/(\\\$[a-zA-Z_]\w+\[)([a-zA-Z_]\w+)\]/i", "\\1'\\2']", $template ); // 将二维数组替换成带单引号的标准模式
		/* $template = "<?php if(!defined('IN_TS')) exit('Access Denied');?>\r\n$template"; */
		$template = "$template";
		
		return $template;
	}
	
	/**
	 * 正则表达式匹配替换
	 *
	 * @param string $s
	 *        	：
	 * @return string
	 */
	function stripvTag($s) {
		return @preg_replace ( "/$this->vtag_regexp/is", "\\1", str_replace ( "\\\"", '"', $s ) );
	}
	function stripTagQuotes($expr) {
		$expr = @preg_replace ( "/\<\?php echo (\\\$.+?);\?\>/s", "{\\1}", $expr );
		$expr = str_replace ( "\\\"", "\"", @preg_replace ( "/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s", "[\\1]", $expr ) );
		return $expr;
	}
	function stripv($vv) {
		$vv = str_replace ( '<?php', '', $vv );
		$vv = str_replace ( 'echo', '', $vv );
		$vv = str_replace ( ';', '', $vv );
		$vv = str_replace ( '?>', '', $vv );
		return $vv;
	}
	
	/**
	 * 将模板中的块替换成BLOCK函数
	 *
	 * @param string $blockname
	 *        	：
	 * @param string $parameter
	 *        	：
	 * @return string
	 */
	function stripBlock($parameter) {
		return $this->stripTagQuotes ( "<?php Mooblock(\"$parameter\"); ?>" );
	}
	
	/**
	 * 替换模板中的LOOP循环
	 *
	 * @param string $arr
	 *        	：
	 * @param string $k
	 *        	：
	 * @param string $v
	 *        	：
	 * @param string $statement
	 *        	：
	 * @return string
	 */
	function loopSection($arr, $k, $v, $statement) {
		$arr = $this->stripvTag ( $arr );
		$k = $this->stripvTag ( $k );
		$v = $this->stripvTag ( $v );
		$statement = str_replace ( "\\\"", '"', $statement );
		return $k ? "<?php foreach((array)$arr as $k=>$v) {?>$statement<?php }?>" : "<?php foreach((array)$arr as $v) {?>$statement<?php } ?>";
	}
}