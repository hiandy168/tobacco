<!DOCTYPE html>
<html lang="en">
<head>
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #CCC; font-size: 16px; }
.system-message{ padding: 24px 48px; margin:auto; border: #CCC 3px solid; top:50%; width:500px; border-radius:10px;
    -moz-border-radius:10px; /* Old Firefox */}
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 5px; }
.system-message .jump{ padding-top: 10px; color: #999;}
.system-message .success,.system-message .error{ line-height: 1.8em;  color: #999; font-size: 36px; font-family: '黑体'; }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
<script type='text/javascript' src='static/system/js/jquery-1.7.1.min.js?ver=3.4.2'></script>
<script type="text/javascript">
$(function(){
    var height2=$('.system-message').height();
    var height1=$(window).height();
    $('.system-message').css('margin-top',((height1-height2)/2)-130);
});

</script>
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="static/system/admin_img/bootstrap.min.css">
</head>
<body>
<div class="system-message" style=" text-align:center;">
<?php if ($error || strstr($message, '失败') || strstr($message, '不能') || strstr($message, '请')){ ?>

<h1 class="glyphicon glyphicon-exclamation-sign" style="color:#F33"></h1>
<p class="error"><?php echo $message; ?></p>
<?php }else{?>
<h1 class="glyphicon glyphicon-ok-circle" style="color:#09F"></h1>
<p class="success"><?php echo $message; ?></p>
<?php }?>
<p class="detail"></p>
<p class="jump">
  <?php if($url_forward){?>页面自动 <a id="href" class="text-primary" href="<?php echo($url_forward); ?>">跳转</a> 等待时间： <b id="wait"><?=$second?></b>
 <?php }else{?>
            	<a class="text-primary" href="javascript:history.back();">返回上一页</a>
            <?php }?>
</p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var error_text = $('.error').html() ? $('.error').html() : 'no';
var interval = setInterval(function(){
    var time = --wait.innerHTML;
	if(error_text.indexOf('登录') > -1){
		window.top.location = href;
	}
	
    if(time <= 0) {
        location.href = href;
        clearInterval(interval);
    };
}, 1000);
})();
</script>
</body>
</html>

<!--
	<div id="container">
		
		<?php echo $message; ?>
        
        <p align="right" style="margin:20px; font-weight:bold;">        
            <?php if($url_forward){?>
                <a href="<?=$url_forward?>"> 确定 </a>
                <script type="text/javascript">
                        function redirect(url, time) {
                            setTimeout("window.location='" + url + "'", time * 1000);
                        }
                        redirect('<?=$url_forward?>', <?=$second?>);
                </script>
                
            <?php }else{?>
            	<a href="javascript:history.back();">返回上一页</a>
            <?php }?>
          </p>
	</div>-->