function recommend(articleid){
	$.post(siteUrl+'index.php?app=article&ac=recommend',{'articleid':articleid},function(rs){
		if(rs==0){
			art.dialog({
				content : '请登陆后再推荐',
				time : 1000
			})
		}else if(rs == 1){
			art.dialog({
				content : '你已经推荐过',
				time : 1000
			})
		}else if(rs == 2){
			art.dialog({
				content : '推荐成功',
				time : 1000
			})
			window.location.reload()
		}
	})
}