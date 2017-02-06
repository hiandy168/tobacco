<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="static/js/jquery-1.11.2.min.js"></script>
    <style type="text/css">

        ::selection { background-color: #E13300; color: white; }
        ::-moz-selection { background-color: #E13300; color: white; }

        body {
            background-color: #fff;
            margin: 40px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 19px;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #body {
            margin: 0 15px 0 15px;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        #container {
            margin: 10px;
            border: 1px solid #D0D0D0;
            box-shadow: 0 0 8px #D0D0D0;
        }
    </style>

    <script type="text/javascript">

        // 与GatewayWorker建立websocket连接，域名和端口改为你实际的域名端口，不能与注册地址一样（registerAddress）
        ws = new WebSocket("ws://192.168.1.217:8090");
        // 服务端主动推送消息时会触发这里的onmessage
        ws.onmessage = function(e){
            // json数据转换成js对象
            var data = eval("("+e.data+")");
            console.log(data);
            var type = data.type || '';
            switch(type){
                // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
                case 'init':
                    // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
                    $.post('index.php?d=admin&c=bind&m=index', {client_id: data.client_id}, function(data){}, 'json');
                    break;
                // 当mvc框架调用GatewayClient发消息时直接alert出来
                default :
                //alert(e.data);
            }
        };

        $(document).ready(function(){
            //土地购买
            $(".btn_buy_land").click(function(){
                var land_num = $("#buy_land_num").val();
                var land_source = $("#buy_land_source").val();
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main_test&m=save_buy_land',
                    {md5_uid:md5_uid,land_num: land_num,land_source:land_source},
                    function(data){
                        if(data['code']){
                            alert("土地购买成功");
                            $("#ledou").text(data['ledou_num']);
                        }
                    },
                    'json'
                );
            });
            //种子购买
            $(".btn_buy_seed").click(function(){
                var seed_type = $("#buy_seed_type").val();
                var seed_num = $("#buy_seed_num").val();
                var seed_source = $("#buy_seed_source").val();
                var seed_way = $("#buy_seed_way").val();
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=save_buy_seed',
                    {md5_uid:md5_uid,seed_type: seed_type,seed_num: seed_num,seed_source:seed_source,seed_way:seed_way},
                    function(data){
                        if(data['code']){
                            alert("种子购买成功");
                            $("#ledou").text(data['ledou_num']);
                        }
                    },
                    'json'
                );
            });


            //开始种植
            $(".btn_plant1").click(function(){
                var seed_type = $("#choose_seed1").val();
                var land_id = $("#land_id1").val();
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=start_plant',
                    {md5_uid:md5_uid,seed_type: seed_type,land_id:land_id},
                    function(data){
                        if(data['code']){
                            var id = data['id'];
                            var plant_record_id = data['plant_record_id'];
                            setTimeout("test("+id+","+plant_record_id+")",1000);
                        }
                    },
                    'json'
                );
            });
            $(".btn_shou1").click(function(){
                var plant_record_id = $("#15").val();
                var land_id = 15;
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=shou_ge',
                    {md5_uid:md5_uid,land_id:land_id,plant_record_id:plant_record_id},
                    function(data){
                        if(data['code']){
                            var id = data['id'];
                            var plant_record_id = data['plant_record_id'];
                            $("#"+id).val(plant_record_id);
                        }
                    },
                    'json'
                );
            });
            $(".btn_plant2").click(function(){
                var seed_type = $("#choose_seed2").val();
                var land_id = 2;
                $.post(
                    'index.php?d=admin&c=main&m=start_plant',
                    {seed_type: seed_type,land_id:land_id},
                    function(data){
                        if(data['code']){
                            var id = data['id'];
                            alert("返回的id="+id);
                            setTimeout("test("+id+")",1000);
                        }
                    },
                    'json'
                );
            });
            $(".btn_plant3").click(function(){
                var seed_type = $("#choose_seed3").val();
                var land_id = 3;
                $.post(
                    'index.php?d=admin&c=main&m=start_plant',
                    {seed_type: seed_type,land_id:land_id},
                    function(data){
                        if(data['code']){
                            var id = data['id'];
                            alert("返回的id="+id);
                            setTimeout("test("+id+")",1000);
                        }
                    },
                    'json'
                );
            });

            $(".btn_jg").click(function(){
                var peifang_type = $("#peifang_type").val();
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=start_working',
                    {md5_uid:md5_uid,peifang_type: peifang_type},
                    function(data){
                        if(data['code']){
                            var working_record_id = data['working_record_id'];
                            setTimeout("test2("+working_record_id+")",1000);
                        }
                    },
                    'json'
                );
            });

            $(".btn_compile").click(function(){
                var working_record_id = $("#working_id").val();
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=complete_working',
                    {md5_uid:md5_uid,working_record_id: working_record_id},
                    function(data){
                        if(data['code']){
                            alert("成功存入仓库");
                            $("#time_jg").val(15);
                        }
                    },
                    'json'
                );
            });

            $(".btn_bz").click(function(){
                var packing_type = $("#packing_type").val();
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=start_packing',
                    {md5_uid:md5_uid,packing_type: packing_type},
                    function(data){
                        if(data['code']){
                            var packing_record_id = data['packing_record_id'];
                            setTimeout("test3("+packing_record_id+")",1000);
                        }
                    },
                    'json'
                );
            });


        });

        function test(id,plant_record_id){
            var times = $("#time").val();
            //alert(times);
            if(times!=0){
                times--;
                $("#time").val(times);
                setTimeout("test("+id+","+plant_record_id+")",1000);
            }else{
                $("#time").val(times);
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=end_plant',
                    {md5_uid:md5_uid,land_id:id,plant_record_id:plant_record_id},
                    function(data){
                        if(data['code']){
                            var id = data['id'];
                            var plant_record_id = data['plant_record_id'];
                            $("#"+id).val(plant_record_id);
                        }
                    },
                    'json'
                );
                clearTimeout("test("+id+","+plant_record_id+")");
            }
        }

        function test2(working_record_id){
            var times = $("#time_jg").val();
            //alert(times);
            if(times!=0){
                times--;
                $("#time_jg").val(times);
                setTimeout("test2("+working_record_id+")",1000);
            }else{
                $("#time_jg").val(times);
                var md5_uid = '66e16d4c71fe0616c864c5d591ab0be7';
                $.post(
                    'index.php?d=admin&c=main&m=end_working',
                    {md5_uid:md5_uid,working_record_id:working_record_id},
                    function(data){
                        if(data['code']){
                            var working_record_id = data['working_record_id'];
                            $("#working_id").val(working_record_id);
                        }
                    },
                    'json'
                );
                clearTimeout("test2("+working_record_id+")");
            }
        }


    </script>


</head>
<body>
<div id="container">
    <!--<div style="margin-bottom: 50px;">
        <input type="button" value="群发信息"></button>
    </div>-->
    <h1>昵称：<?=$nickName;?> 。乐豆：<span id="ledou"><?=$leDouNum?></span> 。等级：<?=$gameGrade?> 。经验值：<?=$experienceValue?></h1>
    <div style="margin-bottom: 30px;">
        土地购买：
        <span>
            <select id="buy_land_num">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>(数量)——>
            <select id="buy_land_source">
                <option value="0">系统商城</option>
                <option value="1">真龙商行</option>
            </select>(平台)——>
            <select id="buy_land_way">
                <option value="0">乐豆购买</option>
                <option value="1">积分兑换</option>
            </select>(购买方式)——>
            <input class="btn_buy_land" type="button" value="确认购买">——>购买完毕存入土地表(zy_land)
        </span>
    </div>
    <div style="margin-bottom: 30px;">
        种子购买：
        <span>
            <select id="buy_seed_type">
                <option value="1">巴西种子</option>
                <option value="2">海南种子</option>
                <option value="3">古巴种子</option>
            </select>(类型)——>
            <select id="buy_seed_num">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
            </select>(数量)——>
            <select id="buy_seed_source">
                <option value="0">系统商城</option>
                <option value="1">真龙商行</option>
            </select>(平台)——>
            <select id="buy_seed_way">
                <option value="0">乐豆购买</option>
                <option value="1">积分兑换</option>
            </select>(方式)——>
            <input class="btn_buy_seed" type="button" value="确认购买">——>购买完毕存入种子表、购买记录表(zy_seed、zy_buy_record)
        </span>
    </div>
    <div style="margin-bottom: 30px;">
        开始种植：<br/><br/>
        <span>
            <input type="hidden" id="land_id1" value="15">
            <input type="hidden" id="15" value="">
            <select id="choose_seed1">
                <option value="1">巴西种子</option>
                <option value="2">海南种子</option>
                <option value="3">古巴种子</option>
            </select>——>
            <input class="btn_plant1" type="button" value="往土地1播种">——>
            <input id="time" type="text" value="20" style="width: 30px;">——>
            <input class="btn_shou1" type="button" value="土地1收割">——>收割完毕自动存入仓库
        </span>
    </div>
    <div style="margin-bottom: 30px;">
        <span>
            <input type="hidden" id="land_id2" value="16">
            <input type="hidden" id="16" value="">
            <select id="choose_seed2">
                <option value="1">巴西种子</option>
                <option value="2">海南种子</option>
                <option value="3">古巴种子</option>
            </select>——>
            <input class="btn_plant2" type="button" value="往土地2播种">——>
            <input type="text" value="10" style="width: 30px;">——>
            <input class="btn_shou2" type="button" value="土地2收割">——>收割完毕自动存入仓库
        </span>
    </div>
    <div style="margin-bottom: 30px;">
        <input type="hidden" id="land_id3" value="17">
        <input type="hidden" id="17" value="">
        <span>
            <select id="choose_seed3">
                <option value="1">巴西种子</option>
                <option value="2">海南种子</option>
                <option value="3">古巴种子</option>
            </select>——>
            <input class="btn_plant3" type="button" value="往土地3播种">——>
            <input type="text" value="8" style="width: 30px;">——>
            <input class="btn_shou3" type="button" value="土地3收割">——>收割完毕自动存入仓库
        </span>
    </div>

    <div style="margin-bottom: 30px;">
        开始加工：<br><br>
        <span>
            <input type="hidden" id="working_id" value="">
            <select id="peifang_type">
                <option value="0">基础配方</option>
                <option value="1">改良配方</option>
                <option value="2">经典配方</option>
            </select>——>
            <input class="btn_jg" type="button" value="点击开始加工">——>
            <input id="time_jg" type="text" value="15" style="width: 30px;">——>
            <input class="btn_compile" type="button" value="收获成品烟">——>加工完毕，成品烟自动存入仓库
        </span>
    </div>

    <div style="margin-bottom: 30px;">
        开始包装：<br><br>
        <span>
            <select id="packing_type">
                <option value="0">海韵包装</option>
                <option value="1">鸿韵包装</option>
                <option value="2">珍品包装</option>
            </select>——>
            <input class="btn_bz" type="button" value="点击开始包装">——>
            <input id="time_bz" type="text" value="10" style="width: 30px;">——>
            <input class="btn_compile2" type="button" value="收获盒装烟">——>包装完毕，盒装烟自动存入仓库
        </span>
    </div>

</div>
</body>
</html>