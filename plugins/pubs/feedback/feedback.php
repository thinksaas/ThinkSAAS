<?php 
//feedback反馈插件 
function feedback_html(){
	global $tsMySqlCache;
	$code = fileRead('data/plugins_pubs_feedback.php');
	
	if($code==''){
		$code = $tsMySqlCache->get('plugins_pubs_feedback');
	}
	
	echo '<div class="feedback-box">
'.stripslashes($code).'
</div>';
}

addAction('pub_footer','feedback_html');

function feedback_css(){
	echo '<style>.feedback-box {
    background-color: #83ACC6;
    border-right: 1px solid #D3E3F0;
    bottom: 60%;
    opacity: 0.6;
    padding: 1px 0;
    position: fixed;
    right: 0;
    width: 30px;
    text-align:center;
}
.feedback-box:hover {
    background-color: #558BC6;
    
    opacity: 1;
}
.feedback-box:hover a {
    border-left-color: #558BC6;
}
.feedback-box a {
    color: #FFFFFF !important;
    display: block;
    padding: 5px;
    text-decoration: none;
}</style>';
}

addAction('pub_header_top','feedback_css');