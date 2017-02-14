<?php $this->load->view('admin/header');?>

<script>
KindEditor.ready(function(K) {
	K.create('#content',{urlType :'relative'});
});
</script>

<div class="col-xs-12 col-md-12">
<div class="widget">
<div class="well with-header">
<div class="header bordered-blue">添加权限</div>   
<div >
</div> 
<form action="<?=$this->baseurl?>&m=save" method="post">
		<input type="hidden" name="Pid" value="<?=$Pid?>">
		<table class="table" width="100%">
        
            
       	 	<tr>
				<td  width="90">父ID</td>
				<td>
					<select name="value[ParentID]" id="ParentID">
						<option value="0">顶级目录</option>
						<?php foreach($priList as $v){?>
						<option <?=($value['ParentID'] == $v[Pid])?'selected':''?> value="<?=$v['Pid']?>"><?=$v['Pname']?></option>
						<?php }?>
					</select>
				</td>
			</tr>	
            	
			<tr>
				<td  width="90">权限名称</td>
				<td><input name="value[Pname]" type="text" class="form-control" value="<?=$value[Pname]?>"></td>
			</tr>
			
			<tr>
				<td >权限标记</td>
				<td><input name="value[Psign]" class="form-control" type="text"
					id="thumb" value="<?=$value[Psign]?>" /></td>
			</tr>
			
			
			
			<tr>
				<td >&nbsp;</td>
				<td><input type="submit" name="submit" value=" 提 交 " class="btn btn-blue"
					tabindex="3" /> &nbsp;&nbsp;&nbsp;<input type="button"
					name="submit" value=" 取消 " class="btn"
					onclick="javascript:history.back();" /></td>
			</tr>
		</table>
	</form>
</div>


</div>
</div>
<?php $this->load->view('admin/footer');?>