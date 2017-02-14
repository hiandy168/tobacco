<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 管理员  控制器 by tangjian

include 'content.php';

class player extends Content
{
    function __construct ()
    {
        parent::__construct();

        $this->control = 'player';
        $this->baseurl = 'index.php?d=admin&c=player';
        $this->table = 'zy_user';
        $this->list_view = 'player_list';
    }

    // 首页
    public function index ()
    {
        $keywords = trim($_REQUEST['keywords']);
        $order = $_GET['order'] ? $_GET['order'] : false;
        $searchsql = '1';
        $order_sql = ' id desc ';
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index";
        } else {
            $searchsql .= " AND (openId like '%{$keywords}%' OR nickName like '%{$keywords}%') ";
            $config['base_url'] = $this->baseurl ."&m=index&keywords=" . rawurlencode($keywords);
        }
        if(!empty($order)){
            $order_sql = ' leDouNum desc ';
            $data['order'] = 'leDouNum';
            $config['base_url'] .= $config['base_url'] ."&order=leDouNum";
        }
        $query = $this->db->query("SELECT COUNT(*) AS num FROM $this->table WHERE $searchsql ");
        $count = $query->row_array();
        $data['count'] = $count['num'];
        $config['total_rows'] = $count['num'];
        $config['per_page'] = $this->per_page;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];
        $data_sql = 'select userId,openId,nickName,phoneOs,experienceValue,gameGrade,leDouNum,goldNum,headImg,localImg,updateTime,addTime,status FROM '.$this->table.' where '.$searchsql.' ORDER BY ' .$order_sql. ' limit '. $offset . ','.$this->per_page;
        $query = $this->db->query( $data_sql );
        $result = $query->result_array();

        $data['list'] = $result;
        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 锁定
    public function lock ()
    {
        $id = intval($_GET['UID']);
        //update   book   status=ABS(status-1)
        $this->db->query("update zy_user set status=ABS(status-1) WHERE userId = $id");
        show_msg('操作成功！',$_SESSION['url_forward']);
    }

    // 删除
    public function delete ()
    {
        $id = $_GET['UID'];

        if ($id) {
            $this->db->query("delete from $this->table where userId=$id");
        } else {
            $ids = implode(",", $_POST['delete']);
            $this->db->query("delete from $this->table where userId in ($ids)");
        }
        //header("Location:{$_SESSION['url_forward']}");
        show_msg('删除成功！', $_SESSION['url_forward']);
    }
}
