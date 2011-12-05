//删除小组
function group_del(gid){
	if(confirm("确定删除吗?")){
		var url = siteUrl+'index.php?app=group&ac=admin&mg=group&ts=del';
		$.post(url,{groupid:gid},function(rs){
					if(rs == 0){
						window.location.reload(); 
					}
		})	
	}
}

function cate_del(cid){
	if(confirm("确定删除吗?")){
		var url = siteUrl+'index.php?app=group&ac=admin&mg=cate&ts=del';
		$.post(url,{cateid:cid},function(rs){
					if(rs == 0){
						window.location.reload(); 
					}
		})	
	}
}