//发送盒子
function sendbox(userid){

$("#msgbox").html("加载消息中......")
$("#sendbox").html("加载输入框中......")

	$.ajax({
		type: "GET",
		url:  siteUrl+"index.php?app=message&ac=msgbox&userid="+userid,
		success: function(msg){
			$('#msgbox').html(msg);
			
			var msgbox=document.getElementById('msgbox');
			if(msgbox.scrollHeight>msgbox.offsetHeight) msgbox.scrollTop=msgbox.scrollHeight-msgbox.offsetHeight+20;
			
		}
	});

	$.ajax({
		type: "GET",
		url:  siteUrl+"index.php?app=message&ac=sendbox&userid="+userid,
		success: function(msg){
			$('#sendbox').html(msg);
		}
	});
}

//发送消息
function sendmsg(userid,touserid){
	var content = $("#boxcontent").val();
	if(content == ''){
		alert("请输入你要发送的内容！");return false;
	}
	//清空内容
	$("#boxcontent").attr("value",'');
	$("#sendbutton").css('display','none');
	$("#loading").css('display','block');

	
	$.ajax({
		type: "POST",
		url: siteUrl+"index.php?app=message&ac=sendmsg",
		data: "userid="+userid+"&touserid="+touserid+"&content="+content,
		beforeSend: function(){},
		success: function(result){
			if(result == '1'){
				$("#loading").css('display','none');
				$("#sendbutton").css('display','block');
				
				window.location.reload();
				
			}
			
		}
	});
}

//系统消息盒子
function systembox(userid){
	$("#sendbox").html("");
	$("#msgbox").html("加载系统消息中......")
	$.ajax({
		type: "GET",
		url:  siteUrl+"index.php?app=message&ac=systembox&userid="+userid,
		success: function(msg){
			$('#msgbox').html(msg);
			var msgbox=document.getElementById('msgbox');
			if(msgbox.scrollHeight>msgbox.offsetHeight) msgbox.scrollTop=msgbox.scrollHeight-msgbox.offsetHeight+20;
			
		}
	});
}