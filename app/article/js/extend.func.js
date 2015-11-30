function recommend(articleid){
	$.post(siteUrl+'index.php?app=article&ac=recommend',{'articleid':articleid},function(rs){
		if(rs==0){

            tsNotice('请登陆后再推荐');

		}else if(rs == 1){

            tsNotice('你已经推荐过');

		}else if(rs == 2){

			window.location.reload()
		}
	})
}