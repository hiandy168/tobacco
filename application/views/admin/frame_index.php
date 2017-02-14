<!DOCTYPE html>
<html>
<head>
<title>【<?=TITLE?>】后台管理中心</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link id="beyond-link" href="static/system/assets/css/beyond.min.css" rel="stylesheet" />
<link rel="stylesheet" href="static/system/admin_img/admincp.css?1"
	type="text/css" media="all" />
    <link href="static/system/assets/css/font-awesome.min.css" rel="stylesheet" />
    
<script type="text/javascript" src="static/system/js/jquery-1.7.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('.nav li').click(function(){
    	$('.nav li').removeClass();
    	$(this).addClass("tabon");
    	$(".frame_left > ul").hide().eq($('.nav li').index(this)).show();
    });
    
    $('.frame_left a').click(function(){
    	$('.frame_left a').removeClass();			
    	$(this).addClass("on");		
        });
	
	var ids = [0,1,2,3,4,5];	
	$('.nav ul li').each(function(){		
		var id = $(this).data('id');		
		ids.splice(id, 1 , -1);
	})	
   
	for(var i=0;i<ids.length;i++){
		if(ids[i] != -1){			
			$('.frame_left .children_'+ids[i]).remove();
		}
	}
	
});
</script>
<script> 
$(document).ready(function(){
var topheight = $(".mainhd").height();
var windowHeight = $(window).height();
  $(".sidebar-collapse").click(function(){
    $(".sider").animate({
      width:'toggle'
    });
	 $(this).toggleClass("fold_off");
  });
});
</script> 
<style>
html,body {
	width: 100%;
	height: 100%;
	overflow: hidden;
}
.frame_left ul{
	display:none;
}
</style>
<?php 
$main_li = array( 
			0=> array('name' => '玩家管理', 'psign' => 'wjgl' ),
			1=> array('name' => '玩家场景管理', 'psign' => 'cjgl' ),
			2=> array('name' => '游戏仓库', 'psign' => 'yxck' ),
			3=> array('name' => '安全控制', 'psign' => 'aqkz' ),
			4=> array('name' => '数据统计', 'psign' => 'sjtj' ),
			5=> array('name' => '系统管理', 'psign' => 'xtgl' ),
			 );

?>
</head>
<body scroll="no">
	<div class="mainhd">
		<div class="logo">
			<img src="./static/system/admin_img/logo.png">
		</div>
        <div class="sidebar-collapse"><i class="collapse-icon fa fa-bars"></i></div>
		<div class="nav" style="width:auto;">
			<ul>
            
            <?php 
			
			foreach($main_li as $key=>$li){				
				 $li_class = 'class="'.$li['psign'].'" data-id="'.$key.'"';
				echo zy_li($li['psign'], $li['name'], '', '' , $li_class );
			}
			?>
				<!--<li class="tabon">                
                <a href="#">渠道管理</a></li>
             	<li><a href="#">活动管理</a></li>
                <li><a href="#">游戏仓库</a></li>
                <li><a href="#">安全控制</a></li>
                <li><a href="#">数据统计</a></li>
				<li><a href="#">系统管理</a></li>-->
                
                
			</ul>
		</div>
		
		
		
		<div style=" float: right;padding-top: 10px;margin-right: 15px;" class="uinfo">
			
			<p>
				欢迎您, <em><?php echo $_SESSION['TrueName']?$_SESSION['TrueName']:$_SESSION['Username'];?></em> 
               <a href="index.php?d=admin&c=admin&m=index&m=edit&UID=<?=$this->session->userdata('UID')?>" target="main"><i class="fa fa-user">个人资料</i></a>
           
                <a href="index.php?d=admin&c=common&m=login_out" target="_top"><i class="fa fa-sign-out">退出</i></a>
			
             <a href="./apidoc/index.html" target="main">API文档</a>
            </p>
			

		</div>
		
	</div>
    
	<table cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tr>
			<td valign="top" width="160"
				class="sider">
				<div class="frame_left">

                    <ul class="children_0">
                        <?php
                        $hdgl = array(
                            0=> array('name' => '<i class="fa fa-user"></i>玩家列表', 'psign' => 'Player_view', 'url' => 'index.php?d=admin&c=player&m=index', 'attr' => 'target="main"')
                        );
                        foreach($hdgl as $key=>$li){
                            if($key == 0) $li['attr'] = ' ' . $li['attr'];
                            echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
                        }
                        ?>
                    </ul>
					<!--<ul class="children_0">
                    
                    <?php /*
					$hdgl = array(
						0=> array('name' => '<i class="fa fa-reorder"></i>渠道列表<i class="menu-expand"></i>', 'psign' => 'Channel_view', 'url' => 'index.php?d=admin&c=channel', 'attr' => 'target="main"'),
						1=> array('name' => '<i class="fa fa-cog"></i>渠道接口管理<i class="menu-expand"></i>', 'psign' => 'ChannelAPI_view', 'url' => 'index.php?d=admin&c=channelAPI', 'attr' => 'target="main"'),
						2=> array('name' => '<i class="fa fa-cog"></i>渠道接口类型<i class="menu-expand"></i>', 'psign' => 'ChannelAPI_view', 'url' => 'index.php?d=admin&c=channel_api_type', 'attr' => 'target="main"'),
				
					);
					
					foreach($hdgl as $key=>$li){						
						if($key == 0) $li['attr'] = ' ' . $li['attr'];
						echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
					}
					
					
					*/?>
                      	               
					</ul>-->

					<ul class="children_1">
                    
                     <?php 
					$hdgl = array(
							0=> array('name' => '<i class="fa fa-flag"></i>农场小屋<i class="menu-expand"></i>', 'psign' => 'Active_list', 'url' => 'index.php?d=admin&c=active', 'attr' => 'target="main"'),
							1=> array('name' => '<i class="fa fa-ban"></i>配方研究所<i class="menu-expand"></i>', 'psign' => 'Black_list', 'url' => 'index.php?d=admin&c=breed', 'attr' => 'target="main"'),
                            2=> array('name' => '<i class="fa fa-gift"></i>种子培育中心<i class="menu-expand"></i>', 'psign' => 'Black_list', 'url' => 'index.php?d=admin&c=research', 'attr' => 'target="main"'),
                            3=> array('name' => '<i class="fa fa-gift"></i>真龙商行<i class="menu-expand"></i>', 'psign' => 'Black_list', 'url' => 'index.php?d=admin&c=shop', 'attr' => 'target="main"'),
                            4=> array('name' => '<i class="fa fa-gift"></i>制烟工厂<i class="menu-expand"></i>', 'psign' => 'Black_list', 'url' => 'index.php?d=admin&c=working', 'attr' => 'target="main"'),
                            5=> array('name' => '<i class="fa fa-gift"></i>仓库<i class="menu-expand"></i>', 'psign' => 'Black_list', 'url' => 'index.php?d=admin&c=store', 'attr' => 'target="main"'),
                            6=> array('name' => '<i class="fa fa-gift"></i>土地<i class="menu-expand"></i>', 'psign' => 'Black_list', 'url' => 'index.php?d=admin&c=land', 'attr' => 'target="main"'),
								);
					
					foreach($hdgl as $key=>$li){						
						if($key == 0) $li['attr'] = ' ' . $li['attr'];
						echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
					}
					
					
					?>            
                    
                    
                    </ul>
					<ul class="children_2">
						<?php 
						$hdgl = array(
							0=> array('name' => '<i class="fa fa-gamepad"></i>游戏列表', 'psign' => 'GameRoom_view', 'url' => 'index.php?d=admin&c=gameRoom', 'attr' => 'target="main"'),
							1=> array('name' => '<i class="fa fa-folder"></i>游戏复用资源管理', 'psign' => 'GameRegTable_view', 'url' => 'index.php?d=admin&c=gameRegTable', 'attr' => 'target="main"'),
							2=> array('name' => '<i class="fa fa-th"></i>游戏功能导航管理', 'psign' => 'GameRegNav_view', 'url' => 'index.php?d=admin&c=gameRegNav', 'attr' => 'target="main"'),
							3=> array('name' => '<i class="fa fa-puzzle-piece"></i>游戏接口管理', 'psign' => 'GameAPI_view', 'url' => 'index.php?d=admin&c=gameAPI', 'attr' => 'target="main"'),
							
						);
						
						foreach($hdgl as $key=>$li){						
							if($key == 0) $li['attr'] = ' ' . $li['attr'];
							echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
						}
						?>  
					<!--	<li><a style="color:red;" href="http://119.29.87.142/racedog/index.php?d=admin&c=common&m=index&id=18" target="_blank"><i class="fa fa-puzzle-piece"></i>赛狗游戏独立管理</a></li>-->
					</ul>
					<ul class="children_3">
                    
                       <?php 
					$hdgl = array(
						0=> array('name' => '<i class="fa fa-television"></i>行为监控', 'psign' => 'Behavior_view', 'url' => 'index.php?d=admin&c=behavior', 'attr' => 'target="main"'),
						1=> array('name' => '<i class="fa fa-warning"></i>异常监控', 'psign' => 'Exceptional_view', 'url' => 'index.php?d=admin&c=exceptional', 'attr' => 'target="main"'),
						//2=> array('name' => '<i class="fa fa-file"></i>资源监控', 'psign' => 'Disk_view', 'url' => 'index.php?d=admin&c=disk', 'attr' => 'target="main"'),
					);
					
					foreach($hdgl as $key=>$li){						
						if($key == 0) $li['attr'] = ' ' . $li['attr'];
						echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
					}
					
					
					?>     
                    
                    </ul>
					<ul class="children_4">
                      <?php 
					$hdgl = array(
						0=> array('name' => '<i class="fa fa-bar-chart-o"></i>游戏访问统计', 'psign' => 'Game_visit_tj_view', 'url' => 'index.php?d=admin&c=game_visit_tj', 'attr' => 'target="main"'),
						1=> array('name' => '<i class="fa fa-area-chart"></i>黑名单统计', 'psign' => 'Blacklist_tj_view', 'url' => 'index.php?d=admin&c=blacklist_tj', 'attr' => 'target="main"'),
						2=> array('name' => '<i class="fa fa-plus-square"></i>游戏关注统计', 'psign' => 'Concern_tj_view', 'url' => 'index.php?d=admin&c=concern_tj', 'attr' => 'target="main"'),
				
					);
					
					foreach($hdgl as $key=>$li){						
						if($key == 0) $li['attr'] = ' ' . $li['attr'];
						echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
					}
					
					
					?>     
                    </ul>					
 					<ul class="children_5">	
 						<?php 
						$hdgl = array(
								0=> array('name' => '<i class="fa fa-user"></i>用户管理', 'psign' => 'Manager_view', 'url' => 'index.php?d=admin&c=admin&m=index', 'attr' => 'target="main"'),
								1=> array('name' => '<i class="fa fa-group"></i>角色管理', 'psign' => 'Group_view', 'url' => 'index.php?d=admin&c=group&m=index', 'attr' => 'target="main"'),
								2=> array('name' => '<i class="fa fa-key"></i>权限列表', 'psign' => 'Privicy_view', 'url' => 'index.php?d=admin&c=privicy&m=index', 'attr' => 'target="main"'),
/*								3=> array('name' => '<i class="fa fa-refresh"></i>同步管理', 'psign' => 'Attachment_view', 'url' => 'index.php?d=admin&c=attachment&m=index', 'attr' => 'target="main"'),
								4=> array('name' => '<i class="fa fa-refresh"></i>服务监控', 'psign' => 'Attachment_view', 'url' => 'index.php?d=admin&c=common&m=server', 'attr' => 'target="main"'),*/
					
						);
						foreach($hdgl as $key=>$li){						
							if($key == 0) $li['attr'] = ' ' . $li['attr'];
							echo zy_li($li['psign'], $li['name'], $li['url'], $li['attr']);
						}
						?>
					</ul>



                </div>
                
			</td>
           
			<td valign="top" height="100%" style="position:relative; background:#eee; padding:15px 0;">
            <iframe
					src="./index.php?d=admin&c=common&m=main" name="main" width="100%"
					height="96%" frameborder="0" scrolling="yes"
					style="overflow: auto;"></iframe></td>
		</tr>
	</table>
</body>
</html>