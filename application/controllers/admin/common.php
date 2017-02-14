<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    // 后台公共页控制器 登陆页 by tangjian 
class Common extends CI_Controller
{
    
    // 框架首页
    public function index ()
    {     
        $uid = $this->session->userdata('UID');
        if (! $uid) {
           redirect('d=admin&c=common&m=login');
        }
        
        $list = get_cache('category');
        
        $this->load->library('tree');
        $this->tree->init($list);
        $string = '<li><a href=\"index.php?d=admin&c=news&m=index\" target=\"main\" >$spacer  ▪ $name </li>';
        
        $data['category'] = $this->tree->get_tree(0, $string);
        
        $data['mainurl'] = '';

        $this->load->view('admin/frame_index', $data);
    }
    
    // 默认搜索页
    public function main ()
    {
        $uid = $this->session->userdata('UID');
        if (! $uid) {
            redirect('d=admin&c=common&m=login');
        }
        $this->load->view('admin/main');
    }
    
    // 登陆页
    public function login ()
    {
        $uid = $this->session->userdata('UID');
        if ($uid) {
            redirect('d=admin&c=common');
        }
        
        $this->load->view('admin/login');
    }
    
    // 验证登陆
    public function check_login()
    {
        $username   = trim($_POST['username']);
        $password   = get_password($_POST['password']);
        //$checkcode  = trim($_POST['checkcode']); 
        $cookietime = intval($_POST['cookietime'])*3600*24;        
        
//         if ($checkcode != $_SESSION['checkcode']) {
//             show_msg('验证码不正确，请重新输入');
//         }
        
        $query = $this->db->get_where('zy_sys_manager',
                array(
                        'Username' => $username,
                        'Password' => $password
                ), 1);
        $user = $query->row_array();
        if ($user) {
            if($user['Status'] == 1){
                show_msg('您的账号已被锁定，请联系管理员', 'admin.php');
            }else{
                $this->db->query('update zy_sys_manager set LoginTime='.time().' where UID='.$user['UID']);
                unset($user['Password']);
               $this->session->sess_expiration = $cookietime;
                $this->session->set_userdata($user);        
                redirect('d=admin&c=common&m=index');
            }
            
        } else {
            show_msg('用户名或密码错误，请重新登录！', 'admin.php');
        }
    }
    
    // 退出
    public function login_out ()
    {
        $this->session->sess_destroy();
        redirect('d=admin&c=common&m=login');
    }
    
    // 基础设置
    public function website ()
    {
        $data[web] = get_cache('website');
        
        $this->load->view('admin/website', $data);
    }
    
    // 基础设置
    public function website_save ()
    {
        $data = array(
                'title' => trim($_POST['title']),
                'keywords' => trim($_POST['keywords']),
                'description' => trim($_POST['description']),
                'aboutus' => trim($_POST['aboutus'])
        );
        set_cache('website', $data);
        
        show_msg('设置成功！', 'index.php?d=admin&c=common&m=website');
    }
	
	function server(){
		$this->load->view('admin/server_view',array());
	}
	
	function query(){
		 $this->load->view('query_list',array());
	}
	
	function output_query(){
		$sql = $this->input->get_post('sql');
		$type = $this->input->get_post('type') ;
		if(empty($sql)) return;
		var_dump($sql);
		$query = $this->db->query($sql);
		if($type == 1){
        	$result = $query->result_array();
		}else if($type == 2){
			$result = $query;
			var_dump($result);
			exit;
		}		
			$data['result'] = $result;			
			$this->load->view('query_list',$data);
		
		
	}
	
	
}

/* End of file welcome.php */




