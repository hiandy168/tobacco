<?php $this->load->view('admin/header');?>
<div style="margin:10px">
<h3 class="marginbot">欢迎登录系统管理后台！</h3>

<ul class="memlist">
	<li><em>系统版本：</em>V1.0</li>
	<li><em>PHP版本：</em><?=PHP_VERSION?></li>
	<li><em>MYSQL版本：</em><?="5.6.17";?></li>
	<!--<li><em>MYSQL版本：</em><?/*=mysql_get_server_info();*/?></li>-->
	<li><em>版权所有：</em>中烟</li>
</ul>
</div>
<?php $this->load->view('admin/footer');?>