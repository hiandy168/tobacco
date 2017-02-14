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
<div class="header bordered-blue">角色管理</div>   
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
          <?=zy_btn('Group_add',' + 添加角色 ','class="btn  btn-success"  onclick="location.href=\'index.php?d=admin&c=group&m=add\'" ');?>
    <!-- <input type="button" value=" + 添加角色 " class="btn" onclick="location.href='<?=$this->baseurl?>&m=add'" /> -->
		 </div>
          <input type="hidden" name="catid" value="<?=$catid?>">  
	
          
	</span> 
 </div>
<form action="<?=$this->baseurl?>&m=delete" method="post">
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table table-hover table-bordered" id="sortTable">
			<tr>
			<!--	<th width="30"></th>-->
				<th align="left">角色ID</th>
                <th  class="td_l">角色名称</th>
				<th  class="td_c">最近更新</th>
				<th>操作</th>
			</tr>

    <?php 
	 foreach($list as $key=>$r) {?>
    <tr class="sortTr">
				<!--<td class="td_l">
                <input type="checkbox" name="delete[]" value="<?=$r['GroupID']?>"class="checkbox" />

                </td>-->
				<td class="td_c"><?=$r['GroupID']?></td>
                <td><?=$r['GroupName']?></td>
				<td class="td_c" title="<?=times($r['UpdateTime'],1)?>"><?=timeFromNow($r['UpdateTime'])?></td>
				<td class="td_c">
					<span class="btn btn-blue btn-xs icon-only white"><?=zy_a('Privicy_fenpei','<i class="fa fa-key" title="权限分配"></i>','','onclick="urlDialogCus(\''.$this->baseurl.'&m=fenpei&GroupID='.$r['GroupID'].'\',\'权限分配\',600,400)"');?></span>
					&nbsp;&nbsp;
					<span class="btn btn-success btn-xs icon-only white"><?=zy_a('Group_update','<i class="fa fa-edit" title="编辑"></i>',$this->baseurl.'&m=edit&GroupID='.$r['GroupID']);?></span>
					&nbsp;&nbsp;
                	<span class="btn btn-danger btn-xs icon-only white"><?=zy_a('Group_del','<i class="fa fa-trash" title="删除"></i>',$this->baseurl.'&m=delete&GroupID='.$r['GroupID'],'onclick="return confirm(\'确定要删除吗？\');"');?></span>
                	
                
                </td>
			</tr>
    <?php }?>
    <tr ><td colspan="5"> <div class="margintop">共：<?=$count?>条&nbsp;&nbsp;<?=$pages?></div></td></tr>

		</table>

	

	</form>
</div> 
</div>
</div>
</div>

<?php $this->load->view('admin/footer');?>