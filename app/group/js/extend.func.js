//插入到编辑器
function insertEdit(val)
{
	if(editor){ editor.pasteText(val);}else{ editors.pasteText(val);}
}

/*显示隐藏回复*/
function commentOpen(id,gid)
{
	$('#rcomment_'+id).toggle('fast');
}

/*视频弹出或隐藏播放*/
function showVideo(id,url)
{
	 if($('#play_'+id).is(":hidden")){
		  $('#swf_'+id).html('<object width="500" height="420" id="swf_'+id+'"><param name="allowscriptaccess" value="always"></param><param name="wmode" value="window"></param><param name="movie" value="'+url+'"></param><embed src="'+url+'" width="500" height="420" allowscriptaccess="always" wmode="window" type="application/x-shockwave-flash"></embed></object>')
		  $('#play_'+id).show();
	 }else{
		$('#swf_'+id).find('object').remove();
		$('#play_'+id).hide();
	 }
	$('#img_'+id).toggle();
}

function showMp3(id,url)
{
	$('#mp3swf_'+id).toggle();
	$('#mp3img_'+id).toggle();
}



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

/*显示标签界面*/
function showTagFrom(){	$('#tagFrom').toggle('fast');}
/*提交标签*/
function savaTag(tid)
{
	var tag = $('#tags').val();
		if(tag ==''){ alert('请输入标签哟^_^');$('#tagFrom').show('fast');}else{
			var url = siteUrl+'index.php?app=tag&ac=add_ajax&ts=do';
			$.post(url,{objname:'topic',idname:'topicid',tags:tag,objid:tid},function(rs){  window.location.reload()   })
		}
	
}

//收藏帖子(1.8改为喜欢)
function topic_collect(tid,c){
	
	var url = siteUrl+'index.php?app=group&ac=do&ts=topic_collect';
	$.post(url,{topicid:tid},function(rs){
			if(rs == 0){
				alert('请登录后再喜欢！');return false;
			}else if(rs == 1){
				alert('自己不能喜欢自己的帖子哦^_^');return false;
			}else if(rs == 2){
				alert('你已经喜欢过本帖啦，请不要再次喜欢^_^');return false;
			}else{
				$('#love').html((c+1)+'人喜欢')
				alert('恭喜您，帖子喜欢成功^_^');
				topic_collect_user(tid);
				return false;
			}					
	});
}

//谁收藏了这篇帖子
function topic_collect_user(topicid){
	var url = siteUrl+'index.php?app=group&ac=topic_collect_user&ts=ajax&topicid='+topicid;
	$.post(url,function(rs){ $('#collects').html(rs); });
}


//Ctrl+Enter 回复评论

function keyRecomment(rid,tid,event)
{
     if(event.ctrlKey == true)
	 {
		if(event.keyCode == 13)
		recomment(rid,tid);
		return false;
	}
}

//回复评论
function recomment(rid,tid){

	c = $('#recontent_'+rid).val();
	if(c==''){alert('回复内容不能为空');return false;}
	var url = siteUrl+'index.php?app=group&ac=do&ts=recomment';
	$('#recomm_btn_'+rid).hide();
	$.post(url,{referid:rid,topicid:tid,content:c} ,function(rs){
				if(rs == 0)
				{
					//alert('回复成功');
					window.location.reload();
				}else{
					$('#recomm_btn_'+rid).show();
				}
	})	
}

function newTopicFrom(that)
{
	var title = $(that).find('input[name=title]').val();
	//var typeid = $(that).find('select[name=typeid]').val();
	var content = $(that).find('input[name=content]').val();
	if(title == '' || content == ''){alert('请填写标题和内容'); return false;}
	//if(typeid == 0){alert('请选择分类'); return false;}
	$(that).find('input[type=submit]').val('正在提交^_^').attr('disabled',true);
}

// 在光标处插入字符串 
// myField 文本框对象 
// 要插入的值 
function insertAtCursor(myField, myValue) 
{ 
	//IE support 
	if (document.selection) { 
		myField.focus(); 
		sel = document.selection.createRange(); 
		sel.text = myValue; 
		sel.select(); 
	} 
	//MOZILLA/NETSCAPE support 
	else if (myField.selectionStart || myField.selectionStart == '0') { 
		var startPos = myField.selectionStart; 
		var endPos = myField.selectionEnd; 
		// save scrollTop before insert 
		var restoreTop = myField.scrollTop; 
		myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos,myField.value.length); 
		if (restoreTop > 0) { 
			// restore previous scrollTop 
			myField.scrollTop = restoreTop; 
		} 
		myField.focus(); 
		myField.selectionStart = startPos + myValue.length; 
		myField.selectionEnd = startPos + myValue.length; 
	} else { 
		myField.value += myValue; 
		myField.focus(); 
	} 
} 