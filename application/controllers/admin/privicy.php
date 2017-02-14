<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 管理员  控制器 by tangjian 

include 'content.php';

class privicy extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'privicy';
        $this->baseurl = 'index.php?d=admin&c=privicy';
        $this->table = 'zy_sys_privicy';
        $this->list_view = 'privicy_list';
        $this->add_view = 'privicy_add';
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
        $searchsql .= " AND (Pname like '%{$keywords}%' OR Psign like '%{$keywords}%')";
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
        $sql = "SELECT * FROM $this->table WHERE  $searchsql ORDER BY Pid ASC limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();
        
        
    
        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add ()
    {
        
        $data['priList'] = $this->db->query("SELECT * FROM {$this->table} WHERE ParentID = 0")->result_array();

        


        $this->load->view('admin/' . $this->add_view, $data);
    }
    
    // 编辑
    public function edit ()
    {
        $id = intval($_GET['Pid']);
        
        // 这条信息
        $query = $this->db->get_where($this->table, 'Pid = ' . $id, 1);
        $value = $query->row_array();
        $data['value'] = $value;
        
        $data['Pid'] = $id;

        $data['priList'] = $this->db->query("SELECT * FROM {$this->table} WHERE ParentID = 0")->result_array();
        
        $this->load->view('admin/' . $this->add_view, $data);
    }


    


	
    // 保存 添加和修改都是在这里
    public function save ()
    {
        $id = intval($_POST['Pid']);
        $data = trims($_POST['value']);
        $data['UpdateTime'] = time();
        if ($id) { // 修改 ===========
            $this->db->where('Pid', $id);
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
        $id = $_GET['Pid'];

        if ($id) {
            $this->db->query("delete from $this->table where Pid=$id");
        } else {
            $ids = implode(",", $_POST['delete']);
            $this->db->query("delete from $this->table where Pid in ($ids)");
        }
        //header("Location:{$_SESSION['url_forward']}");
        show_msg('删除成功！', $_SESSION['url_forward']);
    }
}
