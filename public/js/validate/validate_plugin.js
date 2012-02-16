 // 手机号码验证
 jQuery.validator.addMethod("mobile", function(value, element) {
  var length = value.length;
  return this.optional(element) || (length == 11 && /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/.test(value) || /^(((18[0-9]{1})|(15[0-9]{1}))+\d{8})$/.test(value)  || /^(((15[0-9]{1})|(15[0-9]{1}))+\d{8})$/.test(value));
 }, "手机号码格式错误!");
 
  // 电话号码验证
 jQuery.validator.addMethod("phone", function(value, element) {
  //var tel = /^(\d{3,4}-?)?\d{7,9}$/g;
  var tel = /^(\d{3,4}-?)?\d{7,9}$/;
  return this.optional(element) || (tel.test(value));
 }, "电话号码格式错误!");
 
 // 特殊字符验证
 jQuery.validator.addMethod("string", function(value, element) {
  return this.optional(element) || (/^[\u0391-\uFFE5\w]+$/.test(value));
 }, "不允许包含特殊符号!");
 
  // 中文英文验证
 jQuery.validator.addMethod("cnen", function(value, element) {
  return this.optional(element) || (/^[\u4e00-\u9fa5a-zA-Z]+$/.test(value));
 }, "不允许包含特殊符号!");
 
 // 中文英文数字验证
 jQuery.validator.addMethod("cnennum", function(value, element) {
  //return this.optional(element) || /^[\u4e00-\u9fa5a-zA-Z]+$/.test(value);
  return this.optional(element) || (/^[A-Za-z0-9\u4E00-\u9FA5]+$/.test(value));
 }, "中文英文数字!");

  // 增加只能是字母和数字的验证
  jQuery.validator.addMethod("ennum", function(value, element) {
return this.optional(element) || (/^[a-zA-Z0-9]+$/.test(value));
  }, "只能输入英文和数字");
  
    // 网址URL
jQuery.validator.addMethod("enurl", function(value, element) {
return this.optional(element) || (/^[a-zA-z]+://[^\s]/.test(value));
  }, "");
  
  //日期
  jQuery.validator.addMethod("compareDate",function(value, element, param) {
  
     var startDate = $(param).val();
     
     var date1 = parseInt(startDate.replace(/-/g,''));
     
     var date2 = parseInt(value.replace(/-/g,''));

     return this.optional(element) || (date1 < date2);

 },"");
 
 function Is_URL(str_url){ 
        var strRegex = "^((https|http|ftp|rtsp|mms)?://)"  
        + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@  
        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184  
        + "|" // 允许IP和DOMAIN（域名） 
        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.  
        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名  
        + "[a-z]{2,6})" // first level domain- .com or .museum  
        + "(:[0-9]{1,4})?" // 端口- :80  
        + "((/?)|" // a slash isn't required if there is no file name  
        + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";  
        var re=new RegExp(strRegex);  
		//re.test() 
        if (re.test(str_url)){ 
            return (true);  
        }else{  
            return (false);  
        } 
}

  //日期
  jQuery.validator.addMethod("isurl",function(value) {
  
     var strRegex = "^((https|http|ftp|rtsp|mms)?://)"  
        + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@  
        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184  
        + "|" // 允许IP和DOMAIN（域名） 
        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.  
        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名  
        + "[a-z]{2,6})" // first level domain- .com or .museum  
        + "(:[0-9]{1,4})?" // 端口- :80  
        + "((/?)|" // a slash isn't required if there is no file name  
        + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";  
     
		var re=new RegExp(strRegex);  
		//re.test() 
        if (re.test(value)){ 
            return (true);  
        }else{  
            return (false);  
        } 

 });