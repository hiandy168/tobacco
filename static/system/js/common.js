/*
 *  常用JS函数
	(C) 2010 gxfly Inc.	
	$Id: tangjian 2011-11-18 11:10:00   
*/


// 编辑器初始化
var editor;
KindEditor.ready(function(K) {	
	 editor = K.editor({
		 		urlType :'relative'
				});
});


// 加入收藏夹
function addfavorite(){
    if (document.all) {
        window.external.addFavorite('http://www.gxfly.com/','广西飞讯');
    } else if (window.sidebar) {
        window.sidebar.addPanel('广西飞讯', 'http://www.gxfly.com/', '');
    }
}


/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
	if ($("#check_box").attr("checked") == 'checked') {		
		$("input[name='"+name+"']").each(function() {			
			this.checked = true;
		});
	} else {		
		$("input[name='"+name+"']").each(function() {
			this.checked = false;
		});
	}
}

// 全选 反选
function checkall(name) {
	var userAgent = navigator.userAgent.toLowerCase();
	var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
	var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
	var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
	var e = is_ie ? event : checkall.caller.arguments[0];
	obj = is_ie ? e.srcElement : e.target;
	var arr = document.getElementsByName(name);
	var k = arr.length;
	for(var i=0; i<k; i++) {
		arr[i].checked = obj.checked;
	}
}

// 删除
function deletes(name, url) { 
	var ids = '';
	var	hasselect = false;
		
	$("input[name='"+name+"']").each(function() {			
		if(this.checked == true) hasselect = true;
	});
	if (hasselect == false) {
		alert("请先选择要删除的。。。");
		return ;
	}		
	if(confirm('请确认是否删除！')){	
		$("input[name='"+name+"']:checked").each(function() {
			if(ids==''){
				ids = this.value
			} else {
				ids += ',' + this.value;
			}			
			
		});	
		window.location.href = url + "&ids=" +ids;		
	}	
}

//去空格
function trim(str) { 
	var re = /\s*(\S[^\0]*\S)\s*/; 
	re.exec(str); 
	return RegExp.$1; 
}

//是否为合法的Email格式
function isEmail(str)
{
	var myReg = /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/;
	if ( myReg.test(str) ) return true;
	return false;
}

// 是否是 字母、数字、下划线 6-20位 
function isNumber(str) 
{ 
	var patrn=/^(\w){6,20}$/; 
	if (!patrn.exec(str)) return false; 
	return true;
} 

// ================本站的==================

// 检查注册的各项
function checkreg() 
{
	var username = $("#username").val();	
	if (!username.match( /^[\u4E00-\u9FA5a-zA-Z0-9_\.@]{2,20}$/)) {		
		$("#tips_reg").html("汉字、字母、数字或邮箱，2-20位");		
		return false;
	}
	
	if($("#password").val().length < 6 ||  $("#password").val().length > 20){
		$("#tips_reg").html('密码 6-20位');	
		return false;
	}
	
	var email = $("#email").val();	
	if (!email.match( /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/ )) {			
		$("#tips_reg").html("请输入正确的邮箱格式");			
		return false;
	}
	return true;	
}

// 获取时间 字符 如 2012-03-24 10:25:10
function getTimes()
{
	var now= new Date();
	var year=now.getFullYear();
	var month=now.getMonth()+1;
	var day=now.getDate();
	var hour=now.getHours();
	var minute=now.getMinutes();
	var second=now.getSeconds();
	
	return year+"-"+month+"-"+day+" "+hour+":"+minute+":"+second;	
}


// 检查查询关键词
function checkkeyword() 
{
	if ( $("#keywords").val() == '搜索关键词' ) {
		$("#keywords").val('');
	}		
}

// 上传文件
function upfile(input) 
{	
	editor.loadPlugin('insertfile', function() {
			editor.plugin.fileDialog({
				fileUrl : $('#'+input).val(),
				showRemote : false,
				clickFn : function(url, title) {					
					$('#'+input).val(url);
					editor.hideDialog();
				}
			});
		});		
}




// 比较两个数字大小，用于数组排序
function sortNumber(vNum1,vNum2)
{
	if(vNum1>vNum2)	{
		return 1;
	}
	else if(vNum1<vNum2)	{
		return -1;
	}
	else {
		return 0;    
	}
}


//========================= 对话框=======================

//内容框
function comDialog(thecontent,thetitle) {
	var comDialogApi = $.dialog({id:"comDialogID",content:thecontent,title:thetitle,max:false,min: false});
}
//url 加载框
function urlDialog(urls,thetitle) {
	$.dialog({content:"url:"+urls,title:thetitle,padding:0}).max();
}
//ajax加载框
function urlAjDialogCus(urls,thetitle,w,h) {
	$.post(urls, { name: "a" },function(data){
     	$.dialog({id:'urlAjDialogCus',content:data,title:thetitle,padding:0,width:w,height:h});
   });
}

//url加载框
function urlDialogCus(urls,thetitle,w,h) {
	$.dialog({id:'comDialogID',content:"url:"+urls,title:thetitle,width:w,height:h});
}

function closeComDlog() {
	$.dialog({id: 'comDialogID'}).close();	
}
//警告对话框
function warningMsgBox(msg) {
	$.dialog({id:"warningID",title:'提示',icon: 'alert.gif',content: msg});
	$.dialog({id: 'warningID'}).time(3);
}
function warningMsgBoxLong(msg) {
	$.dialog({id:"warningID",title:'提示',icon: 'alert.gif',content: msg});
}
//提问对话框
function askMsgBox(msg) {
	$.dialog({id:"askID",title:'提示',icon: 'prompt.gif',content: msg});
	$.dialog({id: 'askID'}).time(3);
}
//操作成功对话框
function okMsgBox(msg) {
	$.dialog({id:"okID",title:'提示',icon: 'success.gif',content: msg});
	$.dialog({id: 'okID'}).time(3);
}
//操作成功对话框，刷新
function okRefMsgBox(msg) {
  $.dialog({title:'提示',icon: 'success.gif',content: msg,ok: function(){
        this.reload();
    }
  });
}
//操作失败/错误对话框
function errMsgBox(msg) {
	$.dialog({title:'提示',icon: 'error.gif',content: msg});
	$.dialog({id: 'okID'}).time(5);
}

//通用弹出对话框
function dialog(content,title,w,h,isencode) {
	if(isencode) content = urldecode(content);
	$.dialog({
		content:content,
		title:title,
		width:w,
		height:h,
		min: false,
		max: false,	
		
		});
}


//弹出公司选择框
function showCitys(cityname)
{
	$.dialog({
			id: 'companyTree',
			title: '请选择城市：',		
			content: 'url:cpi.php?d=admin&c=citys&m=dialog',
			resize: false,
			min: false,
			max: false,
			width: 600,
			height: 500,
			data:cityname
	});
}

//查看地图
function vMaps(longlat,imgsrc) {
	comDialog('<img style="margin:10px" width="400" height="300" src="http://api.map.baidu.com/staticimage?width=400&height=300&center='+longlat+'&markers='+longlat+'&zoom=15&markerStyles=-1,'+imgsrc+'" />','地图');	
}

//查看图片
function vPic(imgsrc,title) {
	comDialog('<img src="'+imgsrc+'" />',title);	
}

//查看输入框内路径图片
function vPicFromID(id,title) {
	var imgsrc = $('#'+id).val();
	comDialog('<img src="'+imgsrc+'" />',title);	
}

//弹出加入黑名单对话框
function addBlackList(ActiveID,openid,nickname)
{
	$.dialog({
			id: 'addBlackList',
			title: '加入黑名单',		
			content: 'url:index.php?d=admin&c=black&m=dialog&ActiveID='+ActiveID+'&openid='+openid+'&nickname='+nickname,
			resize: false,
			min: false,
			max: false,
			width: 500,
			height: 200
	});
}

function urldecode(encodedString)
{
    var output = encodedString;
    var binVal, thisString;
    var myregexp = /(%[^%]{2})/;
  
    while((match = myregexp.exec(output)) != null
                && match.length > 1
                && match[1] != '')
    {
        binVal = parseInt(match[1].substr(1),16);
        thisString = String.fromCharCode(binVal);
        output = output.replace(match[1], thisString);
    }
     
    //output = utf8to16(output);
    output = output.replace(/\+/g, " ");
    output = utf8to16(output);
    return output;
}

  function utf8to16(str)
    {
        var out, i, len, c;
        var char2, char3;
 
        out = "";
        len = str.length;
        i = 0;
        while(i < len) 
        {
            c = str.charCodeAt(i++);
            switch(c >> 4)
            { 
                case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                out += str.charAt(i-1);
                break;
                case 12: case 13:
                char2 = str.charCodeAt(i++);
                out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
                break;
                case 14:
                char2 = str.charCodeAt(i++);
                char3 = str.charCodeAt(i++);
                out += String.fromCharCode(((c & 0x0F) << 12) |
                        ((char2 & 0x3F) << 6) |
                        ((char3 & 0x3F) << 0));
                break;
            }
        }
        return out;
    }
