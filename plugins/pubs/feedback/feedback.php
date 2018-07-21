<?php 
//feedback反馈插件 
function feedback_html(){
	global $tsMySqlCache;
	$code = fileRead('data/plugins_pubs_feedback.php');
	
	if($code==''){
		$code = $tsMySqlCache->get('plugins_pubs_feedback');
	}
	
	echo '<div class="feedback-box">'.stripslashes($code).'<!--<div class="jubao"><a href="'.tsUrl('home','report').'">举报</a></div></div>-->';
}

addAction('pub_footer','feedback_html');

function feedback_css(){
	echo '<style>.feedback-box {
    background-color: #49a5de;
    bottom: 60%;
    padding: 1px 0;
    position: fixed;
    right: 0;
    width: 30px;
    text-align:center;
}

.feedback-box a {
    color: #FFFFFF !important;
    display: block;
    padding: 5px;
    text-decoration: none;
}
.feedback-box .jubao{overflow: hidden;}
.feedback-box .jubao a{padding:0px;float: left;width:100%;text-align: center;background: #336699;font-size: 12px;}
.feedback-box .jubao a:hover{background:#ff6600;}
</style>';
}

addAction('pub_header_top','feedback_css');