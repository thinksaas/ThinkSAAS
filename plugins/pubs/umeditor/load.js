var ue = UM.getEditor('tseditor',{
	//这里可以选择自己需要的工具按钮名称,此处仅选择如下七个
	toolbar:['undo redo | bold italic underline strikethrough | superscript subscript | forecolor backcolor | removeformat |',
            'insertorderedlist insertunorderedlist | selectall cleardoc paragraph | fontfamily fontsize' ,
            '| justifyleft justifycenter justifyright justifyjustify |',
            'link unlink | emotion image insertvideo  | map',
            '| horizontal print preview fullscreen'],
	//focus时自动清空初始化时的内容
	autoClearinitialContent:false,
	//关闭字数统计
	wordCount:false,
	//关闭elementPath
	elementPathEnabled:false,
	initialFrameWidth:600,
	//默认的编辑区域高度
	initialFrameHeight:200
	//更多其他参数，请参考umeditor.config.js中的配置项
});