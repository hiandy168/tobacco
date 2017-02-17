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



        $(".phone").click(function(){
            var phone = $(this).data('phone');

            var title = '查看浏览器信息';
            $.dialog({
                id: 'a15',
                max: false,
                min: false,
                height: 150 ,
                width: 350,
                padding: '10px' ,
                title:  title,
                lock: true,
                content: phone,//'<img  src="'+img[index]+'" width="100%" height="auto" />',
                cancelVal: '关闭',
                cancel: true /*为true等价于function(){}*/
            });

        })
        $("#order").click(function(){
            if($("#order").is(':checked')){
                window.location.href = '<?= $this->baseurl?>&order=num';
            }else{
                window.location.href = '<?= $this->baseurl?>&order=';
            }
        })



    });
    function getstate(){
        $('#submit').trigger("click");
    }


</script>

<div class="col-xs-12 col-md-12">
    <div class="widget">
        <div class="well with-header wellpadding">
            <div class="header bordered-blue">参与用户</div>
            <div>
                <div class="form-inline"> <span>
          <form action="<?=$this->baseurl?>&m=index" method="post">
              <input type="checkbox" id="order" <?php if($order == 'status'){echo "checked";} ?> align="center" style=" vertical-align:text-bottom;margin-bottom:-0px;*margin-bottom:-3px; margin-right: 2px;" >按状态排序
              <div class="form-group">
				<span class="input-icon">
              <input type="text" name="keywords" value="" class="form-control input-sm">
              <i class="glyphicon glyphicon-search blue"></i> </span> </div>
              <div class="form-group">
                  <input type="submit" name="submit" value=" 搜索 " class="btn btn-blue">
              </div>
          </form>
          </span> </div>
                <table width="100%" border="0" cellpadding="3" cellspacing="0"
                       class="table table-hover table-bordered" id="sortTable">
                    <tr>
                        <th width="30">排序</th>
                        <th>加工记录id</th>
                        <th >头像</th>
                        <th align="left">微信昵称</th>
                        <th align="left">openid</th>
                        <th >所需烟叶/数量</th>
                        <th >所需香料/数量</th>
                        <th >所需滤嘴/数量</th>
                        <th >成品</th>
                        <th >开始加工时间</th>
                        <th >结束加工时间</th>
                        <th >操作</th>
                    </tr>
                    <?php foreach($list as $key=>$r) {?>
                        <tr class="sortTr">
                            <td><?=$key+1?></td>
                            <td><?=$r['id']?></td>
                            <td><img src="<?=$r['headImg'] ? $r['headImg']: $r['localImg']?>" width="40" height="40" /></td>
                            <td ><?=$r['nickName']?></td>
                            <td><?=$r['openId']?></td>
                            <td><?=$r['yanye']['goodsName'].'/'.$r['yanye']['yanyeNum']?></td>
                            <td><?=$r['spice']['goodsName'].'/'.$r['spice']['spiceNum']?></td>
                            <td><?=$r['filter']['goodsName'].'/'.$r['filter']['filterNum']?></td>
                            <td><?=$r['goodsName']?></td>
                            <td title="<?=times($r['startWorkingTime'],1)?>"><?=times($r['startWorkingTime'],1)?></td>
                            <td title="<?=times($r['endWorkingTime'],1)?>"><?=times($r['endWorkingTime'],1)?></td>

                            <td>
                                <span class="btn btn-blue btn-xs icon-only white"><?=zy_a('Manager_update',$r['status']?'<i class="fa fa-lock" title="已拉黑"></i>':'<i class="fa fa-unlock" title="拉黑"></i>','index.php?d=admin&c=player&m=lock&UID='.$r['userId']);?></span>
                                <!--&nbsp;&nbsp;
                                <span class="btn btn-success btn-xs icon-only white"><?/*=zy_a('Manager_update','<i class="fa fa-edit" title="编辑"></i>','index.php?d=admin&c=player&m=edit&UID='.$r['userId']);*/?></span>
                                &nbsp;&nbsp;
                                <span class="btn btn-danger btn-xs icon-only white"><?/*=zy_a('Manager_del','<i class="fa fa-trash" title="删除"></i>','index.php?d=admin&c=player&m=delete&UID='.$r['userId'],'onclick="return confirm(\'确定要删除吗？\');"');*/?></span>-->
                            </td>
                        </tr>
                    <?php }?>
                </table>
                <div class="margintop">共：
                    <?=$count?>
                    条&nbsp;&nbsp;
                    <?=$pages?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/footer');?>
