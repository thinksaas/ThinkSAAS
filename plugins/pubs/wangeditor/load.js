var E = window.wangEditor
var editor = new E('#tseditor')
// 或者 var editor = new E( document.getElementById('editor') )

// 自定义菜单配置
editor.customConfig.menus = [
    'head',  // 标题
    'bold',  // 粗体
    'italic',  // 斜体
    'underline',  // 下划线
    'strikeThrough',  // 删除线
    'foreColor',  // 文字颜色
    'backColor',  // 背景颜色
    'link',  // 插入链接
    'list',  // 列表
    'justify',  // 对齐方式
    'quote',  // 引用
    'image',  // 插入图片
    'table',  // 表格
    'code',  // 插入代码
    'undo',  // 撤销
    'redo'  // 重复
]

editor.customConfig.zIndex = 100

// 配置服务器端地址
editor.customConfig.uploadImgServer = siteUrl+'index.php?app=pubs&ac=editor&ts=photo&js=1'
// 将图片大小限制为 10M
editor.customConfig.uploadImgMaxSize = 10 * 1024 * 1024
// 限制一次最多上传 5 张图片
editor.customConfig.uploadImgMaxLength = 5
editor.customConfig.uploadFileName = 'photo'

var content = $('textarea[name="content"]')
editor.customConfig.onchange = function (html) {
    // 监控变化，同步更新到 textarea
    content.val(filterXSS(html))
}

editor.create()
// 初始化 textarea 的值
content.val(editor.txt.html())