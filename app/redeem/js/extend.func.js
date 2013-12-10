function doRedeem(goodsid){

	$.post(siteUrl+'index.php?app=redeem&ac=do&ts=user',{'goodsid':goodsid},function(rs){
		if(rs=='0'){
			art.dialog({
				content : '请登陆后再兑换！',
				time : 1000
			});
		}else if(rs=='1'){
		
			art.dialog({
				content : '你已经兑换过！',
				time : 1000
			});
		
		}else if(rs=='2'){
			
			art.dialog({
				content : '你的积分不够兑换！',
				time : 1000
			});
			
		}else if(rs=='3'){
			
			art.dialog({
				content : '已经兑换过不能再次兑换！',
				time : 1000
			});
			
		}else if(rs=='4'){
			art.dialog({
				content : '兑换成功！',
				time : 1000
			});
			window.location.reload()
			
		}
	})

}

//返还积分
function returnscore(goodsid,userid){
	$.post(siteUrl+'index.php?app=redeem&ac=return',{'goodsid':goodsid,'userid':userid},function(rs){
		if(rs=='4'){
			art.dialog({
				content : '积分返还成功！',
				time : 1000
			});
			window.location.reload()
		}else{
			art.dialog({
				content : '非法操作！',
				time : 1000
			});
		}
	})
}