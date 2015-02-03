<?php 
defined('IN_TS') or die('Access Denied.');
//回到顶部插件 

function gotop_html(){
	echo '<script type="text/javascript" src="'.SITE_URL.'plugins/pubs/gotop/jquery.goToTop.js"></script>
<script type="text/javascript">
$(function(){
	//document.execCommand("BackgroundImageCache", false, true);
	$(".go-top").goToTop({});
	$(window).bind("scroll resize",function(){
		$(".go-top").goToTop({});
	});
});
</script>';
}

addAction('pub_footer', 'gotop_html');