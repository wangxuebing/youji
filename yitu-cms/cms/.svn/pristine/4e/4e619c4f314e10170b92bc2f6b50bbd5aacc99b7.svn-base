loginedModel = [
    '<div class="logged">' +
      '<span class="logout" id="logout">退出</span>' +
      '<span class="username">名字</span>' +
    '</div>'
].join();
notLoginModel = [
  '<span class="not-logged">未登录</span>'
].join();

//格式化format
String.prototype.format = function(args){ 
  if (arguments.length>0) { 
    result = this; 
    if (arguments.length == 1 && typeof (args) == "object"){ 
      for (var key in args) { 
        var reg=new RegExp ("({"+key+"})","g"); 
        result = result.replace(reg, args[key]); 
      } 
    }else{ 
      for (var i = 0; i < arguments.length; i++){ 
        if(arguments[i]==undefined){ 
          return ""; 
        }else{ 
          var reg=new RegExp ("({["+i+"]})","g"); 
          result = result.replace(reg, arguments[i]); 
        } 
      } 
    } 
    return result; 
  }else{ 
    return this; 
  } 
}