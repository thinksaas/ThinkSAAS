function doRedeem(goodsid){

	$.post(siteUrl+'index.php?app=redeem&ac=do&ts=user',{'goodsid':goodsid},function(rs){
		if(rs=='0'){

            tsNotice('请登陆后再兑换');

		}else if(rs=='1'){

            tsNotice('你已经兑换过');
		
		}else if(rs=='2'){

            tsNotice('你的积分不够兑换');
			
		}else if(rs=='3'){
			
            tsNotice('已经兑换过不能再次兑换！');
			
		}else if(rs=='4'){

            //tsNotice('兑换成功')
			window.location.reload()
			
		}
	})

}

//返还积分
function returnscore(goodsid,userid){
	$.post(siteUrl+'index.php?app=redeem&ac=return',{'goodsid':goodsid,'userid':userid},function(rs){
		if(rs=='4'){
            //tsNotice('积分返还成功！')
			window.location.reload()
		}else{
            tsNotice('非法操作！')
		}
	})
}