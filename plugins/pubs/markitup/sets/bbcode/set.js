// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// BBCode tags example
// http://en.wikipedia.org/wiki/Bbcode
// ----------------------------------------------------------------------------
// Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	previewParserPath:	'~/sets/bbcode/pbb.php', // path to your BBCode parser
	markupSet: [
		{name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Underline', key:'U', openWith:'[u]', closeWith:'[/u]'},
		{separator:'---------------' },
		
		//上传图片
		{name:'Upload', key:'M',beforeInsert:function(markItUp){
			$.fancybox.showActivity();
			$.fancybox({
				'href'			: siteUrl+'index.php?app=photo&ac=ajax&ts=album',
				'autoScale'     	: true,
				'type'				: 'iframe',
				'scrolling'   		: 'no'
			})
		}},
		
		//上传附件
		{name:'Attach', key:'A',beforeInsert:function(markItUp){
			$.fancybox.showActivity();
			$.fancybox({
				'href'			: siteUrl+'index.php?app=attach&ac=ajax&ts=my',
				'autoScale'     	: true,
				'type'				: 'iframe',
				'scrolling'   		: 'no'
			})
		}},
		
		{name:'Picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
		{name:'Link', key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},
		{separator:'---------------' },
		{name:'Size', key:'S', openWith:'[size=[![Text size]!]]', closeWith:'[/size]',
		dropMenu :[
			{name:'Big', openWith:'[size=200]', closeWith:'[/size]' },
			{name:'Normal', openWith:'[size=100]', closeWith:'[/size]' },
			{name:'Small', openWith:'[size=50]', closeWith:'[/size]' }
		]},
/* 		{separator:'---------------' },
		{name:'Bulleted list', openWith:'[list]\n', closeWith:'\n[/list]'},
		{name:'Numeric list', openWith:'[list=[![Starting number]!]]\n', closeWith:'\n[/list]'}, 
		{name:'List item', openWith:'[*] '}, */
		{separator:'---------------' },
		{name:'Quotes', openWith:'[quote]', closeWith:'[/quote]'},
		{name:'Code', openWith:'[code]', closeWith:'[/code]'}, 
		{separator:'---------------' },
		{name:'Clean', className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		/* {name:'Preview', className:"preview", call:'preview' } */
	]
}