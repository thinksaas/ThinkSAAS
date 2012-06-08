//显示和隐藏
function viewcontent(){
	$("#displaycontent").toggle();
	$("#displaytitle").hide();
}
//显示和隐藏
function closecontent(){
	$("#displaycontent").hide();
	$("#displaytitle").toggle();
}
	
	
/*显示隐藏回复*/
function reguest(userid,reid)
{
	$("#reguest").toggle('fast');
	$("#reguest textarea").val('@aaa#');
	$("#reguest #touserid").val(userid);
	$("#reguest #reid").val(reid);
	$("#reguest textarea").focus();
}