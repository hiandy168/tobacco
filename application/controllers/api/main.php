<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class main extends base {

    private $openid = '';

    public $GameID=0;
    public $ActiveID = 0;
    public $ChannelID = 0;
    public $RoomID = 0;

    public $game_sign_arr;
    public $basestr = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    public $baseStrRand='';
    public function __construct()
    {
        parent::__construct();
        $this->GameID = $this->input->get('GameID') ? $this->input->get('GameID') : $this->input->get('GID');
        $this->ActiveID = $this->input->get('ActiveID') ? $this->input->get('ActiveID') : $this->input->get('AID');
        $this->ChannelID = $this->input->get('ChannelID') ? $this->input->get('ChannelID') : $this->input->get('CID');
        $this->RoomID = $this->input->get('RoomID') ? $this->input->get('RoomID') : $this->input->get('RID');

        $this->GameID = intval($this->GameID);
        $this->ActiveID = intval($this->ActiveID);
        $this->ChannelID = intval($this->ChannelID);
        $this->RoomID = intval($this->RoomID);

        if($this->ActiveID && !$this->ChannelID){
            $this->content_model->set_table( 'zy_active_main' );
            $row = $this->content_model->get_one($this->ActiveID, 'ActiveID');
            $this->ChannelID = $row['ChannelID'];
            $this->RoomID    = $row['RoomID'];
        }

        $this->game_sign = "&AID=$this->ActiveID&CID=$this->ChannelID&RID=$this->RoomID&GID=$this->GameID";
        $this->game_sign_sql = addslashes("  ActiveID=$this->ActiveID AND ChannelID=$this->ChannelID AND RoomID=$this->RoomID AND GameID=$this->GameID");

        $this->load->model('my_common_model', 'common');
        //$this->load->model('lb_model');

        $this->game_sign_arr=array(
            'ActiveID'  => $this->ActiveID,
            'ChannelID' => $this->ChannelID,
            'RoomID' => $this->RoomID,
            'GameID' => $this->GameID
        );

    }


    /**
     *接口名称：游戏入口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=main&m=index
     *接收方式：post
     *接收参数：
     *
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:  时间戳
     *      nickName: 玩家昵称
     *      headimgurl：头像地址
     *      md5Uid：用户加密id
     *      experienceValue: 用户游戏经验值
     *      gameGrade：游戏等级
     *      leDouNum：乐豆数量
     *      goldNum：金币数量
     *      storeTotalCap：仓库容量
     *      first_time：是否第一次进入游戏（yes是，no不是）
     *      data:
     *          plant: 所有种植状态
     *              land_id: 土地id
     *              landStatus : 土地状态 0空闲、1正在种植、2已经成熟(但未收割)
     *              plant_record_id：种植记录id
     *              goodsId：种植物品（种子）id
     *              startPlantTime：开始种植时间
     *              endPlantTime：结束种植时间
     *              needTime:   成长所需要的时长
     *              goodsName:  种植物品（种子）中文名称
     *              becomeGoodsId:  成熟之后的物品（叶子）id（种子成熟后，变为叶子）
     *              becomeGoodsName:  成熟之后的物品（叶子）的中文名称
     *          working: 所有加工状态
     *              working_record_id: 加工id
     *              peifangId : 加工所用的配方id
     *              startWorkingTime：开始加工时间
     *              endWorkingTime：结束加工时间
     *              status：加工状态 （1正在加工、2加工完成）
     *              goodsName：加工所用的配方的中文名称
     *              needTime:   加工所需要的时长
     *              becomeGoodsId:  加工完成之后的物品（烟支）的id
     *              becomeGoodsName:  加工完成之后的物品（烟支）的中文名称（烟叶经过加工后，变为“海韵”烟支）
     *          packing: 所有包装状态
     *              packing_record_id: 包装记录id
     *              packingId : 包装方式id
     *              startPackingTime：开始包装时间
     *              endPackingTime：结束包装时间
     *              status：包装状态 （1正在包装、2包装完成）
     *              goodsName：包装方式的中文名称
     *              needTime:   包装所需要的时长
     *              becomeGoodsId:  包装完成之后的物品（盒装烟）的id
     *              becomeGoodsName:  包装完成之后的物品（盒装烟）的中文名称（烟支经过包装后，变为“海韵”盒装烟）
     *          breed:  所有种子培育状态
     *              breed_record_id: 种子培育记录id
     *              startBreedTime：开始培育时间
     *              endBreedTime：结束培育时间（当培育状态为正在培育时，即尚未培育完成，该值为空）
     *              status：培育状态 （1正在培育、2培育完成）
     *              goodsId：所要培育的种子的id
     *              goodsName：所要培育的种子的中文名称
     *              needTime:   培育种子所需要的时长
     *          research:  所有配方研究状态
     *              research_record_id: 配方研究记录id
     *              startResearchTime：开始研究时间
     *              endResearchTime：结束研究时间（当培育状态为正在培育时，即尚未培育完成，该值为空）
     *              status：研究状态 （1正在研究、2培育研究）
     *              goodsId：所要研究的配方的id
     *              goodsName：所要研究的配方的中文名称
     *              needTime:   配方研究所需要的时长
     **/

    public function index ()
    {
        $this->load->helper('cookie');
        set_cookie("xxle_mainer",'haha');
        $time=time();
        if(isset($_GET['test'])){
            $this->baseStrRand = str_shuffle($this->basestr);//校验串,玩家进入游戏时发给客户端
            $phone_os = $_SERVER['HTTP_USER_AGENT'];
            $headurl = "http://wx.qlogo.cn/mmopen/FaYC3jcvGMMJTjnicMTEhsspRAvtLmhCBNBSjHYMicm69pwpGJ6oFjIqWddWmgjb24ibFluDJPw9dpkmFg7ZdtF2sKcYTvEguLM/0";
            if($_GET['test']==1){
                $wx_info = array('openId' => 'oM0MxsyO2H_CsGnGJ5TkejcsLTzE', 'nickName' => '方方', 'headimgurl' => $headurl);
            }else if($_GET['test']==2){
                $wx_info = array('openId' => 'oM0MxsxV0Tb0DTjl80N8VP0Brazw', 'nickName' => '童话', 'headimgurl' => $headurl);
            }

            $filename ='static/wxheadimg/tobacco/' . $wx_info['openId'] . '.png';

            $data['openId'] = $wx_info['openId'];//加密
            $data['nickName'] = $wx_info['nickName'];
            $data['headimgurl']=$wx_info['headimgurl'];
            $this->openid = $data['openId'];
            //用户是否存在
            $isexit = $this->db->query("select count(*) as total,userId,md5Uid,nickName,headImg, localImg,allowMusic,experienceValue,gameGrade,leDouNum,goldNum,storeTotalCap  from zy_user where openId='".$data['openId']."' AND $this->game_sign_sql")->row_array();

            if($isexit['total'] > 0){
                if (!file_exists($filename) || $isexit['headImg'] != $headurl) {
                    $img_local_url = $this->getImg($headurl, $filename);
                    //echo $headurl;exit;
                    $headLocalPhoto = base_url() . $img_local_url;
                    $data['headimgurl'] = $headLocalPhoto;
                } else {
                    $data['headimgurl'] = $isexit['localImg'] ? $isexit['localImg'] : base_url() . $filename;
                }

                //更新最近时间
                $this->db->query("update zy_user set baseStrRandCode='$this->baseStrRand', Updatetime= $time,phoneOs = '$phone_os' where openId= '".$data['openId']."' AND $this->game_sign_sql");//更新烟豆
                $uId = $isexit['userId'];
                $data['md5Uid'] = $isexit['md5Uid'];
                $data['experienceValue'] = $isexit['experienceValue'];
                $data['gameGrade'] = $isexit['gameGrade'];
                $data['leDouNum'] = $isexit['leDouNum'];
                $data['goldNum'] = $isexit['goldNum'];
                $data['storeTotalCap'] = $isexit['storeTotalCap'];
                $data['first_time'] = 'no';

            }else{
                $img_local_url = $this->getImg($headurl, $filename);
                $headLocalPhoto = base_url() . $img_local_url;
                $data['headimgurl'] = $headLocalPhoto;
                //用户表保存用户信息
                $user_data['openId'] =  $wx_info['openId'];
                $user_data['nickName'] =  $wx_info['nickName'];
                $user_data['headImg'] =  $wx_info['headimgurl'];
                $user_data['experienceValue'] = 0;
                $user_data['gameGrade'] = 0;
                $user_data['leDouNum'] = 100;
                $user_data['goldNum'] =  500;
                $user_data['storeTotalCap'] =  100000;
                $user_data['localImg'] = $headLocalPhoto;
                $user_data['addTime'] =  $time;
                $user_data['updateTime'] =  $time;
                $user_data['allowMusic'] = 0;
                $user_data['phoneOs'] = $phone_os;
                $user_data['ActiveID'] 	= $this->ActiveID;
                $user_data['ChannelID'] = $this->ChannelID;
                $user_data['RoomID'] 	= $this->RoomID;
                $user_data['GameID'] 	= $this->GameID;
                $user_data['baseStrRandCode']=$this->baseStrRand;
                //获取随机字符串
                $rand = rand(0,45);
                $rand_str = substr($this->baseStrRand,$rand,6).$wx_info['openId'].'ywz';
                $user_data['md5Uid'] = md5($rand_str);
                $insert_sql = $this->db->insert_string('zy_user',$user_data);
                $insert_sql = str_replace('INSERT', 'INSERT ignore ', $insert_sql);
                $this->db->query($insert_sql);
                $uId = $this->db->insert_id();
                //初始化用户土地（新用户默认拥有6块土地）
                for($i=0;$i<6;$i++){
                    $this->land_model->insert(array('uId'=>$uId,'addTime'=>$time));
                }
                $data['allowMusic'] = 0;    //默认为0，0代表允许播放，1代表禁止播放
                $data['md5Uid'] = $user_data['md5Uid'];
                $data['experienceValue'] = 0;
                $data['storeTotalCap'] =  100000;
                $data['gameGrade'] = 0;
                $data['leDouNum'] = 100;
                $data['goldNum'] =  500;
                $data['first_time'] = 'yes';
            }

        }else{
            //判断活动、游戏状态
            $isRun = $this->common->get_active_game_status($this->ActiveID,$this->RoomID);
            if(!$isRun['status']) {
                $data['msg'] = $isRun['msg'];
                $this->load->view('tip', $data);
                return;
            }

            if($this->ActiveID && $this->ChannelID && $this->RoomID){
                echo $this->ActiveID."|".$this->ChannelID."|".$this->RoomID;exit;
                $state_base64 = base64_encode('http://h5game.gxtianhai.cn/mntvdb/gamecenter/index.php?d=ljxxl&c=ljxxl&m=getUser&AID=' . $this->ActiveID);
                $apiUrl = $this->lb_model->getUserApiByAID($this->ActiveID);
                $temp = sprintf($apiUrl,$state_base64);
                if(empty($temp)) show_msg('渠道接口获取失败ChannelID：'.$this->ActiveID.'！');
                header("Location: ".$temp);
                return;
            }else{
                show_msg('非法访问！');
                exit;
            }
        }

        //微信分享用到的信息
        //$signPackage = $this->common->getSignPackage();
        //$data['signPackage'] = $signPackage;
        //获取游戏UI
        //$data['GameUI'] =  $this->common->get_game_ui($this->ActiveID, 'ljxxl');

        //添加游戏访问量
        $this->common->add_game_VistNum($this->RoomID, $this->ChannelID, $this->ActiveID, trim($this->openid));
        //$this->common->add_game_user($this->RoomID, $this->ChannelID, $this->ActiveID, trim($this->openid), $data['nickname'], $data['total_gold']);
        $_SESSION['userId'] = $uId;//userId存入session
        //初始化游戏数据
        $data['init'] = $this->initialize($uId);
        unset($data['openId']);
        echo "<pre>";
        print_r($data);
        echo "<pre/>";
        exit;
        $this->load->view('admin/index', $data);
    }

    /*
     * 获取用户信息
     */
    public function getUser()
    {
        $this->baseStrRand = str_shuffle($this->basestr);//校验串,玩家进入游戏时发给客户端
        //$this->check_game_rule('');
        $phone_os 	= addslashes($_SERVER['HTTP_USER_AGENT']);
        $openid 	= addslashes($_REQUEST['openid']);
        $nickname 	= addslashes($_REQUEST['nickName']);
        $headPhoto 	= addslashes($_REQUEST['headPhoto']);
        $data = array();
        if (strpos($phone_os, 'MicroMessenger') === false) {
            // 非微信浏览器禁止浏览
            // $this->load->view('tip', $data);
            //  return;
        } else {
            if (strpos($phone_os, 'Windows Phone') === false) {
                // 非微信浏览器禁止浏览
                // $this->load->view('tip', $data);return;
            }
        }
        $data['openid'] 	= $openid;
        $data['nickname'] 	= $nickname;

        $isexit = $this->db->query("select count(*) as total,Nickname,head_img, local_img,allowMusic, Num  from zy_gamedev_user where Openid='" . $openid . "' AND $this->game_sign_sql ")->row_array();
        $filename = 'static/wxheadimg/zzlx/' . $openid . '.jpg';

        if ($isexit['total'] > 0) {
            if($isexit['head_img'] != $headPhoto ){
                $img_flag = $this->input->get("img");
                if($img_flag){

                }else{
                    $state_base64 = base64_encode('http://h5game.gxtianhai.cn/mntvdb/gamecenter/index.php?d=ljxxl&c=ljxxl&m=getUser&img=1&AID=' . $this->ActiveID);
                    $apiUrl = $this->lb_model->getUserApiByAID($this->ActiveID);
                    $temp = sprintf($apiUrl,$state_base64);
                    if(empty($temp)) show_msg('渠道接口获取失败ChannelID：'.$this->ActiveID.'！');
                    header("Location: ".$temp);
                    return;
                }

            }
            if (!file_exists($filename) || $isexit['head_img'] != $headPhoto) {
                $img_local_url 		= $this->getImg($headPhoto, $filename);
                $headLocalPhoto 	= 'http://static.gxtianhai.cn/mntvdb/gamecenter/' . $img_local_url;
                $data['headimgurl'] = $headLocalPhoto;
            } else {
                $data['headimgurl'] = $isexit['local_img'] ? $isexit['local_img'] : 'http://h5game.gxtianhai.cn/mntvdb/gamecenter/' . $filename;
            }
            $update_nickname 		= "";
            if ($isexit['Nickname'] != $nickname) $update_nickname = "  Nickname='" . $nickname . "' , ";

            $this->db->query("update zy_gamedev_user set baseStrRandCode = '$this->baseStrRand', {$update_nickname}  Updatetime= " . time() . " ,head_img = '" . $headPhoto . "',local_img = '" . 'http://static.gxtianhai.cn/mntvdb/gamecenter/' . $filename . "' where Openid= '" . $openid . "' AND $this->game_sign_sql ");//更新烟豆

            //同步另一台服务器头像
            if($isexit['head_img'] != $headPhoto){
                $this->anotherGetAvatar($headPhoto,$openid);
            }else{
                if(!$this->hasIMG($openid)){
                    $this->anotherGetAvatar($headPhoto,$openid);
                }
            }
            $data['allowMusic'] = $isexit['allowMusic']; //0代表允许播放，1代表禁止播放
            $data['first_time'] = 'no';

        } else {
            $img_local_url 			= $this->getImg($headPhoto, $filename);
            $this->anotherGetAvatar($headPhoto,$openid);//同步另一台服务器头像
            $headLocalPhoto 		= 'http://static.gxtianhai.cn/mntvdb/gamecenter/' . $img_local_url;

            $data['headimgurl'] 	= $headLocalPhoto;
            $data['allowMusic'] = 0;    //默认为0，0代表允许播放，1代表禁止播放
            $data['first_time'] = 'yes';

            $user_data['Openid'] 	= $openid;
            $user_data['Nickname'] 	= $nickname;
            $user_data['head_img'] 	= $headPhoto;
            $user_data['local_img'] = $headLocalPhoto;
            $user_data['Addtime'] 	= time();
            $user_data['Updatetime'] 	= time();
            $user_data['Num'] 	= 0;
            $user_data['allowMusic'] = 0;
            $user_data['phone_os'] 	= $phone_os;
            $user_data['ActiveID'] 	= $this->ActiveID;
            $user_data['ChannelID'] = $this->ChannelID;
            $user_data['RoomID'] 	= $this->RoomID;
            $user_data['GameID'] 	= $this->GameID;
            $user_data['baseStrRandCode']=$this->baseStrRand;
            //获取随机字符串
            $rand = rand(0,45);
            $rand_str = substr($this->baseStrRand,$rand,6).$openid.'ywz';
            $user_data['md5Uid'] = md5($rand_str);
            $insert_sql = $this->db->insert_string('zy_gamedev_user', $user_data);
            $insert_sql = str_replace('INSERT', 'INSERT ignore ', $insert_sql);
            $this->db->query($insert_sql);
        }
        //微信分享用到的信息
        $signPackage = $this->common->getSignPackage();
        $data['signPackage'] = $signPackage;
        //获取游戏UI
        $data['GameUI'] =  $this->common->get_game_ui($this->ActiveID, 'ljxxl');
        //添加信息到游戏排行榜
        $this->add_ranking($data['openid'],0);
        //添加游戏访问量
        $this->common->add_game_VistNum($this->RoomID, $this->ChannelID, $this->ActiveID, trim($openid));
        $this->common->add_game_user($this->RoomID, $this->ChannelID, $this->ActiveID, trim($openid), $data['nickname'], $data['total_gold']);

        //获取我的最佳成绩
        $myBest = $this->getMyBest($data['openid']);
        $data['myBestScore'] = $myBest['Score2'];
        $data['myBestRanking'] = $myBest['pm'];

        //是否截图
        $data['isSaveIMG'] = $this->my_common_model->getRule('isSaveIMG',$this->ChannelID,$this->ActiveID,$this->RoomID);
        //保存浏览记录
        $this->save_vist($openid,$this->baseStrRand);
        $data['openid'] 	= $this->encode($openid);//加密
        $this->load->view('ljxxl/index', $data);


    }



    public function initialize($uId){
        if($uId){
            //种植
            $res = $this->land_model->get_current_all_plant($uId);
            foreach($res as $key=>$value){
                if($value['landStatus']!=0){
                    $need_time = $this->goods_model->get_column_row('goodsName,needTime,becomeGoodsId',array('id'=>$value['goodsId']));
                    //判断是否已经成熟，成熟更新土地表、种植表
                    if((intval(time())-intval($value['startPlantTime']) >= intval($need_time['needTime']))&&$value['landStatus']==1){
                        $update_land['landStatus'] = 2;
                        $this->land_model->update($update_land,array('id'=>$value['land_id'],'uId'=>$uId));
                        $update_plant_record['status'] = 2;
                        $update_plant_record['endPlantTime'] = time();
                        $this->plant_record_model->update($update_plant_record,array('id'=>$value['plant_record_id'],'uId'=>$uId));
                        $res[$key]['landStatus'] = 2;
                    }
                    $res[$key]['needTime'] = $need_time['needTime'];
                    $res[$key]['goodsName'] = $need_time['goodsName'];
                    //根据种子id,查找种子成熟后对应的物品
                    $become = $this->goods_model->get_column_row('goodsName',array('id'=>$need_time['becomeGoodsId']));
                    $res[$key]['becomeGoodsId'] = $need_time['becomeGoodsId'];
                    $res[$key]['becomeGoodsName'] = $become['goodsName'];
                }else{
                    $res[$key]['needTime'] = null;
                    $res[$key]['goodsName'] = null;
                }
            }
            //加工
            $working_res = $this->working_record_model->get_current_all_working($uId);
            foreach($working_res as $key=>$value){
                $need_working = $this->goods_model->get_column_row('goodsName,needTime',array('id'=>$value['peifangId']));
                if($value['status']==1){
                    //判断是否已经加工完成，完成则更新加工表
                    if(intval(time())-intval($value['startWorkingTime']) >= intval($need_working['needTime'])){
                        $update_working_record['status'] = 2;
                        $update_working_record['endWorkingTime'] = time();
                        $this->working_record_model->update($update_working_record,array('id'=>$value['working_record_id'],'uId'=>$uId));
                        $working_res[$key]['status'] = 2;
                        $working_res[$key]['endWorkingTime'] = $update_working_record['endWorkingTime'];
                    }
                }
                $working_res[$key]['needTime'] = $need_working['needTime'];
                $working_res[$key]['goodsName'] = $need_working['goodsName'];
                //根据配方id,查找配方加工后对应的物品
                $become = $this->goods_model->get_column_row('goodsName',array('id'=>$value['goodsId']));
                $working_res[$key]['becomeGoodsId'] = $value['goodsId'];
                $working_res[$key]['becomeGoodsName'] = $become['goodsName'];
                unset($working_res[$key]['goodsId']);
            }
            //包装
            $packing_res = $this->packing_record_model->get_current_all_packing($uId);
            foreach($packing_res as $key=>$value){
                $need_packing = $this->goods_model->get_column_row('goodsName,needTime',array('id'=>$value['packingId']));
                if($value['status']==1){
                    //判断是否已经包装完成，完成则更新包装表
                    if(intval(time())-intval($value['startPackingTime']) >= intval($need_packing['needTime'])){
                        $update_packing_record['status'] = 2;
                        $update_packing_record['endPackingTime'] = time();
                        $this->packing_record_model->update($update_packing_record,array('id'=>$value['packing_record_id'],'uId'=>$uId));
                        $packing_res[$key]['status'] = 2;
                        $packing_res[$key]['endPackingTime'] = $update_packing_record['endPackingTime'];
                    }
                }
                $packing_res[$key]['needTime'] = $need_packing['needTime'];
                $packing_res[$key]['goodsName'] = $need_packing['goodsName'];
                //根据包装id,查找包装完成后对应的物品
                $become = $this->goods_model->get_column_row('goodsName',array('id'=>$value['goodsId']));
                $packing_res[$key]['becomeGoodsId'] = $value['goodsId'];
                $packing_res[$key]['becomeGoodsName'] = $become['goodsName'];
                unset($packing_res[$key]['goodsId']);
            }
            //种子培育
            $breed_res = $this->breed_record_model->get_current_all_breed($uId);
            foreach($breed_res as $key=>$value){
                $need_breed = $this->goods_model->get_column_row('goodsName,breedTime',array('id'=>$value['goodsId']));
                if($value['status']==1){
                    //判断是否已经培育完成，完成则更新种子培育表
                    if(intval(time())-intval($value['startBreedTime']) >= intval($need_breed['breedTime'])){
                        $update_breed_record['status'] = 2;
                        $update_breed_record['endBreedTime'] = time();
                        $this->breed_record_model->update($update_breed_record,array('id'=>$value['breed_record_id'],'uId'=>$uId));
                        $breed_res[$key]['status'] = 2;
                        $breed_res[$key]['endBreedTime'] = $update_breed_record['endBreedTime'];
                    }
                }
                $breed_res[$key]['needTime'] = $need_breed['breedTime'];
                $breed_res[$key]['goodsName'] = $need_breed['goodsName'];
            }
            //配方研究
            $research_res = $this->research_record_model->get_current_all_research($uId);
            foreach($research_res as $key=>$value){
                $need_research = $this->goods_model->get_column_row('goodsName,breedTime',array('id'=>$value['goodsId']));
                if($value['status']==1){
                    //判断配方是否已经研究完成，完成则更新配方研究表
                    if(intval(time())-intval($value['startResearchTime']) >= intval($need_research['breedTime'])){
                        $update_research_record['status'] = 2;
                        $update_research_record['endResearchTime'] = time();
                        $this->research_record_model->update($update_research_record,array('id'=>$value['research_record_id'],'uId'=>$uId));
                        $research_res[$key]['status'] = 2;
                        $research_res[$key]['endResearchTime'] = $update_research_record['endResearchTime'];
                    }
                }
                $research_res[$key]['needTime'] = $need_research['breedTime'];
                $research_res[$key]['goodsName'] = $need_research['goodsName'];
            }

            $data['plant'] = $res;
            $data['working'] = $working_res;
            $data['packing'] = $packing_res;
            $data['breed'] = $breed_res;
            $data['research'] = $research_res;
            $result = $data;
            //$result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$data);
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        return $result;
    }














    /**
     * 生成缩略图函数  剪切
     *
     * @param $imgurl 图片路径
     * @param $width 缩略图宽度
     * @param $height 缩略图高度
     * @return string 生成图片的路径 类似：./uploads/201203/img_100_80.jpg
     */
    private function thumb($imgurl, $width = 100, $height = 100)
    {
        if (empty($imgurl))
            return '不能为空';
        include_once 'application/libraries/image_moo.php';
        $moo = new Image_moo();
        $moo->load($imgurl);
        $moo->resize_crop($width, $height);
        $moo->save_pa('', '', true);
    }

    /*
    *@通过curl方式获取指定的图片到本地
    *@ 完整的图片地址
    *@ 要存储的文件名
    */
    private function getImg($url = "", $filename = "")
    {
        //去除URL连接上面可能的引号
        //$url = preg_replace( '/(?:^['"]+|['"/]+$)/', '', $url );
        if(!strstr($url,"wx.qlogo.cn"))  return '';

        $hander = curl_init();

        $fp = fopen($filename, 'wb');
        //$fp = file_get_contents($filename);
        curl_setopt($hander, CURLOPT_URL, $url);
        curl_setopt($hander, CURLOPT_FILE, $fp);
        curl_setopt($hander, CURLOPT_HEADER, 0);
        curl_setopt($hander, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
        curl_setopt($hander, CURLOPT_TIMEOUT, 60);
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);

        $this->thumb($filename, 58, 58);
        return $filename;
    }




















}
