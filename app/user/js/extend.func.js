/*显示隐藏回复*/
function reguest(userid,reid,username)
{
	$("#reguest").toggle('fast');
	$("#reguest textarea").val('@'+username+'#');
	$("#reguest #touserid").val(userid);
	$("#reguest #reid").val(reid);
	$("#reguest textarea").focus();
}