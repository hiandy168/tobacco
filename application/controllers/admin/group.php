<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');    
   
include 'content.php';

class group extends Content
{
    function __construct ()
    {    
        parent::__construct();
            
        $this->control = 'group';
        $this->baseurl = 'index.php?d=admin&c=group';
        $this->table = 'zy_sys_group';
        $this->list_view = 'group_list';
        $this->add_view = 'group_add';
    }
    
    // 首页
    public function index ()
    {
        $keywords = trim($_REQUEST['keywords']);
        $searchsql = '1';      
        // 是否是查询
        if (empty($keywords)) {
            $config['base_url'] = $this->baseurl . "&m=index";
        } else {
        $searchsql .= " AND (GroupName like '%{$keywords}%')";
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
        $sql = "SELECT * FROM $this->table WHERE  $searchsql ORDER BY GroupID ASC limit $offset,$per_page";
        $query = $this->db->query($sql);
        $data['list'] = $query->result_array();

        $_SESSION['url_forward'] =  $config['base_url']. "&per_page=$offset";
        $this->load->view('admin/' . $this->list_view, $data);
    }

    // 添加
    public function add ()
    { 
        $this->load->view('admin/' . $this->add_view);
    }
    
    // 编辑
    public function edit ()
    {
        $id = intval($_GET['GroupID']);
        
        // 这条信息
        $query = $this->db->get_where($this->table, 'GroupID = ' . $id, 1);
        $value = $query->row_array();
        $data['value'] = $value;
        $data['GroupID'] = $id;
        
        $this->load->view('admin/' . $this->add_view, $data);
    }
	
    // 保存 添加和修改都是在这里
    public function save ()
    {
        $id = intval($_POST['GroupID']);
        $data = trims($_POST['value']);
        $data['UID'] = $this->session->userdata('UID');
        $data['UpdateTime'] = time();
        
        if ($id) { // 修改 ===========
            $this->db->where('GroupID', $id);
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
        $id = $_GET['GroupID'];
        
        
        if ($id) {
            $this->db->query("delete from $this->table where GroupID=$id");
        } else {
            $ids = implode(",", $_POST['delete']);
            $this->db->query("delete from $this->table where GroupID in ($ids)");
        }
        show_msg('删除成功！', $_SESSION['url_forward']);
    }

    //权限分配页面
    public function fenpei()
    {
        $data['GroupID'] = intval($_GET['GroupID']);
        $priList = $this->db->query("SELECT * FROM zy_sys_privicy")->result_array();//权限列表

        $newList =array();
        foreach ($priList as $k => $v) {
            if($v['ParentID'] == 0){
                $newList[] = $v;
                foreach($priList as $p => $r){
                    if($r['ParentID'] == $v['Pid']){
                        $newList[] = $r;
                    }
                }

            }
                
        }

        
        $data['priList'] = $newList;

        //获取已选权限id
        $selected = $this->db->query("SELECT Pids FROM {$this->table} WHERE GroupID = {$data['GroupID']}")->row_array();
        $data['sel_ids'] = explode(',', $selected['Pids']);

        $this->load->view('admin/fenpei',$data);
    }

    //保存权限
    public function prisave()
    {
        $GroupID = intval($_POST['GroupID']);
        $pri = $_POST['pri'];
        if(!empty($pri)){
            $Pids = implode(',', $pri);
        }else{
            $Pids = '';
        }
        
        $time = time();

        $this->db->query("update {$this->table} set Pids = '{$Pids}',UpdateTime = {$time} where GroupID = {$GroupID}");

            
        $data['msg'] = '添加成功！';
        $data['url'] = $this->baseurl . '&m=index';
        $this->load->view('admin/show_msg', $data);

    }
}
