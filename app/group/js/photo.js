$(function(){
	var $container = $('#container');
	
	$('#container').infinitescroll({
		
		navSelector  	: "a#page-nav",
		nextSelector 	: "a#page-nav",
		itemSelector 	: ".card-item",
		loading: {
			finishedMsg: '没有内容可以加载啦！',
			img: siteUrl+'public/images/loadingg.gif'
		},
		loadingText     : "内容加载中......"
	}, // trigger Masonry as a callback
	function( newElements ) {
		
		var $newElems = $( newElements );
		
		$container.masonry( 'appended', $newElems);                  
		  
	})

})