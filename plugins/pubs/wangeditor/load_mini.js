var E = window.wangEditor
var editor = new E('#tseditor','#tseditor2')
// 或者 var editor = new E( document.getElementById('editor') )

// 自定义菜单配置
editor.customConfig.menus = [
    'bold',  // 粗体
    'foreColor',  // 文字颜色
    'link',  // 插入链接
    'emoticon',  // 表情
]

editor.customConfig.zIndex = 100

var content = $('textarea[name="content"]')
editor.customConfig.onchange = function (html) {
    // 监控变化，同步更新到 textarea
    content.val(filterXSS(html))
}

editor.create()