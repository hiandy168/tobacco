<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 通用模型
class My_common_model extends CI_Model
{
	private $appId = 'wx309d71544a02bd4a';//紫云
    private $appSecret = 'b8d778616059287d3854c8ef433d97c2';//紫云
    function __construct ()
    {
        parent::__construct();
    }
    
    // 获取渠道列表
    function get_channel_list($channelID = 0)
    {
		$result = array();
		$this->db->select('ChannelID, ChannelName');
        if($channelID) {
            $query = $this->db->get_where('zy_channel_main', array('ChannelID' => $channelID));
			$result = $query->row_array();
        }else{
			$this->db->order_by("ChannelID", "desc");
			$query = $this->db->get('zy_channel_main');			
			$result = $query->result_array();
		}				
		return $result;
    }
	
	// 获取游戏库列表
    function get_room_list($RoomID = 0)
    {
		$result = array();
		//$this->db->select('RoomID, GameName','');
        if($RoomID) {
            $query = $this->db->get_where('zy_game_room', array('RoomID' => $RoomID));
			$result = $query->row_array();
        }else{
			$this->db->order_by("RoomID", "desc");
			$query = $this->db->get('zy_game_room');			
			$result = $query->result_array();
		}			
		return $result;
    }
	// 获取活动列表
    function get_active_list($ActiveID = 0)
    {
		$result = array();
		$this->db->select('ActiveID, ActiveName, RoomID');
        if($ActiveID) {
            $query = $this->db->get_where('zy_active_main', array('ActiveID' => $ActiveID));
			$result = $query->row_array();
        }else{
			$this->db->order_by("ActiveID", "desc");
			$query = $this->db->get('zy_active_main');			
			$result = $query->result_array();
		}			
		return $result;
    }
	
	
	//获取系统用户
	function get_user($uid){
		$result = 0;
		if($uid) {
            $query = $this->db->get_where('zy_sys_manager', array('UID' => $uid));
			$result = $query->row_array();
        }
		return $result;		  
	}
	
	//解禁黑名单
	function release($ids, $field = 'id') {
		$data['ReleaseTime'] = time();
		$data['ReleaseUid'] = $this->session->userdata('UID');
		if (is_numeric ( $ids )) {
			$this->db->where('ActiveBlackID', $ids);			
            $query = $this->db->update('zy_active_blacklist', $data);			
		} else {
			$ids = implode ( ",", $ids );
			$query = $this->db->query ( "UPDATE set zy_active_blacklist ReleaseTime=".$data['ReleaseTime']." , ReleaseUid=".$data['ReleaseUid']."  where ActiveBlackID in ($ids)" );
		}
		return  $this->db->affected_rows();
	}

	//添加黑名单
	function addBlackList($ActiveID, $Openid, $NickName, $Remark) {
		$data['ActiveID'] = $ActiveID;
		$data['Openid'] = $Openid;
		$data['NickName'] = $NickName;
		$data['Remark'] = $Remark;
		$Active = $this->db->get_where('zy_active_main',array('ActiveID'=>$data['ActiveID']))->row_array();
        
        $data['ChannelID'] = $Active['ChannelID'];
        $data['GameID'] = $Active['GameID'];
        $data['AddTime'] = time();
        $data['AddUid'] = $this->session->userdata('UID');

        //判断是否已存在
        $is_exists = $this->db->query("SELECT * FROM zy_active_blacklist WHERE Openid='{$data['Openid']}' and ActiveID={$data['ActiveID']} and ReleaseUid is NULL ")->row_array();
        if(!$is_exists){
            if($this->db->insert('zy_active_blacklist',$data)){
                return  $this->db->insert_id();
            }
        }
		return  false;
	}
	
	//获取活动对应的游戏的菜单管理
	function get_nav_by_roomid($roomid){
		if(!$roomid) return array();		
			$this->db->order_by('Sort','ASC');
            $query = $this->db->get_where('zy_game_reg_nav', array('RoomID' => $roomid));
			$result = $query->result_array();
        if($result) 
		return $result;	
		else
		return array();	
	}

	//获取活动、游戏状态
	/**
	* @param $ActiveID 活动ID
	* @param $RoomID   游戏ID
	* @return $result array() 
	* 活动 0:未开启 1:活动中 2:已结束
	* 游戏 0:测试 1:开放 2:停用 3:维护
	**/
	function get_active_game_status($ActiveID,$RoomID){
		if(!$ActiveID || !$RoomID){
			return false;
		}

		$result = array();

		$active = $this->db->get_where('zy_active_main', array('ActiveID' => $ActiveID))->row_array();

		switch ($active['Status']) {
			case '0':
				$result['status'] = false;
				$result['msg'] = '活动未开启';
				break;

			case '3':
			case '1':
				$result['status'] = true;
				$result['msg'] = '活动中';
				$game = $this->db->get_where('zy_game_room', array('RoomID' => $RoomID))->row_array();
				switch ($game['Status']) {
					case '0':
					case '1':
						$result['status'] = true;
						$result['msg'] = '活动中';
						break;
					case '2':
						$result['status'] = false;
						$result['msg'] = '游戏已停用';
						break;
					case '3':
						$result['status'] = false;
						$result['msg'] = '游戏维护中';
						break;
					
					default:
						# code...
						break;
				}
				break;

			case '2':
				$result['status'] = false;
				$result['msg'] = '活动已结束';
				break;
			
			default:
				# code...
				break;
		}

		return $result;
	}

	//游戏访问次数统计

	function add_game_VistNum($RoomID,$ChannelID,$ActiveID,$openid){
		if(!$RoomID || !$ChannelID || !$ActiveID || !$openid){
			return false;
		}

		$data['Openid'] = $openid;
		$data['ChannelID'] = $ChannelID;
		$data['ActiveID'] = $ActiveID;
		$data['RoomID'] = $RoomID;
		$data['AddTime'] = time();

		$this->db->insert('zy_game_log',$data);

		$this->db->where('RoomID',$RoomID);
		$this->db->set('VistNum', 'VistNum+1', FALSE);
        $this->db->update('zy_game_room');
        return true;
	}
	
//添加公共用户表
	function add_game_user($RoomID,$ChannelID,$ActiveID,$openid,$nickname, $num = 0){
		if(!$RoomID || !$ChannelID || !$ActiveID || !$openid){
			return false;
		}
		$game_row = $this->db->get_where('zy_active_game', array('ChannelID'=>$ChannelID,'ActiveID'=>$ActiveID,'RoomID'=>$RoomID),1)->row_array();
		$GameID = $game_row['GameID'];
		
		$user =  $this->db->get_where('zy_gamelist_user', array('ChannelID'=>$ChannelID,'ActiveID'=>$ActiveID,'GameID'=>$GameID, 'Openid'=>$openid),1)->row_array();
		if($user){
			$this->db->update('zy_gamelist_user',array('UpdateTime'=>time(),'Num'=>$num),array('UserID'=>$user['UserID']));
		}else{			
			$this->db->insert('zy_gamelist_user',array('Openid'=>$openid,'Nickname'=>$nickname,'ChannelID'=>$ChannelID,'ActiveID'=>$ActiveID,'GameID'=>$GameID,'Num'=>$num,'UpdateTime'=>time(),'AddTime'=>time()));
		}
		
	}
	
	

	//获取渠道活动数
	function getActiveNum($ChannelID){
		if(!$ChannelID){
			return 0;
		}

		$this->db->where('ChannelID',$ChannelID);
		return $this->db->get('zy_active_main')->num_rows();

	}

	//获取渠道接口数
	function getChannelApiNum($ChannelID){
		if(!$ChannelID){
			return 0;
		}

		$this->db->where('ChannelID',$ChannelID);
		return $this->db->get('zy_channel_api')->num_rows();

	}

	//获取渠道接口最近更新时间
	function getChannelApiUpdateTime($ChannelID){
		if(!$ChannelID){
			return 0;
		}

		$this->db->where('ChannelID',$ChannelID);
		$res = $this->db->get('zy_channel_api')->row_array();
		return $res['Uptime'];
	}

	//获取活动参与人数
	function getActivePartNum($ActiveID){
		if(!$ActiveID){
			return 0;
		}

		$this->db->where('ActiveID',$ActiveID);
		$res = $this->db->get('zy_gamelist_user')->num_rows();
		return $res;
	}

	//添加异常记录
	/**
	*
	* $Type 0:数据异常1:接口异常2:其他
	*
	**/
	function addErrLog($Openid, $ChannelID, $ActiveID, $RoomID, $Content, $Type = 0){
		if(!$Openid || !$ChannelID || !$ActiveID || !$RoomID || !$Content){
			return false;
		}

		$data['Openid'] = $Openid;
		$data['ChannelID'] = $ChannelID;
		$data['ActiveID'] = $ActiveID;
		$data['RoomID'] = $RoomID;
		$data['Content'] = $Content;
		$data['AddTime'] = time();
		$data['Type'] = intval($Type);
		$data['Ip'] = ip();
		$data['Browser'] = getbrowser();

		return $this->db->insert('zy_error_log',$data);
	}

	//添加系统操作记录
	
	function addSysLog($Comment = ''){
		
		$this->load->library('user_agent');
        
        $post = $_POST;
        $temp = '';
        if($post){
        	foreach ($post as $k => $v) {
        		if(is_array($v)){
        			foreach ($v as $p => $r) {
        				$temp .= '&nbsp;&nbsp;'.$p.'=>'.$r.'<br/><br/>';
        			}
        		}else{
        			$temp .= $k.'=>'.$v.'<br/><br/>';
        		}
        		
        	}
        }

		$data['Controller'] = $_GET['c'];
		$data['Method'] = $_GET['m']?$_GET['m']:'index';
		$data['Param'] = $temp;
		$data['Url'] = $_SERVER['REQUEST_URI'];
		$data['Ip'] = ip();
		$data['Browser'] = $this->agent->browser().$this->agent->version();
		$data['Os'] = getos();
		$data['Comment'] = $Comment;
		$data['AddTime'] = time();
		$data['UID'] = $this->session->userdata('UID');

		if($post){
			$res = $this->db->insert('zy_sys_log',$data);
		}else{
			$Isexists = $this->db->query("SELECT * FROM zy_sys_log WHERE Controller = '{$data['Controller']}' AND Method = '{$data['Method']}' AND {$data['AddTime']}-AddTime < 60 AND UID = {$data['UID']}")->row_array();
			if($Isexists){
				$res = $this->db->update('zy_sys_log',array('AddTime' => $data['AddTime']),array('Id' => $Isexists['Id']));
			}else{
				$res = $this->db->insert('zy_sys_log',$data);
			}
		}

		
		return $res;
	}

	//根据RuleSign获取游戏规则
	function getRule($RuleSign, $ChannelID, $ActiveID, $RoomID){
		if(!RuleSign || !$ChannelID || !ActiveID || !$RoomID){
			return false;
		}

		$res = $this->db->query("SELECT RuleSet FROM zy_gamelist_rule WHERE RuleSign = '{$RuleSign}' and ChannelID = {$ChannelID} and ActiveID = {$ActiveID} and RoomID = {$RoomID}");
        $ruleset = $res->row_array();

        return $ruleset['RuleSet'];
	}

		//获取网页分享的微信信息签名	
	public function getSignPackage() {
	    $jsapiTicket = $this->getJsApiTicket();
	
	    // 注意 URL 一定要动态获取，不能 hardcode.
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	    $timestamp = time();
	    $nonceStr = $this->createNonceStr();
	
	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	
	    $signature = sha1($string);
	
	    $signPackage = array(
	        "appId"     => $this->appId,
	        "nonceStr"  => $nonceStr,
	        "timestamp" => $timestamp,
	        "url"       => $url,
	        "signature" => $signature,
	        "rawString" => $string
	    );
	    return $signPackage;
	}
	
	private function createNonceStr($length = 16) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $str = "";
	    for ($i = 0; $i < $length; $i++) {
	        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	    }
	    return $str;
	}
	
	private function getJsApiTicket() {
	    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
		$isexit = $this->db->query("select *   from zy_racedog_wx_token where type='ticket' ")->row_array();	
	    
	    if ($isexit['expire_time'] < time()) {
	        $accessToken = $this->getAccessToken();
	        // 如果是企业号用以下 URL 获取 ticket
	        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
	        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
	        $res = json_decode(curlGetData($url));
	        $ticket = $res->ticket;
	        if ($ticket) {
				$data['expire_time'] = time() + 7000;
	            $data['content'] = $ticket;
				$this->db->where('type','ticket');
	            $this->db->update('zy_racedog_wx_token',$data);	            
	        }
	    } else {
	        $ticket = $isexit['content'] ;
	    }
	
	    return $ticket;
	}
	
	private function getAccessToken() {
	    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	    $isexit = $this->db->query("select *   from zy_racedog_wx_token where type='token' ")->row_array();	
	    if ($isexit['expire_time'] < time()) {
	        // 如果是企业号用以下URL获取access_token
	        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
	        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
	        $res = json_decode(curlGetData($url));
	        $access_token = $res->access_token;
	        if ($access_token) {
				
	            $data['expire_time'] = time() + 7000;
	            $data['content'] = $access_token;
				$this->db->where('type','token');
	            $this->db->update('zy_racedog_wx_token',$data);
	        }
	    } else {
	        $access_token = $isexit['content'];
	    }
	    return $access_token;
	}

	function get_game_ui($ActiveID, $folder){
		 //获取该活动自定义资源
        $Resources_arr = $this->db->query("SELECT VarName,ReSrc FROM zy_gamelist_resources WHERE  ActiveID = {$ActiveID}")->result_array();
		$Resources = array();
        foreach ($Resources_arr as $k => $v) {
            $Resources[trim($v['VarName'])] = str_replace('static/gameroom/'.$folder.'/', '', trim($v['ReSrc'])) ;
    	}
        return json_encode($Resources);
	}
    
    
}
