<?php $this->load->view('admin/header');?>

<script type="text/javascript">
var api = frameElement.api, W = api.opener;

$(function(){
	dialog();
	 })
function dialog(){ 
	W.$.dialog({id:'comDialogID'}).hide();
				W.$.dialog({
					top:50,
					width:200,
					title: '提示',
					time: 3, 
					icon: 'success.gif',
					titleIcon: 'lhgcore.gif',
					content: '<?=$msg?>',
					ok:function(){  						
							W.location.href ="<?=$url?>";
   						},
					cancel:false,
					close:function(){
      			 		W.location.href ="<?=$url?>";
    				}

				});
}
 
</script>
