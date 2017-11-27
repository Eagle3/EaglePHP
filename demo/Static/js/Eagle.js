(function() {
	
	///////////////////////////////////////////////////  Eagle对象的父对象 EagleParent  开始  ////////////////////////////////////////////////
	function EagleParent() {
		//测试继承，第3优先调用此处的   test_extend
		this.testExtend = function(){
			return '第3优先调用：父对象构造方法中的方法';
		};
		
	}
	
	EagleParent.prototype = {
			constructor : EagleParent,
			
			//测试继承，第4优先调用构造函数原型属性中的test_extend
			testExtend : function(){
				return '第4优先调用：父对象构造方法原型属性中的方法';
			},
	};
	///////////////////////////////////////////////////  Eagle对象的父对象 EagleParent  结束  ////////////////////////////////////////////////
	
	///////////////////////////////////////////////////  Eagle对象（继承EagleParent）  开始 //////////////////////////////////////////////////
	function Eagle() {
		this.ajax = {
				method : 'GET'
		};
		
		//测试继承，第1优先调用此处方法
		this.testExtend = function(){
			return '第1优先调用：本对象构造函数中的方法';
		};
	}
	
	Eagle.prototype = {
		constructor : Eagle,
		
		//测试继承，第2优先调用此处方法
		testExtend : function(){
			return '第2优先调用：本构造函数原型属性中的方法';
		},
		
		//扩展Eagle.prototype方法接口
		extMethods : function (obj) {
			if(!this.isEmptyObject(obj)){
				for (var o in obj) {
					this.__proto__[o] = obj[o];
				}
			}
			return this;
		},
		
		/////////////////////////////////////////////// 杂项 /////////////////////////////////////////////////////////
		
		//json转对象
		jsonToObj : function(jsonStr) {
			if (typeof jsonStr == "string") {
				return window.JSON ? JSON.parse(jsonStr) : eval("(" + jsonStr + ")");
			}
			return jsonStr;
		},
		
		//对象json转json
		ObjToJson : function(Obj) {
			return typeof Obj == "string" ? JSON.stringify(Obj) : Obj;
		},
		
		//判断是不是空对象
		isEmptyObject : function(e) {
		    var t;  
		    for (t in e)  {
		    	 return !1;  
		    }
		    return !0  
		},
		
		//判断是PC还是移动设备
		isPC : function() {
		    var userAgentInfo = navigator.userAgent;
		    var Agents = ["Android", "iPhone",
		                "SymbianOS", "Windows Phone",
		                "iPad", "iPod"];
		    var flag = true;
		    for (var v = 0; v < Agents.length; v++) {
		        if (userAgentInfo.indexOf(Agents[v]) > 0) {
		            flag = false;
		            break;
		        }
		    }
		    return flag;
		},
		
		//判断是安卓还是IOS
		androidOrIOS : function() {
			var u = navigator.userAgent;
		    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1;
		    var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
		    if (isAndroid) {
		       return 'android';
		    }
		    if (isIOS) {
		    	return 'ios';
		    }
		},
		
		//判断客户端是PC/微信/移动端
		getClientInfo : function(){
	    	var sUserAgent = window.navigator.userAgent.toLowerCase();
			var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
			var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
			var bIsMidp = sUserAgent.match(/midp/i) == "midp";
			var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
			var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
			var bIsAndroid = sUserAgent.match(/android/i) == "android";
			var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
			var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
			if(sUserAgent.search(/micromessenger/i) > -1){
				return 'weixin';
			}else if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
				return 'mobile';
			} else {
				return 'pc';
			}
	    },
		
		//判断是不是数组
		isArray : function(arr){
			return Object.prototype.toString.call(arr)=='[object Array]';
		},
		
		//删除某个元素
		removeElem : function(elem){
			var parent = elem.parentNode;
	        if(parent && parent.nodeType !== 11) {
	            parent.removeChild(elem);
	        }
		},
		
		//当前时间戳  毫秒 或 秒
		nowTimestamp : function(flag){
			if(flag){
				return parseInt((new Date()).getTime() / 1000); //秒
			}
			return parseInt((new Date()).getTime()); //毫秒
		},
		
		//当前时间 10:45:20
		nowTime : function(){
			return new Date().toLocaleTimeString();
		},
		
		//当前日期格式 2017-08-08 10:10:10,也可根据传入时间戳转成时间格式，单位秒
		nowDate : function(timestamp){
			var d = new Date();
			if(timestamp){
				var len = (timestamp.toString()).length;
				if(len != 13 && len != 10){
					timestamp = parseInt((timestamp.toString()).substr(0,10)) * 1000;
				}else if(len == 10){
					timestamp = timestamp * 1000;
				}
				d.setTime(timestamp);
			}
			var dateFormat = '';
			var year = d.getFullYear()
			var month = d.getMonth() + 1; 
			month = month < 10 ? '0' + month : month;
			var day = d.getDate();
			day = day < 10 ? '0' + day : day;
			var hour = d.getHours();
			hour = hour < 10 ? '0' + hour : hour;
			var minute = d.getMinutes();
			minute = minute < 10 ? '0' + minute : minute;
			var second = d.getSeconds();
			second = second < 10 ? '0' + second : second;
			dateFormat = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
			return dateFormat;
		},
		
		//时间格式转为时间戳
		dateToTimestamp : function(dateString){
			return parseInt(Date.parse(new Date(dateString)) / 1000);
		},
		
		//时间戳转为时间格式 时间戳可以是毫秒也可以是秒
		timestampToDate : function(timestamp){
			return this.nowDate(timestamp);
		},
		
		//创建ajax对象
		createAjaxObj : function() {
			var xhr = null;
			if (window.XMLHttpRequest) {
				// IE7+, Firefox, Chrome, Opera, Safari
				xhr = new XMLHttpRequest();
			} else {
				// IE5 IE6
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			return xhr;
		},
		
		//调用ajax方法时，url和回调函数 参数必传
		paramIsEmpty : function(obj){
			if( this.isEmptyObject(obj)){
				return true;
			}
			if(!obj.url){
				return true;
			}
			if(!obj.success){
				return true
			}
			return false;
		},
		
		//调用ajax方法时，组装发送的参数
		joinParam : function(url,data){
			var p = '';
			if ( typeof data == "object" && !this.isEmptyObject(data) ) {
				 for (var k in data){
					 p = p + k + '=' + data[k] + '&';
				 }
				 p = p.substring(0, p.lastIndexOf('&'));
			}
			
			if ( typeof data == "string" ) {
				 p = data;
			}
			
			if( this.ajax.method == 'GET' ){
				p = p ? (url.indexOf("?") == -1 ? url + '?' + p : url + '&' + p) : url;
			}
			 
		    return p;
		},
		
		//调用jsonp时组装数据
		parseData : function(data){
			var ret = "";
	        if(typeof data === "string") {
	            ret = data;
	        }else if(typeof data === "object") {
	            for(var key in data) {
	            	//encodeURIComponent() 函数可把字符串作为 URI 组件进行编码。
	                //ret += "&" + key + "=" + encodeURIComponent(data[key]);
	                ret += "&" + key + "=" + data[key];
	            }
	        }
	        // 加个时间戳，防止缓存
	        ret += "&_time=" + this.nowTimestamp();
	        ret = ret.substr(1);
	        return ret;
		},
		
		///////////////////////////////////////////////// 功能 /////////////////////////////////////////////////////////
		
		//ajax GET方法
		ajaxGet : function(obj) {
			var $this = this;
			this.ajax.method = 'GET';
			obj = this.jsonToObj(obj);
			var paramIsEmpty = this.paramIsEmpty(obj);
			if(paramIsEmpty){
				console.log('传入参数不全或为空');
				return false;
			}
			var url = obj.url;
			var data = obj.data;
			var callBack = obj.success;
			
			var xhr = this.createAjaxObj();
			// 在 onreadystatechange 事件中，我们规定当服务器响应已做好被处理的准备时所执行的任务
			// 当 readyState 等于 4 且状态为 200 时，表示响应已就绪，此时会执行函数
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					var res = xhr.responseText;
					res = $this.jsonToObj(res);
					callBack(res);
				} else {
					//console.log('ajax等待中......');
				}
			}
			sendUrl = this.joinParam(url,data);
			xhr.open("GET", sendUrl, true);// GET请求时，send()中的参数为null
			xhr.send();
		},
		
		//ajax POST方法
		ajaxPost : function(obj) {
			var $this = this;
			this.ajax.method = 'POST';
			obj = this.jsonToObj(obj);
			var paramIsEmpty = this.paramIsEmpty(obj);
			if(paramIsEmpty){
				console.log('传入参数不全或为空');
				return false;
			}
			var url = obj.url;
			var data = obj.data;
			var callBack = obj.success;
			
			var xhr = this.createAjaxObj();
			// 在 onreadystatechange 事件中，我们规定当服务器响应已做好被处理的准备时所执行的任务
			// 当 readyState 等于 4 且状态为 200 时，表示响应已就绪，此时会执行函数
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					var res = xhr.responseText;
					//res = $this.jsonToObj(res);
					callBack(res);
					return true;
				} else {
					//console.log('ajax等待中......');
				}
			}
			sendParam = this.joinParam(url,data);
			// POST请求时， send() 方法中规定希望发送的数据
			xhr.open("POST", url, true);
			// 如果需要像 HTML 表单那样 POST 数据，使用 setRequestHeader() 来添加 HTTP
			// 头。(必须加上此语句)
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send(sendParam);
		},
		
		//jsonp 跨域
		jsonp : function(obj) {
			var paramIsEmpty = this.paramIsEmpty(obj);
			if(paramIsEmpty){
				console.log('传入参数不全或为空');
				return false;
			}
			
			var callback = obj.success;
			var jsonpCallback = 'jsonpCallback'+this.nowTimestamp();
			
			var url = obj.url;
			var data = obj.data;
	        url = url + (url.indexOf("?") === -1 ? "?" : "&") + this.parseData(data) + '&callbackfun=eagle.' + jsonpCallback;
	        
	        this[jsonpCallback] = function(data){
	        	callback(data);
	        	delete  this[jsonpCallback];  
	        }
	        
	        var script = document.createElement("script");
	        script.type = "text/javascript";
	        script.src = url;
	        script.id = "id_" + jsonpCallback ;
	        var head = document.getElementsByTagName("head");
	        if(head && head[0]) {
	            head[0].appendChild(script);
	        }
	        var elem = document.getElementById("id_" + jsonpCallback);
	        this.removeElem(elem);
		},
		
		//获取URL参数
		getAllParam : function (){
			var q = (window.location.search).substr(1);
			var rs = {};
			if(q.length > 0){
				var arr = q.split('&');
				var len = arr.length;
				for (var i=0; i<len;i++ ){
					var p = arr[i];
					var item = p.split('=');
					rs[item[0]] = decodeURIComponent(item[1]); 
				}
			}
			return rs;
		},
		
		//写cookie
		setCookie : function(name,value,hours) {
			var cookieStr =  name + "=" + encodeURIComponent(value);
			var d = new Date();
			if(hours){
				d.setTime(this.nowTimestamp() + parseFloat(hours) * 60 * 60 * 1000 + 8 * 60 * 60 * 1000);
				cookieStr = cookieStr + ";expires=" + d.toUTCString(); // ";path=/;domain=test.com";
			}
			document.cookie = cookieStr;			
		},
		
		//查找cookie
		getCookie : function(name) {
			var strCookie = document.cookie;
		    var arrCookie = strCookie.split("; ");
		    for (var i = 0; i < arrCookie.length; i++) {
		        var arr = arrCookie[i].split("=");
		        if (arr[0] == name) {
		            cookieVal = decodeURIComponent(arr[1]);
		            return cookieVal;
		        }
		    }
			try{
				if(window.localStorage){
					var cookieVal = window.localStorage[name];
					if (cookieVal != undefined) {
						cookieVal = decodeURIComponent(cookieVal);
						return cookieVal;
					}
				}
			}catch(e){
				
			}
		    return "";
		},
		
		//清除某个cookie
		delCookie : function(name) {
			var d = new Date();
		    d.setTime(d.getTime() - 1);
		    var cval = this.getCookie(name);
		    if(cval) {
		        document.cookie = name + "=" + cval + ";expires=" + d.toUTCString() ; // + ";path=/;domain=egret-labs.org"
		    }
		    if(window.localStorage) {
		        localStorage.removeItem(name);
		    }
		},
		
		//清除所有cookie
		clearCookie : function() {
			var keys=document.cookie.match(/[^ =;]+(?=\=)/g); 
			if (keys) { 
			for (var i = keys.length; i--;) 
				document.cookie=keys[i]+'=0;expires=' + new Date( 0).toUTCString() 
			} 
		},
		
		//动态追加js文件。scripts 可以是字符串或数组，如：'http://libs.baidu.com/jquery/2.0.0/jquery.min.js' 或 ['http://localhost/note/eagle/static/js/test.js','http://libs.baidu.com/jquery/2.0.0/jquery.min.js']
		loadJs : function(scripts,callback){
			if(!this.isArray(scripts)){
				var script = document.createElement('script');
				script.src = scripts;
				document.getElementsByTagName('head')[0].appendChild(script);
				script.onload = function(){
				    callback();
				}
				return ;
			}else{
				var head = document.getElementsByTagName("head")[0];
				var s = [], last = scripts.length - 1;
				function recursiveLoad(i) {
					s[i] = document.createElement("script");
					s[i].setAttribute("type", "text/javascript");
					s[i].setAttribute("src", scripts[i]);
					head.appendChild(s[i]);
					s[i].onload = function() {
						//this.parentNode.removeChild(this); //加载完再删除节点
						if (i != last) {
							recursiveLoad(i + 1);
						} else {
							callback();
						}
					};
				}
				recursiveLoad(0);
				
				/*if (typeof (scripts) != "object") {
				 	var scripts = [ scripts ]
				 }
				var haveLoaded = true;
				for (var i = 0; i < scripts.length; i++) {
					if (!scripts[i]) {
						haveLoaded = false;
						scripts[i] = 1
					}
				}
				if (haveLoaded) {
					callback();
					return
				}
			  	var HEAD = document.getElementsByTagName("head").item(0)
						|| document.documentElement;
				var s = new Array(), 
				last = scripts.length - 1;
				function recursiveLoad(i) {
					s[i] = document.createElement("script");
					s[i].setAttribute("type", "text/javascript");
					s[i].onload = s[i].onreadystatechange = function() {
						if (!0 || this.readyState == "loaded" || this.readyState == "complete") {
							this.onload = this.onreadystatechange = null;
							//this.parentNode.removeChild(this);
							if (i != last) {
								recursiveLoad(i + 1);
							} else {
								callback();
							}
						}
					};
					s[i].setAttribute("src", scripts[i]);
					HEAD.appendChild(s[i]);
				}
				recursiveLoad(0);*/
				
			}
		},
		
		//获取表单数据
		getFormData : function ( o ){
			var p = '';
			var q = '';
			var r = '';
			
			var inputs = o.getElementsByTagName('input');
			var selects = o.getElementsByTagName('select');
			
			var inlen = inputs.length;
			if( inlen > 0 ){
				for ( i = 0; i < inlen; i++ ){
					var type =  inputs[i].type.toLowerCase();
					switch ( type ) {
						case 'text': case 'password': case 'hidden': case 'submit': case 'color': case 'date': case 'datetime': 
						case 'datetime-local': case 'email': case 'month': case 'number': case 'range': case 'search': case 'url':
							p += inputs[i].name + '=' + inputs[i].value + '&';
						case 'radio': case 'checkbox':
							if( inputs[i].checked == true ){
								p = p + inputs[i].name + '=' + inputs[i].value + '&';
							}
							break;
						default :
							break;
					}
				}
				p = p ? p.substring(0, p.lastIndexOf('&')) : '';
			}
			
			var selen = selects.length;
			if( selen > 0 ){
				for ( i = 0; i < selen; i++ ){
					var options = selects[i];
					var oplen = options.length;
					if( oplen > 0 ){
						var j = 0;
						for ( j = 0; j < oplen; j++ ){
							if( options[j].selected == true ){
								q += selects[i].name + '=' + options[j].value + '&';
							}
						}
					}
				}
				q = q ? ( p ? '&'+q : '' ) : '';
				q = q ? q.substring(0, q.lastIndexOf('&')) : '';
			}
			
			return p + q;
		},
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	};
	///////////////////////////////////////////////////  Eagle对象（继承EagleParent）  结束 //////////////////////////////////////////////////
	
	
	
	
	/**
	 * 调用库方法方式：eagle.ajaxPost(obj);
	 * 第1优先调用本对象构造方法中的方法，第2优先调用：本构造函数原型属性中的方法，第3优先调用：父对象构造方法中的方法，第4优先调用：父对象构造方法原型属性中的方法
	 */	
	window.eagleParent = new EagleParent();
	window.eagle = new Eagle();
	//继承父对象，此行等价 Eagle.prototype.__proto__ = window.eagle_parent;
	window.eagle.__proto__.__proto__ = window.eagleParent; 
	//Eagle.prototype.__proto__ = window.eagle_parent;
	
	//console.log("验证 Eagle.prototype === window.eagle.__proto__ 结果为：");
	//console.log(Eagle.prototype === window.eagle.__proto__);
})();

/*
 * Eagle 库扩展方法
 * 调用eagle.extend()，将扩展的方法注册到库中。也可把下面的代码单独写到JS文件中，此文件一定要在Eagle库加载之后再引入
 */

//加入一些常规正则验证
eagle.extMethods({
	/*
		 * 验证密码格式
		 * password  	输入的密码
		 * rule			验证类型(1：纯字母，2：纯数字，3：字母或数字，4：字母和数字组合，5：字母 或 字母和数字组合，6：数字 或 字母和数字，7：必须是字母、数字和下划线组合，8：必须是字母、数字和特殊字符组合)
		 * min_length	密码最小长度
		 * max_length   密码最大长度
		 */
		checkPwd : function(password,rule,min_length,max_length){
			//第一步：验证密码长度
			var pwd_len = password.length;
			var length_status = pwd_len < min_length || pwd_len > max_length ? false : true;
			if(!length_status){
				return false;
			}
			
			//第二步：验证密码类型
			var res = false;
			switch (rule){
				case 1:
					var reg = /^[a-zA-Z]+$/;
					break;
				case 2:
					var reg = /^[0-9]+$/;
					break;
				case 3:
					var reg = /^[a-zA-Z0-9]+$/;
					break;
				case 4:
					//第一种写法，一个正则
					//var reg = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/;
					//第二种写法，分步实现
					if(!/[a-zA-Z]+/i.test(password)){
						return false;
					}
					if(!/[0-9]+/.test(password)){
						return false;
					}
					if(/[^a-zA-Z0-9]+/.test(password)){
						return false;
					}
					return true;
					break;
				case 5:
					var reg = /(^[a-zA-Z]+$)|(^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$)/;
					break;
				case 6:
					var reg = /(^[0-9]+$)|(^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$)/;
					break;
				case 7:
					//第一种写法，一个正则
					//var reg = /^(?!([a-zA-Z0-9]+|[a-zA-Z_]+|[_])$)[a-zA-Z0-9_]+$/;
					//第二种写法，分步实现
					if(!/[a-zA-Z]+/i.test(password)){
						return false;
					}
					if(!/[0-9]+/.test(password)){
						return false;
					}
					if(!/[_]+/.test(password)){
						return false;
					}
					if(/[^a-zA-Z0-9_]+/.test(password)){
						return false;
					}
					return true;
					break;
				case 8:
					var reg = /^(?!([a-zA-Z\d]+|[a-zA-Z`~\!@#\$%\^&\*\(\)\-_=\+\[\{\]\}\\\|;:\'\",<\.>\/\?]+|[\d`~\!@#\$%\^&\*\(\)\-_=\+\[\{\]\}\\\|;:\'\",<\.>\/\?]+)$)[a-zA-Z\d`~\!@#\$%\^&\*\(\)\-_=\+\[\{\]\}\\\|;:\'\",<\.>\/\?]+$/;
					break;
				default:
					break;
			}
			
			if(reg.test(password)){
				res = true;
			}
			return res;
		},
		
		/*
		 * 验证邮箱
		 * email 输入的邮箱
		 */
		checkEmail : function(email){
			if(!email || email.length <= 0){
				return false;
			}
			var reg=/^[a-z0-9](\w|\.|-)*@([a-z0-9]+-?[a-z0-9]+\.){1,3}[a-z]{2,4}$/i;
			var res = false;
			if(reg.test(email)){
				res = true;
			}
			return res;
		},
		
		/*
		 * 验证手机号
		 * tel 输入的手机号
		 */
		checkTel : function(tel){
			if(tel.length <= 0){
				return false;
			}
			var reg=/^((13)[0-9]{1}|(14)[5|7]{1}|(15)[^4]{1}|(17)[0|7]{1}|(18)[0-9]{1})[0-9]{8}$/;
			
			///^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[\d]{9}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$/
			var res = false;
			if(reg.test(tel)){
				//如果需要可ajax验证
				res = true;
			}
			return res;
		},
		
		/*
		 * 验证身份证号
		 * cardid 输入的身份证号
		 */
		checkCardid:function(cardid){
			if(cardid.length <= 0){
				return false;
			}
			// 身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
			var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
			var res = false;
			if(reg.test(cardid)){
				res = true;
			}
			return res;
		},
		
		/*
		 * 验证QQ号
		 * qq 输入的QQ号
		 */
		checkQq : function(qq){
			if(qq.length <= 0){
				return false;
			}
			//目前QQ最多11位
			var reg = /^[1-9]{1}[0-9]{4,10}$/;
			var res = false;
			if(reg.test(qq)){
				res = true;
			}
			return res;
		},
		
		/*
		 * 验证纯数字QQ邮箱
		 * qq_email 输入的QQ邮箱(只能是数字：例如：123456@qq.com，test123456@qq.com则不符合)
		 */
		checkQqEmail : function(qq_email){
			if(qq_email.length <= 0){
				return false;
			}
			var reg = /^([1-9]{1}[0-9]{4,10})@(qq\.com)$/i;
			var res = false;
			if(reg.test(qq_email)){
				res = true;
			}
			return res;
		},
		
		/*
		 * 验证是不是QQ邮箱
		 * qq_email 输入的QQ邮箱(可能是数字：例如：123456@qq.com，也可能是字母和数字等组合 test123456@qq.com)
		 */
		checkAllQqEmail : function(qq_email){
			if(qq_email.length <= 0){
				return false;
			}
			var reg=/^(([1-9]{1}[0-9]{4,10})|([a-z0-9](\w|\.|-)*))@(qq\.com)$/i;
			var res = false;
			if(reg.test(qq_email)){
				res = true;
			}
			return res;
		},
		
		/**
		 * 验证输入的字符中是否含有中文字符
		 * chinese 	输入的字符
		 */
		checkChinese : function(chinese){
			if(chinese.length <= 0){
				return false;
			}
			var reg = /[\u4e00-\u9fa5]+/;
			var res = false;
			if(reg.test(chinese)){
				res = true;
			}
			return res;
		},
		
		/**
		 * 验证输入的字符必须全部是中文字符
		 * chinese 		输入的字符
		 * min_length	最少几个字符【参数可选】 
		 * max_length   最多几个字符【参数可选】
		 */
		checkAllChinese : function(chinese,min_length,max_length){
			//第一步：判断是否为空
			var chinese_len = chinese.length;
			if(chinese_len <= 0){
				return false;
			}
			//第二步：判断是否输入个数范围
			var length_status = false;
			if(!min_length && !max_length){
				length_status = true;
			}
			if(min_length && !max_length){
				length_status = chinese_len < min_length ? false : true;
			}
			if(min_length && max_length){
				length_status = chinese_len < min_length || chinese_len > max_length ? false : true;
			}
			if(!length_status){
				return false;
			}
			//第三步：判断是否全是中文
			var reg = /^[\u4e00-\u9fa5]+$/;
			var res = false;
			if(reg.test(chinese)){
				res = true;
			}
			return res;
		},

		/**
		 * 验证邮编
		 * post_num 	输入的邮政编码
		 */
		checkPostNum : function(post_num){
			if(/^[0-9]{6}$/.test(post_num)){
				return true;
			}
			return false;
		},
});