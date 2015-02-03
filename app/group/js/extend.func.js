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

//收藏帖子(1.8改为喜欢)
function loveTopic(tid){
	
	var url = siteUrl+'index.php?app=group&ac=do&ts=topic_collect';
	$.post(url,{topicid:tid},function(rs){
			if(rs == 0){
				art.dialog({
					content : '请登录后再喜欢！',
					time : 1000
				})
			}else if(rs == 1){
				art.dialog({
					content : '自己不能喜欢自己的帖子哦',
					time : 1000
				})		
			
			}else if(rs == 2){
				art.dialog({
					content : '你已经喜欢过本帖啦，请不要再次喜欢',
					time : 1000
				})	
			}else{
				window.location.reload();
			}					
	});
}

//淘帖子,加专辑
function taoalbum(topicid){
	$.post(siteUrl+'index.php?app=group&ac=album&ts=topic',{'topicid':topicid},function(rs){
		if(rs==0){
			art.dialog({
				content : '请登陆后再进行淘帖',
				time : 1000
			})
		}else if(rs == 1){
			art.dialog({
				content : '请创建专辑后再进行淘贴',
				time : 1000
			})
		}else {
			art.dialog({
				content : rs
			})
		}
	})
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
function recomment(rid,tid,token){

	c = $('#recontent_'+rid).val();
	if(c==''){alert('回复内容不能为空');return false;}
	var url = siteUrl+'index.php?app=group&ac=do&ts=recomment';
	$('#recomm_btn_'+rid).hide();
	$.post(url,{referid:rid,topicid:tid,content:c,'token':token} ,function(rs){
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

//向下加载更多帖子
function loadTopic(userid,page){
	var num = parseInt(page)+1;
	$("#viewmore").html('<img src="'+siteUrl+'public/images/loading.gif" />');
	$.get(siteUrl+'index.php?app=group&ac=ajax&ts=topic',{'userid':userid,'page':page},function(rs){
		if(rs==''){
			$("#viewmore").html('没有可以加载的内容啦...');
		}else{
			$("#before").before(rs);
			$("#viewmore").html('<a class="btn" href="javascript:void(0);" onclick="loadTopic('+userid+','+num+')">查看更多内容......</a>');
		}
	})
}

//帖子审核
function topicAudit(topicid,token){
	$.post(siteUrl+'index.php?app=group&ac=ajax&ts=topicaudit',{'topicid':topicid,'token':token},function(rs){
		if(rs==0){
			art.dialog({
				content : '帖子取消审核成功！',
				time : 1000
			})
			
			window.location.reload();
			return false;
		}else if(rs==1){
		
			art.dialog({
				content : '帖子审核成功！',
				time : 1000
			})
			
			window.location.reload();
			return false;
		
		}else if(rs==2){
		
			art.dialog({
				content : '非法操作！',
				time : 1000
			})
		
		}
	})
}

//设为管理员，取消管理员
function setAdmin(groupid,userid,token){

	$.post(siteUrl+'index.php?app=group&ac=user&ts=manager',{'groupid':groupid,'userid':userid,'token':token},function(rs){
	
		if(rs=='0'){
			art.dialog({
				content : '非法操作',
				time : 1000
			})
		}else if(rs=='1'){
		
			art.dialog({
				content : '操作成功！',
				time : 1000
			});
			
			window.location.reload();
		
		}
	
	})
	
}

//踢出小组
function kickedGroup(groupid,userid){
	$.post(siteUrl+'index.php?app=group&ac=kicked',{'groupid':groupid,'userid':userid},function(rs){
		if(rs=='0'){
			art.dialog({
				content : '非法操作',
				time : 1000
			});
		}else if(rs=='1'){
			art.dialog({
				content : '非法操作',
				time : 1000
			});
		}else{
			window.location.reload();
		}
	})
	
}

//加入小组
function joinGroup(groupid){
	tsPost('index.php?app=group&ac=ajax&ts=joingroup',{'groupid':groupid});
}
function exitGroup(groupid){
	tsPost('index.php?app=group&ac=ajax&ts=exitgroup',{'groupid':groupid});
}