var ue = UM.getEditor('tseditor',{
	//这里可以选择自己需要的工具按钮名称,此处仅选择如下七个
	toolbar:['bold italic underline image link unlink removeformat'],
	//focus时自动清空初始化时的内容
	autoClearinitialContent:false,
	//关闭字数统计
	wordCount:false,
	//关闭elementPath
	elementPathEnabled:false,
	//默认的编辑区域高度
	initialFrameHeight:80
	//更多其他参数，请参考umeditor.config.js中的配置项
});