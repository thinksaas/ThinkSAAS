/*!刷新验证码*/
function newgdcode(obj, url) {
    obj.src = url + "&nowtime=" + new Date().getTime()
}
/*!搜索点击*/
function searchon() {
    $("#searchto").click()
}
/*!用户关注*/
function follow(userid, token) {
    $.getJSON(siteUrl + "index.php?app=user&ac=follow&ts=do", {
        "userid": userid,
        "token": token
    },
    function(json) {
        if (json.status == 0) {
            art.dialog({
				lock: true,
                content: json.msg,
                time: 2000
            })
        } else {
            if (json.status == 1) {
                art.dialog({
					lock: true,
                    content: json.msg,
                    time: 2000
                })
            } else {
                if (json.status == 2) {
                    art.dialog({
						lock: true,
                        content: json.msg,
                        time: 2000
                    });
                    window.location.reload()
                }
            }
        }
    })
}
/*!取消用户关注*/
function unfollow(userid, token) {
    $.getJSON(siteUrl + "index.php?app=user&ac=follow&ts=un", {
        "userid": userid,
        "token": token
    },
    function(json) {
        if (json.status == 0) {
            art.dialog({
				lock: true,
                content: json.msg,
                time: 2000
            })
        } else {
            if (json.status == 1) {
                art.dialog({
					lock: true,
                    content: json.msg,
                    time: 2000
                });
                window.location.reload()
            }
        }
    })
}
/*!显示视频*/
function showVideo(id, url) {
    if ($("#play_" + id).is(":hidden")) {
        $("#swf_" + id).html('<object width="500" height="420" id="swf_' + id + '"><param name="allowscriptaccess" value="always"></param><param name="wmode" value="window"></param><param name="movie" value="' + url + '"></param><embed src="' + url + '" width="500" height="420" allowscriptaccess="always" wmode="window" type="application/x-shockwave-flash"></embed></object>');
        $("#play_" + id).show()
    } else {
        $("#swf_" + id).find("object").remove();
        $("#play_" + id).hide()
    }
    $("#img_" + id).toggle()
}
/*!Jquery input输入框提示插件*/
(function($) {
    $.fn.inputDefault = function(options) {
        var defaults = {
            attrName: "fs",
            size: 0,
            bold: false,
            italic: false,
            color: "#CCC"
        };
        var options = $.extend(defaults, options);
        this.each(function() {
            var $this = $(this);
            var text = $this.attr(options.attrName);
            var offset = $this.position();
            var outerWidth = $this.outerWidth();
            var outerHeight = $this.outerHeight();
            var innerWidth = $this.innerWidth();
            var innerHeight = $this.innerHeight();
            var plusLeft = (outerWidth - innerWidth) / 2;
            var plusTop = (outerHeight - innerHeight) / 2;
            var paddingTop = parseInt($this.css("paddingTop"));
            var paddingRight = parseInt($this.css("paddingRight"));
            var paddingBottom = parseInt($this.css("paddingBottom"));
            var paddingLeft = parseInt($this.css("paddingLeft"));
            if (!$.browser.chrome) {
                var width = innerWidth - (paddingLeft + paddingRight);
                var height = innerHeight - (paddingTop + paddingBottom)
            } else {
                var width = innerWidth - paddingRight;
                var height = innerHeight - paddingBottom
            }
            var top = offset.top + plusTop;
            var left = offset.left + plusLeft;
            var lineHeight = $this.css("lineHeight");
            var display = $this.val() ? "none": "block";
            var fontSize = options.size ? options.size: $this.css("fontSize");
            var fontStyle = options.italic ? "italic": "";
            var fontWeight = options.bold ? "700": $this.css("fontWeight");
            var css = {
                position: "absolute",
                fontSize: fontSize,
                fontWeight: fontWeight,
                fontStyle: fontStyle,
                lineHeight: lineHeight,
                display: display,
                paddingTop: paddingTop,
                paddingRight: paddingRight,
                paddingBottom: paddingBottom,
                paddingLeft: paddingLeft,
                cursor: "text",
                width: width,
                height: height,
                top: top,
                left: left,
                color: options.color,
                overflow: "hidden"
            };
            var lable = $("<label></label>").text(text).css(css).click(function() {
                $(this).hide();
                $(this).prev().focus()
            });
            $this.after(lable)
        }).focus(function() {
            var $this = $(this);
            var $label = $(this).next("label");
            $label.hide()
        }).blur(function() {
            var $this = $(this);
            var $label = $(this).next("label");
            if (!$this.val()) {
                $label.show()
            }
        })
    }
})(jQuery);
$(function() {
    $("[fs]").inputDefault()
});