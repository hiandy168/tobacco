<?php $this->load->view('admin/header');?>
<script type="text/javascript">
if(top.location!=self.location){
  //说明你的页面在if框架中显示
  //alert();
  parent.location.reload();
}
$(document).ready(function(){
var windowHeight = $(window).height();
var conheight = $(".main_login").height();
$(".container").height(windowHeight );
$(".main_login").css("top",(windowHeight - conheight)/2);
});
</script>
<style>
html,body{margin:0; height:100%;}
.mpage{width:100%;height:163px;overflow:hidden;margin:0 auto;position:relative;background-color:#f96e00;}
.anit,.veinbg{position:absolute;left:0;top:0;width:100%;height:163px;}

</style>
<link href="static/system/assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/system/assets/css/font-awesome.min.css" rel="stylesheet" />
<link id="beyond-link" href="static/system/assets/css/beyond.min.css" rel="stylesheet" />
<link href="static/system/assets/css/animate.min.css" rel="stylesheet" />
<link href="static/system/assets/css/style.css" rel="stylesheet" />
<script type="text/javascript" src="static/system/js/dl.js"></script>

<div class="login-container animated fadeInDown">
<div class="loginbox bg-white">
    <div class="login-logo">   <div id="container" class="mpage">
        <div id="anit" class="anit"></div>
     
        
    </div><img src="static/system/assets/img/login_logo.png" class="login_logo"/></div>
    <form action="index.php?d=admin&c=common&m=check_login" method="post">
    <div class="loginbox-textbox">
      <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user"></i></span>
        <input name="username" type="text" autocomplete="off"  class="form-control" id="username" tabindex="1" placeholder="用户名" />
      </div>
    </div>
    <!--<tr>
				  <td align="left">自&nbsp; 动：                
				  <td colspan="2" align="left"><select class="text" name="cookietime" id="cookietime" style="height:26px;">
				    <option value="0">不自动登录</option>
				    <option value="7">一个星期内</option>
				    <option value="30">一个月内</option>
				    <option value="90">三个月内</option>
				    <option value="365">一年内</option>
				    <option value="3650">十年内</option>
                    <option value="365000">百年内</option>
			      </select></td>
			  </tr>-->
    <div class="loginbox-textbox">
      <div class="input-group"> <span class="input-group-addon"><i class="fa fa-key"></i></span>
        <input name="password" type="password" autocomplete="off"  class="form-control" id="password" tabindex="2" placeholder="密码"/>
      </div>
    </div>
    <div class="loginbox-submit">
      <input name="input" type="submit" value="登录"  class="btn btn-blue btn-block" tabindex="4" />
    </div>
    </form>
  </div>
<div class="copyright">建议使用IE8及以上版本的浏览器或火狐浏览器! </p>
<p>Copyright ©2016  Power by <a href="#" target="_blank">广西中烟</a></div>
</div>
