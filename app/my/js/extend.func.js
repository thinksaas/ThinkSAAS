function selectTheme(theme){
	$('#tsTheme').attr('href',siteUrl+'theme/'+theme+'/style.css');
	document.cookie="tsTheme="+theme+";path=/"; 
}