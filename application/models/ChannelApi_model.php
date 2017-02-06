<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 模型 基类，其他模型需要先继承本类
 */
class ChannelApi_model extends CI_Model {
	public $table = 'zy_channel_api'; // 数据库表名称
	function __construct() {
		parent::__construct ();		
	}
	
	
	/**
	 * 根据ChannelID,接口标记调用接口地址
	 *
	 * @param int $ChannelID  
	 * @param string $ApiSign 
	 * @return string $url 接口地址
	 */
	function getApi($ChannelID, $ApiSign) {
		$this->db->where ( array('ChannelID'=>$ChannelID,'ApiSign'=>$ApiSign) );
		$query = $this->db->get ( $this->table, 1 );
		$value = $query->row_array ();
		if(!$value || !$value['Status']){
			return false;
		}	
		return $value['ApiUrl'];
	}





	
}
