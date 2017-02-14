<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class admin extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'admin';
        $this->baseurl = 'index.php?d=admin&c=admin';
        $this->table = 'zy_sys_manager';
        $this->list_view = 'admin_list';
        $this->add_view = 'admin_add';
    }
    
    // 首页
    public function index ()
    {
        $keywords = trim($_REQUEST['keywords']);
        $searchsql = '1';
        //         if ($catid) {
        //             $searchsql .= " AND catid=$catid ";
        //         }
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index";
        } else {
        $searchsql .= " AND (Username like '%{$keywords}%' OR TrueName like '%{$keywords}%' OR Tel like '%{$keywords}%')";
        $config['base_url'] = $this->baseurl ."&m=index&keywords=" . rawurlencode($keywords);
        }
        
        $data['list'] = array();
        $query = $this->db->query(
        "SELECT COUNT(*) AS num FROM $this->table WHERE  $searchsql");
        $count = $query->row_array();
        $data['count'] = $count['num'];
        $this->load->library('pagination');
        
        $config['total_rows'] = $count['num'];
        $config['per_page'] = 20;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $offset = $_GET['per_page'] ? intval($_GET['per_page']) : 0;
        $per_page = $config['per_page'];
        $sql = "SELECT * FROM $this->table WHERE  $searchsql ORDER BY UID ASC limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();
        
        $data['grouplist'] = getGroupList();
    
        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";

        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add ()
    {
        $data['grouplist'] = getGroupList();
        $this->load->view('admin/' . $this->add_view, $data);
    }
    
    // 编辑
    public function edit ()
    {
        $id = intval($_GET['UID']);
        
        // 这条信息
        $query = $this->db->get_where($this->table, 'UID = ' . $id, 1);
        $value = $query->row_array();
        $data['value'] = $value;
        $data['UID'] = $id;
        $data['grouplist'] = getGroupList();
        $this->load->view('admin/' . $this->add_view, $data);
    }

    // 锁定
    public function lock ()
    {
        $id = intval($_GET['UID']);
        //update   book   status=ABS(status-1)
        $this->db->query("update zy_sys_manager set status=ABS(Status-1) WHERE UID = $id");
        show_msg('操作成功！',$_SESSION['url_forward']);
    }
	
    // 保存 添加和修改都是在这里
    public function save ()
    {
        $id = intval($_POST['UID']);
        $data = trims($_POST['value']);
        
        if($data['Password']) {
            $data['Password'] = get_password($data['Password']);
        } else {
            unset ($data['Password']);
        }
        if ($id) { // 修改 ===========
            $this->db->where('UID', $id);
            $query = $this->db->update($this->table, $data);
            show_msg('修改成功！',$_SESSION['url_forward']);
        } else { // ===========添加 ===========
            
            $query = $this->db->insert($this->table, $data);
            show_msg('添加成功！',$_SESSION['url_forward']);
        }
    }
    
    // 删除
    public function delete ()
    {
        $id = $_GET['UID'];

        if ($id) {
            $this->db->query("delete from $this->table where UID=$id");
        } else {
            $ids = implode(",", $_POST['delete']);
            $this->db->query("delete from $this->table where UID in ($ids)");
        }
        //header("Location:{$_SESSION['url_forward']}");
        show_msg('删除成功！', $_SESSION['url_forward']);
    }
}
