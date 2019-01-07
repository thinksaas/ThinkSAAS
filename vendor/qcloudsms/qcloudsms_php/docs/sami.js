
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Qcloud" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Qcloud.html">Qcloud</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Qcloud_Sms" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Qcloud/Sms.html">Sms</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Qcloud_Sms_FileVoiceSender" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/FileVoiceSender.html">FileVoiceSender</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsMobileStatusPuller" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsMobileStatusPuller.html">SmsMobileStatusPuller</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsMultiSender" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsMultiSender.html">SmsMultiSender</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsSenderUtil" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsSenderUtil.html">SmsSenderUtil</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsSingleSender" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsSingleSender.html">SmsSingleSender</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsStatusPuller" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsStatusPuller.html">SmsStatusPuller</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsVoicePromptSender" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsVoicePromptSender.html">SmsVoicePromptSender</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_SmsVoiceVerifyCodeSender" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/SmsVoiceVerifyCodeSender.html">SmsVoiceVerifyCodeSender</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_TtsVoiceSender" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/TtsVoiceSender.html">TtsVoiceSender</a>                    </div>                </li>                            <li data-name="class:Qcloud_Sms_VoiceFileUploader" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Qcloud/Sms/VoiceFileUploader.html">VoiceFileUploader</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Qcloud.html", "name": "Qcloud", "doc": "Namespace Qcloud"},{"type": "Namespace", "link": "Qcloud/Sms.html", "name": "Qcloud\\Sms", "doc": "Namespace Qcloud\\Sms"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/FileVoiceSender.html", "name": "Qcloud\\Sms\\FileVoiceSender", "doc": "&quot;\u6309\u8bed\u97f3\u6587\u4ef6fid\u53d1\u9001\u8bed\u97f3\u901a\u77e5\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\FileVoiceSender", "fromLink": "Qcloud/Sms/FileVoiceSender.html", "link": "Qcloud/Sms/FileVoiceSender.html#method___construct", "name": "Qcloud\\Sms\\FileVoiceSender::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\FileVoiceSender", "fromLink": "Qcloud/Sms/FileVoiceSender.html", "link": "Qcloud/Sms/FileVoiceSender.html#method_send", "name": "Qcloud\\Sms\\FileVoiceSender::send", "doc": "&quot;\u6309\u8bed\u97f3\u6587\u4ef6fid\u53d1\u9001\u8bed\u97f3\u901a\u77e5&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsMobileStatusPuller.html", "name": "Qcloud\\Sms\\SmsMobileStatusPuller", "doc": "&quot;\u62c9\u53d6\u5355\u4e2a\u624b\u673a\u77ed\u4fe1\u72b6\u6001\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsMobileStatusPuller", "fromLink": "Qcloud/Sms/SmsMobileStatusPuller.html", "link": "Qcloud/Sms/SmsMobileStatusPuller.html#method___construct", "name": "Qcloud\\Sms\\SmsMobileStatusPuller::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsMobileStatusPuller", "fromLink": "Qcloud/Sms/SmsMobileStatusPuller.html", "link": "Qcloud/Sms/SmsMobileStatusPuller.html#method_pullCallback", "name": "Qcloud\\Sms\\SmsMobileStatusPuller::pullCallback", "doc": "&quot;\u62c9\u53d6\u56de\u6267\u7ed3\u679c&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsMobileStatusPuller", "fromLink": "Qcloud/Sms/SmsMobileStatusPuller.html", "link": "Qcloud/Sms/SmsMobileStatusPuller.html#method_pullReply", "name": "Qcloud\\Sms\\SmsMobileStatusPuller::pullReply", "doc": "&quot;\u62c9\u53d6\u56de\u590d\u4fe1\u606f&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsMultiSender.html", "name": "Qcloud\\Sms\\SmsMultiSender", "doc": "&quot;\u7fa4\u53d1\u77ed\u4fe1\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsMultiSender", "fromLink": "Qcloud/Sms/SmsMultiSender.html", "link": "Qcloud/Sms/SmsMultiSender.html#method___construct", "name": "Qcloud\\Sms\\SmsMultiSender::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsMultiSender", "fromLink": "Qcloud/Sms/SmsMultiSender.html", "link": "Qcloud/Sms/SmsMultiSender.html#method_send", "name": "Qcloud\\Sms\\SmsMultiSender::send", "doc": "&quot;\u666e\u901a\u7fa4\u53d1&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsMultiSender", "fromLink": "Qcloud/Sms/SmsMultiSender.html", "link": "Qcloud/Sms/SmsMultiSender.html#method_sendWithParam", "name": "Qcloud\\Sms\\SmsMultiSender::sendWithParam", "doc": "&quot;\u6307\u5b9a\u6a21\u677f\u7fa4\u53d1&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsSenderUtil.html", "name": "Qcloud\\Sms\\SmsSenderUtil", "doc": "&quot;\u53d1\u9001Util\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_getRandom", "name": "Qcloud\\Sms\\SmsSenderUtil::getRandom", "doc": "&quot;\u751f\u6210\u968f\u673a\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_calculateSig", "name": "Qcloud\\Sms\\SmsSenderUtil::calculateSig", "doc": "&quot;\u751f\u6210\u7b7e\u540d&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_calculateSigForTemplAndPhoneNumbers", "name": "Qcloud\\Sms\\SmsSenderUtil::calculateSigForTemplAndPhoneNumbers", "doc": "&quot;\u751f\u6210\u7b7e\u540d&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_phoneNumbersToArray", "name": "Qcloud\\Sms\\SmsSenderUtil::phoneNumbersToArray", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_calculateSigForTempl", "name": "Qcloud\\Sms\\SmsSenderUtil::calculateSigForTempl", "doc": "&quot;\u751f\u6210\u7b7e\u540d&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_calculateSigForPuller", "name": "Qcloud\\Sms\\SmsSenderUtil::calculateSigForPuller", "doc": "&quot;\u751f\u6210\u7b7e\u540d&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_calculateAuth", "name": "Qcloud\\Sms\\SmsSenderUtil::calculateAuth", "doc": "&quot;\u751f\u6210\u4e0a\u4f20\u6587\u4ef6\u6388\u6743&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_sha1sum", "name": "Qcloud\\Sms\\SmsSenderUtil::sha1sum", "doc": "&quot;\u751f\u6210sha1sum&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_sendCurlPost", "name": "Qcloud\\Sms\\SmsSenderUtil::sendCurlPost", "doc": "&quot;\u53d1\u9001\u8bf7\u6c42&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSenderUtil", "fromLink": "Qcloud/Sms/SmsSenderUtil.html", "link": "Qcloud/Sms/SmsSenderUtil.html#method_fetch", "name": "Qcloud\\Sms\\SmsSenderUtil::fetch", "doc": "&quot;\u53d1\u9001\u8bf7\u6c42&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsSingleSender.html", "name": "Qcloud\\Sms\\SmsSingleSender", "doc": "&quot;\u5355\u53d1\u77ed\u4fe1\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSingleSender", "fromLink": "Qcloud/Sms/SmsSingleSender.html", "link": "Qcloud/Sms/SmsSingleSender.html#method___construct", "name": "Qcloud\\Sms\\SmsSingleSender::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSingleSender", "fromLink": "Qcloud/Sms/SmsSingleSender.html", "link": "Qcloud/Sms/SmsSingleSender.html#method_send", "name": "Qcloud\\Sms\\SmsSingleSender::send", "doc": "&quot;\u666e\u901a\u5355\u53d1&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsSingleSender", "fromLink": "Qcloud/Sms/SmsSingleSender.html", "link": "Qcloud/Sms/SmsSingleSender.html#method_sendWithParam", "name": "Qcloud\\Sms\\SmsSingleSender::sendWithParam", "doc": "&quot;\u6307\u5b9a\u6a21\u677f\u5355\u53d1&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsStatusPuller.html", "name": "Qcloud\\Sms\\SmsStatusPuller", "doc": "&quot;\u62c9\u53d6\u77ed\u4fe1\u72b6\u6001\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsStatusPuller", "fromLink": "Qcloud/Sms/SmsStatusPuller.html", "link": "Qcloud/Sms/SmsStatusPuller.html#method___construct", "name": "Qcloud\\Sms\\SmsStatusPuller::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsStatusPuller", "fromLink": "Qcloud/Sms/SmsStatusPuller.html", "link": "Qcloud/Sms/SmsStatusPuller.html#method_pullCallback", "name": "Qcloud\\Sms\\SmsStatusPuller::pullCallback", "doc": "&quot;\u62c9\u53d6\u56de\u6267\u7ed3\u679c&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsStatusPuller", "fromLink": "Qcloud/Sms/SmsStatusPuller.html", "link": "Qcloud/Sms/SmsStatusPuller.html#method_pullReply", "name": "Qcloud\\Sms\\SmsStatusPuller::pullReply", "doc": "&quot;\u62c9\u53d6\u56de\u590d\u4fe1\u606f&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsVoicePromptSender.html", "name": "Qcloud\\Sms\\SmsVoicePromptSender", "doc": "&quot;\u53d1\u9001\u8bed\u97f3\u901a\u77e5\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsVoicePromptSender", "fromLink": "Qcloud/Sms/SmsVoicePromptSender.html", "link": "Qcloud/Sms/SmsVoicePromptSender.html#method___construct", "name": "Qcloud\\Sms\\SmsVoicePromptSender::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsVoicePromptSender", "fromLink": "Qcloud/Sms/SmsVoicePromptSender.html", "link": "Qcloud/Sms/SmsVoicePromptSender.html#method_send", "name": "Qcloud\\Sms\\SmsVoicePromptSender::send", "doc": "&quot;\u53d1\u9001\u8bed\u97f3\u901a\u77e5&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/SmsVoiceVerifyCodeSender.html", "name": "Qcloud\\Sms\\SmsVoiceVerifyCodeSender", "doc": "&quot;\u53d1\u9001\u8bed\u97f3\u9a8c\u8bc1\u7801\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\SmsVoiceVerifyCodeSender", "fromLink": "Qcloud/Sms/SmsVoiceVerifyCodeSender.html", "link": "Qcloud/Sms/SmsVoiceVerifyCodeSender.html#method___construct", "name": "Qcloud\\Sms\\SmsVoiceVerifyCodeSender::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\SmsVoiceVerifyCodeSender", "fromLink": "Qcloud/Sms/SmsVoiceVerifyCodeSender.html", "link": "Qcloud/Sms/SmsVoiceVerifyCodeSender.html#method_send", "name": "Qcloud\\Sms\\SmsVoiceVerifyCodeSender::send", "doc": "&quot;\u53d1\u9001\u8bed\u97f3\u9a8c\u8bc1\u7801&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/TtsVoiceSender.html", "name": "Qcloud\\Sms\\TtsVoiceSender", "doc": "&quot;\u6307\u5b9a\u6a21\u677f\u53d1\u9001\u8bed\u97f3\u901a\u77e5\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\TtsVoiceSender", "fromLink": "Qcloud/Sms/TtsVoiceSender.html", "link": "Qcloud/Sms/TtsVoiceSender.html#method___construct", "name": "Qcloud\\Sms\\TtsVoiceSender::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\TtsVoiceSender", "fromLink": "Qcloud/Sms/TtsVoiceSender.html", "link": "Qcloud/Sms/TtsVoiceSender.html#method_send", "name": "Qcloud\\Sms\\TtsVoiceSender::send", "doc": "&quot;\u6307\u5b9a\u6a21\u677f\u53d1\u9001\u8bed\u97f3\u77ed\u4fe1&quot;"},
            
            {"type": "Class", "fromName": "Qcloud\\Sms", "fromLink": "Qcloud/Sms.html", "link": "Qcloud/Sms/VoiceFileUploader.html", "name": "Qcloud\\Sms\\VoiceFileUploader", "doc": "&quot;\u4e0a\u4f20\u8bed\u97f3\u6587\u4ef6\u7c7b&quot;"},
                                                        {"type": "Method", "fromName": "Qcloud\\Sms\\VoiceFileUploader", "fromLink": "Qcloud/Sms/VoiceFileUploader.html", "link": "Qcloud/Sms/VoiceFileUploader.html#method___construct", "name": "Qcloud\\Sms\\VoiceFileUploader::__construct", "doc": "&quot;\u6784\u9020\u51fd\u6570&quot;"},
                    {"type": "Method", "fromName": "Qcloud\\Sms\\VoiceFileUploader", "fromLink": "Qcloud/Sms/VoiceFileUploader.html", "link": "Qcloud/Sms/VoiceFileUploader.html#method_upload", "name": "Qcloud\\Sms\\VoiceFileUploader::upload", "doc": "&quot;\u4e0a\u4f20\u8bed\u97f3\u6587\u4ef6&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


