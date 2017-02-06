<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class main extends CI_Controller {

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
            $isexit = $this->db->query("select count(*) as total,userId,nickName,headImg, localImg,allowMusic,experienceValue,gameGrade,leDouNum  from zy_user where openId='".$data['openId']."' AND $this->game_sign_sql")->row_array();

            if($isexit['total'] > 0){
                //判断用户有没有作弊
                //$row_status2 = $this->db->query("select Status2 from zy_gamedev_ranking WHERE Openid='$wx_info[openid]'")->row_array();

                /*if($row_status2['Status2']==1){
                    $data['msg'] = "您涉嫌作弊！";
                    $this->load->view('tip', $data);
                    return;
                }*/

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
                $data['userId'] = $isexit['userId'];
                $data['experienceValue'] = $isexit['experienceValue'];
                $data['gameGrade'] = $isexit['gameGrade'];
                $data['leDouNum'] = $isexit['leDouNum'];
                $data['first_time'] = 'no';

            }else{

                $img_local_url = $this->getImg($headurl, $filename);
                $headLocalPhoto = base_url() . $img_local_url;

                $data['headimgurl'] = $headLocalPhoto;
                //用户表保存用户信息
                $user_data['openId'] =  $wx_info['openId'];
                $user_data['nickName'] =  $wx_info['nickName'];
                $user_data['headImg'] =  $wx_info['headimgurl'];
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
                $insert_sql = $this->db->insert_string('zy_user',$user_data);
                $insert_sql = str_replace('INSERT', 'INSERT ignore ', $insert_sql);
                $this->db->query($insert_sql);
                $uId = $this->db->insert_id();
                //初始化用户土地（新用户默认拥有6块土地）
                for($i=0;$i<6;$i++){
                    $this->land_model->insert(array('uId'=>$uId,'addTime'=>$time));
                }

                $data['userId'] = $uId;
                $data['allowMusic'] = 0;    //默认为0，0代表允许播放，1代表禁止播放
                $data['experienceValue'] = 0;
                $data['gameGrade'] = 500;
                $data['leDouNum'] = 100;
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
        $_SESSION['userId'] = $data['userId'];//userId存入session

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


    /**
     *接口名称：购买土地接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=save_buy_land
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_num：购买土地的数量
     *      land_source：0系统商城，1真龙商行
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:   时间戳
     *
     **/
    public function save_buy_land(){
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //从物品表zy_goods获取土地属性信息（土地的id=4）
            $land_msg = $this->goods_model->get_one(4,'id');
            $land_num = $this->input->post("land_num");
            $land_source = $this->input->post("land_source");
            //确定土地价钱
            $price = $land_msg['priceByLD'];

            if($land_num){
                //减去相应的乐豆
                $ld_num = $this->user_model->get_column_row('leDouNum',array('userId'=>$uId));
                if($ld_num['leDouNum']>=$price*$land_num){
                    $update_ld['leDouNum'] = $ld_num['leDouNum']-$price*$land_num;
                    $affect = $this->user_model->update($update_ld,array('userId'=>$uId));
                    if($affect){
                        //插入购买记录表
                        $insert_buy_record['goodsId'] = 4;
                        $insert_buy_record['uId'] = $uId;
                        $insert_buy_record['singlePrice'] = $price;
                        $insert_buy_record['totalNum'] = $land_num;
                        $insert_buy_record['totalPrice'] = $price*$land_num;
                        $insert_buy_record['addTime'] = time();
                        $insert_buy_record['source'] = $land_source;
                        $buy_record_id = $this->buy_record_model->insert($insert_buy_record);
                        if($buy_record_id){
                            //插入土地表zy_land
                            $insert_land['buyRecordId'] = $buy_record_id;
                            $insert_land['uId'] = $uId;
                            $insert_land['addTime'] = time();
                            for($i=0 ; $i<$land_num ; $i++){
                                $res = $this->land_model->insert($insert_land);
                            }
                            if($res){
                                $result = array('code'=>1,'msg'=>'保存成功','time'=>time(),'ledou_num'=>$update_ld['leDouNum']);
                            }else{
                                $result = array('code'=>0,'msg'=>'土地保存失败','time'=>time(),'ledou_num'=>$update_ld['leDouNum']);
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'购买记录写入数据库错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新乐豆数量错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'乐豆不足','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'购买的土地数量必须大于0','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }

        echo json_encode($result);
    }

    /**
     *接口名称：购买种子接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=save_buy_seed
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      seed_type：购买种子的类型：1巴西种子，2海南种子，3古巴种子
     *      seed_num：购买种子的数量
     *      seed_source：0系统商城，1真龙商行
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:   时间戳
     *
     **/
    public function save_buy_seed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //从物品表zy_goods获取种子属性信息
            $seed_type = $this->input->post("seed_type");
            $seed_num = $this->input->post("seed_num");
            if($seed_num){
                //判断仓库是否已经存满物品
                $remain_num = $this->is_store_full($md5_uid);
                if($remain_num>=$seed_num){
                    $seed_source = $this->input->post("seed_source");
                    $seed_msg = $this->goods_model->get_one($seed_type,'id');
                    //确定种子价钱
                    $price = $seed_msg['priceByLD'];

                    //减去相应的乐豆
                    $ld_num = $this->user_model->get_column_row('leDouNum',array('userId'=>$uId));
                    if($ld_num['leDouNum']>=$price*$seed_num) {
                        $update_ld['leDouNum'] = $ld_num['leDouNum'] - $price*$seed_num;
                        $affect = $this->user_model->update($update_ld,array('userId'=>$uId));
                        if ($affect) {
                            //插入购买记录表
                            $insert_buy_record['goodsId'] = $seed_type;
                            $insert_buy_record['uId'] = $uId;
                            $insert_buy_record['singlePrice'] = $price;
                            $insert_buy_record['totalNum'] = $seed_num;
                            $insert_buy_record['totalPrice'] = $price*$seed_num;
                            $insert_buy_record['addTime'] = time();
                            $insert_buy_record['source'] = $seed_source;
                            $buy_record_id = $this->buy_record_model->insert($insert_buy_record);
                            if($buy_record_id){
                                //查询仓库表 zy_store_house 是否已经存在该种类型种子的记录，有则更新即可
                                $isexist = $this->store_house_model->get_column_row("id,num",array('goodsId'=>$seed_type,'uId'=>$uId));
                                if($isexist['id']){
                                    $update_seed['num']=$isexist['num']+$seed_num;
                                    $res = $this->store_house_model->update($update_seed,array('id'=>$isexist['id']));
                                }else{
                                    $insert_seed['uId'] = $uId;
                                    $insert_seed['goodsId'] = $seed_type;
                                    $insert_seed['num'] = $seed_num;
                                    $insert_seed['updateTime'] = time();
                                    $res = $this->store_house_model->insert($insert_seed);
                                }

                                if($res){
                                    $result = array('code'=>1,'msg'=>'保存成功','ledou_num'=>$update_ld['leDouNum']);
                                }else{
                                    $result = array('code'=>0,'msg'=>'保存失败','ledou_num'=>$update_ld['leDouNum']);
                                }
                            }else{
                                $result = array('code'=>0,'msg'=>'购买记录写入数据库错误','time'=>time());
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'更新乐豆数量错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'乐豆不足','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'仓库容量不足，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'购买的土地数量必须大于0','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：播种接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=start_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_id：土地编号 0,1,2,3,4,5
     *      seed_type：种子类型 1巴西种子，2海南种子，3古巴种子
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 土地id
     *      plant_record_id :种植记录id
     **/

    public function start_plant(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $land_id = $this->input->post("land_id");
            $seedType = $this->input->post("seed_type");
            $is_land_free = $this->get_land_status($land_id);//判断土地是否空闲
            //判断种子是否充足
            $num = $this->store_house_model->get_column_row('num',array('goodsId'=>$seedType,'uId'=>$uId));
            if($num['num']>=1){
                //更新仓库表,播下一颗种子，相应从仓库减去一颗
                $update_num['num'] = $num['num']-1;
                $affect = $this->store_house_model->update($update_num,array('goodsId' => $seedType,'uId'=>$uId));
                if($affect){
                    //更新土地表的种植状态
                    $update_land['landStatus'] = 1;
                    $res = $this->land_model->update($update_land,array('id' => $land_id,'uId'=>$uId));
                    if($res=1){
                        //zy_plant_record插入种植记录
                        $insert['goodsId'] = $seedType;
                        $insert['uId'] = $uId;
                        $insert['landId'] = $land_id;
                        $insert['startPlantTime'] = time();
                        $insert['status'] = 1;
                        $plant_record_id = $this->plant_record_model->insert($insert);
                        if($plant_record_id){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$land_id,'plant_record_id'=>$plant_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'保存种植表错误','time'=>time(),'id'=>$land_id,'plant_record_id'=>$plant_record_id);
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新土地表错误','time'=>time(),'id'=>$land_id);
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time(),'id'=>$land_id);
                }
            }else{
                $result = array('code'=>0,'msg'=>'种子数量不足','time'=>time(),'id'=>$land_id);
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：种子成熟接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=end_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_id：土地编号 0,1,2,3,4,5
     *      plant_record_id：种植记录id
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 土地id
     *      plant_record_id :种植记录id
     **/
    public function end_plant(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断种子种植时间是否已经到达成熟的时间
            $plant_record_id = $this->input->post("plant_record_id");
            $plant_arr = $this->plant_record_model->get_column_row('goodsId,status,startPlantTime', array('id'=>$plant_record_id,'uId'=>$uId));
            if($plant_arr['goodsId']&&$plant_arr['status']==1){ //判断种植记录是否存在，并且status==1
                $need_time = $this->goods_model->get_column_row('needTime', array('id'=>$plant_arr['goodsId']));
                if($need_time['needTime']){
                    if( (intval(time()) - intval($plant_arr['startPlantTime'])) >= $need_time['needTime']){
                        //更新土地表种植状态
                        $id = $this->input->post("land_id");
                        $update_land['landStatus'] = 2;
                        $res = $this->land_model->update($update_land,array('id' => $id,'uId'=>$uId));
                        if($res){
                            //更新种植记录表种植状态
                            $update_plant_record['endPlantTime'] = time();
                            $update_plant_record['status'] = 2;
                            $res = $this->plant_record_model->update($update_plant_record,array('id' => $plant_record_id,'uId'=>$uId));
                            if($res){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$id,'plant_record_id'=>$plant_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'更新种植记录表错误','time'=>time());
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'更新土地表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'种子尚未成熟','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'种子成熟时间未设置','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'种植记录不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }

        echo json_encode($result);
    }

    /**
     *接口名称：收割接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=shou_ge
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_id：土地编号 0,1,2,3,4,5
     *      plant_record_id：种植记录id
     *返回参数：
     * 	    code：返回码 0错误，1正确，2仓库已满，暂缓收割
     * 	    message：描述信息
     *      id: 土地id
     *      plant_record_id :种植记录id
     **/
    public function shou_ge(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断种子种植时间是否已经到达成熟的时间
            $plant_record_id = $this->input->post("plant_record_id");
            $plant_status = $this->plant_record_model->get_column_row('status', array('id'=>$plant_record_id,'uId'=>$uId));
            if($plant_status['status']==2){
                //判断仓库是否已经存满物品
                if($this->is_store_full($md5_uid)){
                    //更新土地表种植状态
                    $id = $this->input->post("land_id");
                    $update_land['landStatus'] = 0;//(收割完毕所以土地状态变为0：空闲状态)
                    $res = $this->land_model->update($update_land,array('id' => $id,'uId'=>$uId));
                    if($res){
                        //更新种植记录表种植状态
                        $update_plant_record['status'] = 3;//种植记录状态变为3：已经收割完毕。
                        $plant_record_id = $this->input->post("plant_record_id");
                        $res = $this->plant_record_model->update($update_plant_record,array('id' => $plant_record_id,'uId'=>$uId));
                        if($res){
                            //收割一颗种子，仓库多一张烟叶（一期只能采摘粗质烟叶）
                            $isexist = $this->db->get_where('zy_store_house', array('goodsId' => 5,'uId'=>$uId))->row_array();
                            if($isexist['goodsId']){
                                $update_num['num'] = $isexist['num']+1;
                                $update_num['updateTime'] = time();
                                $affect = $this->store_house_model->update($update_num,array('goodsId' => 5,'uId'=>$uId));
                                if($affect){
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$id,'plant_record_id'=>$plant_record_id);
                                }else{
                                    $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time());
                                }
                            }else{
                                $insert['goodsId'] = 5;
                                $insert['uId'] = $uId;
                                $insert['buyRecourdId'] = '';
                                $insert['num'] = 1;
                                $insert['updateTime'] = time();
                                $res = $this->store_house_model->insert($insert);
                                if($res){
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$id,'plant_record_id'=>$plant_record_id);
                                }else{
                                    $result = array('code'=>0,'msg'=>'保存仓库表错误','time'=>time());
                                }
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'更新种植记录表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新土地表错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>2,'msg'=>'仓库已满，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'种子尚未成熟','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：获取仓库里面的种子
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=get_seed
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 物品id
     *      goodsName :物品名称
     *      num：物品数量
     **/
    public function get_seed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_seed_num();
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：获取仓库里面的所有物品
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=get_store_all
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 物品id
     *      goodsName :物品名称
     *      num：物品数量
     *      total_num：仓库当前存储量
     *      storeTotalCap：仓库总容量
     **/
    public function get_store_all(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $data['goods'] = $res = $this->store_house_model->get_all_num();//获取仓库所有物品名称和对应数量
            if($res){
                //获取当前仓库存储量
                $data['total_num'] = $this->current_store_num($md5_uid);
                //获取仓库总容量
                $data['storeTotalCap'] = $this->get_store_tocap($md5_uid);

                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$data);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    //获取仓库总容量
    private function get_store_tocap($md5_uid){
        $user_id = $this->user_model->get_column_row('storeTotalCap', array('md5Uid'=>$md5_uid));
        return $user_id['storeTotalCap'];
    }

    //获取当前仓库存量
    private function current_store_num($md5_uid){
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_current_store_num($uId);
            $total_num = 0;
            if($res){
                //获取当前仓库存储量
                foreach($res as $value){
                    $total_num += $value['num'];
                }
            }
            return $total_num;
        }
    }

    //判断仓库是否已经存满物品
    private function is_store_full($md5_uid){
        //先获取仓库总量
        $storeTotalCap = $this->get_store_tocap($md5_uid);
        //获取当前仓库存量
        $total_num = $this->current_store_num($md5_uid);
        if($storeTotalCap > $total_num){
            return 1;   //未存满
        }else{
            return 0;   //已经存满
        }
    }

    //判断某块土地状态
    private function get_land_status($land_id){
        $res = $this->land_model->get_column_row('landStatus',array('id'=>$land_id));
        return $res['landStatus'];
    }

    /**
     *接口名称：获取所有土地及其种植的物品、状态
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=current_all_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 土地id
     *      landStatus : 土地状态 0空闲、1正在种植、2已经成熟(但未收割)
     *      plant_record_id：种植记录id
     *      goodsId：种植物品id
     *      startPlantTime：开始种植时间
     *      needTime:   成长所需要的时长
     *      goodsName:  物品中文名称
     *
     **/
    public function current_all_plant(){
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->land_model->get_current_all_plant($uId);
            foreach($res as $key=>$value){
                if($value['landStatus']!=0){
                    $need_time = $this->goods_model->get_column_row('goodsName,needTime',array('id'=>$value['goodsId']));
                    $res[$key]['needTime'] = $need_time['needTime'];
                    $res[$key]['goodsName'] = $need_time['goodsName'];
                }else{
                    $res[$key]['needTime'] = null;
                    $res[$key]['goodsName'] = null;
                }
            }
            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

















}
