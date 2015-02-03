/**
 *  author: leunpha
 *  date: 2014-5-1
 *  version 2.0
 *  dependency: jquery.js
 */
$.fn.upload = function (options) {
    options = options || {};
    options.dom = this;
    $.upload(options);
}
$.upload = function (options) {
    var settings = {
        dom: "",
        action: "",
        fileName: "file",
        params: {},
        accept: ".jpg,.png",
        ieSubmitBtnText: "上传",
        complete: function () {
        },
        submit: function () {

        }
    }
    settings = $.extend(settings, options);
    var ele = settings.dom;

    var iframeName = "leunpha_iframe_v" + Math.random() * 100;
    var width = ele.outerWidth();
    var height = ele.outerHeight();
    var iframe = $("<iframe id='"+iframeName+"' name='"+iframeName+"' style='display:none;'></iframe>");
    var form = $("<form></form>");
    form.attr({
        target: iframeName,
        action: settings.action,
        method: "post",
        "class": "ajax_form",
        enctype: "multipart/form-data"
    }).css({
            width: width,
            height: height,
            position: "absolute",
            top: (ele.offset().top),
            left: (ele.offset().left)
        });
    var input = $("<input type='file'/>");
    input.attr({
        accept: settings.accept,
        name: settings.fileName
    })
        .css({
            opacity: 0,
            position: "absolute",
            width: width,
            height: height + "px",
            cursor: "pointer"
        });
    input.change(function () {
        settings.submit.call(form);
        $(this).parent("form").submit();
    });
    form.append(input);
    $("body").append(iframe);
    iframe.after(form);
    for (var param in settings.params) {
        var div = $("<input type='hidden'/>").attr({name: param, value: settings.params[param]});
        input.after(div)
        div = null;
        delete div;
    }
    iframe.load(function () {
        var im = document.getElementById(iframeName);
		
        var text = $(im.contentWindow.document.body).text();
		
        if (text) {
            settings.complete.call(null, text);
        }
    });
}