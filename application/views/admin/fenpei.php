<?php $this->load->view('admin/header');?>
<script type="text/javascript">


</script>

<div class="mainbox">
  <form action="<?=$this->baseurl?>&m=prisave" method="post">
    <input type="hidden" name="GroupID" value="<?=$GroupID?>">
    <table width="99%" border="0" cellpadding="3" cellspacing="0"
			class="datalist fixwidth" style="min-width:300px;" id="sortTable">
      <tr>
        <th  class="td_l" width='30'>名称</th>
        <th  class="td_l" width='30'>标志</th>
        <th align="left" width='30'>选择</th>
      </tr>
      <?php 
	 foreach($priList as $key=>$r) {?>
      <tr class="sortTr">
        <td><?=($r['ParentID'] == 0)?'<b>'.$r['Pname'].'</b>': '<font style="margin-left:20px;">'. $r['Pname'] .'</font>'?></td>
        <td><?=$r['Psign']?></td>
        <td  class="td_c"><input name="pri[]" type="checkbox" <?=in_array($r['Pid'], $sel_ids)?'checked="checked"':''?> value="<?=$r['Pid']?>"></td>
      </tr>
      <?php }?>
    </table>
    <div class="margintop">
      <input type="submit" id="tj"  class="btn" value="提交">
      &nbsp;&nbsp; <a href="javascript:;" onclick="selectalls('all')">全选</a>
      &nbsp;&nbsp; <a href="javascript:;" onclick="selectalls('re')">反选</a>
      &nbsp;&nbsp; <a href="javascript:;" onclick="selectalls('no')">取消</a>
       </div>
  </form>
</div>
<script>
function selectalls(type){
	if(type == 're'){
	  $(".datalist input:checkbox").each(function () {   
				this.checked = !this.checked;    
	   }) 
	}else if( type == 'all'){
		$(".datalist input:checkbox").each(function () {   
				$(this).attr("checked", true);    
	    }) 
		
	}else if( type == 'no'){
		$(".datalist input:checkbox").each(function () {   
				$(this).attr("checked", false);    
	    })  
	}
   
   
   
}
</script>