<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 通用模型
class Common extends CI_Model
{
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
		$this->db->select('RoomID, GameName');
        if($channelID) {
            $query = $this->db->get_where('zy_game_room', array('RoomID' => $RoomID));
			$result = $query->row_array();
        }else{
			$this->db->order_by("RoomID", "desc");
			$query = $this->db->get('zy_game_room');			
			$result = $query->result_array();
		}		
		return $result;
    }
    
    
}
