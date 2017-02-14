<?php $this->load->view('admin/header');?>
<script type="text/javascript">
$(function($)
{
	// 数据列表 点击开始排序
	var sortFlag = 0;	
	$("#sortTable th").click(function()
	{		
		var tdIndex = $(this).index();		
		var temp = "";
		var trContent = new Array();
		//alert($(this).text());	
		
		// 把要排序的字符放到行的最前面，方便排序
		$("#sortTable .sortTr").each(function(i){ 
			temp = "##" + $(this).find("td").eq(tdIndex).text() + "##";			
			trContent[i] = temp + '<tr class="sortTr">' + $(this).html() + "</tr>";	
				
		});
		
		// 排序
		if(sortFlag==0) {
			trContent.sort(sortNumber);
			sortFlag = 1;
		} else {
			trContent.sort(sortNumber);
			trContent.reverse();
			sortFlag = 0;
		}
		
		// 删除原来的html 添加排序后的
		$("#sortTable .sortTr").remove();
		$("#sortTable tr").first().after( trContent.join("").replace(/##(.*?)##/, "") );		
	});
	
});

</script>
<div class="col-xs-12 col-md-12">
<div class="widget">
<div class="well with-header wellpadding">
<div class="header bordered-blue">用户管理</div>   
<div >
<div class="form-inline">
    	<span >

          
  
   <form action="<?=$this->baseurl?>&m=index" method="post">
    <div class="form-group">
    <span class="input-icon">
			<input type="hidden" name="catid" value="<?=$catid?>"> <input
				type="text" name="keywords" value="" class="form-control input-sm">
                 <i class="glyphicon glyphicon-search blue"></i>
</span></div> 
     <div class="form-group"><input type="submit" name="submit" value=" 搜索 " class="btn btn-blue"></div>
		</form>
         <div class="form-group">
		  <?=zy_btn('Manager_add',' + 添加用户 ','class="btn btn-success"  onclick="location.href=\'index.php?d=admin&c=admin&m=add\'" ');?>
		 </div>
          <input type="hidden" name="catid" value="<?=$catid?>">  
	
          
	</span> 
 </div>
<form action="<?=$this->baseurl?>&m=delete" method="post">
		<input type="hidden" name="catid" value="<?=$catid?>">
		<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table table-hover table-bordered" id="sortTable">
			<tr>
				<!--<th width="30"></th>-->
				<th width="30">UID</th>
                
				<th  class="td_l">用户名</th>
                <th  class="td_l">姓名</th>
                <th  class="td_l">所属角色</th>
				<th >电话</th>
				<th  class="td_l">E-mail</th>
				<th  class="td_l">备注</th>
	      		<th class="td_l" width="100">最近登录</th>
				<th width="160">操作</th>
			</tr>

    <?php 
	 foreach($list as $key=>$r) {?>
    <tr class="sortTr">
				<!--<td class="td_c">
                <input type="checkbox" name="delete[]" value="<?=$r['UID']?>"class="checkbox" />

                </td>-->
				<td class="td_c"><?=$r['UID']?></td>
                <td><?=$r['Username']?></td>
                <td><?=$r['TrueName']?></td>
                <td><?=getGroupName($r['GroupID'])?></td>
				<td class="td_c"><?=$r['Tel']?></td>
				<td class="td_l"><?=$r['Email']?></td>
				<td><?=$r['Remark']?></td>
				<td  class="td_l"title="<?=times($r['LoginTime'],1)?>"><?=timeFromNow($r['LoginTime'])?></td>
				<td>
					<span class="btn btn-blue btn-xs icon-only white"><?=zy_a('Manager_update',$r['Status']?'<i class="fa fa-lock" title="已锁定"></i>':'<i class="fa fa-unlock" title="锁定"></i>','index.php?d=admin&c=admin&m=lock&UID='.$r['UID']);?></span>
					&nbsp;&nbsp;
					<span class="btn btn-success btn-xs icon-only white"><?=zy_a('Manager_update','<i class="fa fa-edit" title="编辑"></i>','index.php?d=admin&c=admin&m=edit&UID='.$r['UID']);?></span>
					&nbsp;&nbsp;
                	<span class="btn btn-danger btn-xs icon-only white"><?=zy_a('Manager_del','<i class="fa fa-trash" title="删除"></i>','index.php?d=admin&c=admin&m=delete&UID='.$r['UID'],'onclick="return confirm(\'确定要删除吗？\');"');?></span>
                </td>
			</tr>
    <?php }?>
    <tr ><td colspan="12"> <div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div></td></tr>
		</table>

		

	</form>
</div> 
</div>
</div>
</div>




<?php $this->load->view('admin/footer');?>