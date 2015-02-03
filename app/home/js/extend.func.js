function selectTheme(theme){
	var date=new Date();
	var expireDays=10;
	//将date设置为10天以后的时间
	date.setTime(date.getTime()+expireDays*24*3600*1000);
	$('#tsTheme').attr('href',siteUrl+'theme/'+theme+'/style.css');
	document.cookie="tstheme="+theme+";path=/;expire="+date.toGMTString(); 
}