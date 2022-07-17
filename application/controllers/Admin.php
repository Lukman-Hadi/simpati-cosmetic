<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
		if(!is_login())redirect(site_url('login'));
		$this->load->model("Menu_model","menu");
	}
	
	public function index(){
        $data['title']  = 'Dashboard';
        $data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
        $data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
        $data['js_files'][] = base_url() . 'assets/admin/js/dashboard.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
        $this->template->load('template/template','dashboard/dashboard',$data);
	}
	
	public function menu(){
		$data['title']			= MASTER.'Menu';
		$data['subtitle']		= 'List Menu Aplikasi '.APPLICATION_NAME;
		$data['description']	= TABLE_DESC.' Menu pada aplikasi '.APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS.'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS.'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS.'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS.'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS.'vendor/select2/dist/js/select2.min.js';
		$this->template->load('template/template','master/menu',$data);
	}
	
	public function getMenu(){
		$result = $this->menu->getAll();
		echo json_encode($result);
	}
	
	public function getMenuList(){
		$result = $this->menu->getListMenu();
		echo json_encode($result);
	}
	
	public function getMenuById(){
		
	}
	
	public function saveMenu(){
		if(!$this->form_validation->run("menu")){
			$errorMessage = $this->form_validation->error_array();
			$res = array(
				"status" 	=> FALSE,
				"message" 	=> VALIDATION_ERROR,
				"data"		=> $errorMessage
			);
			echo json_encode($res);
			return;
		}else{
			$nama = $this->input->post("nama");
			$link = $this->input->post("link");
			$icon = $this->input->post("icon");
			$ordinal = $this->input->post("ordinal");
			$parentId = $this->input->post("parent_id");
			
			$toSave = array(
				"nama"			=> $nama, 
				"link"			=> $link, 
				"icon"			=> $icon, 
				"ordinal"		=> $ordinal, 
				"parent_id"		=> $parentId,
				"is_active"		=> 1,
				"is_deleted"	=> 0 
			);
			
			$query = $this->menu->saveMenu($toSave);
			$response = array();
			if($query){
				$response =  ["status"=>true,"message"=>SAVE_SUCCESS];
			}else{
				$response =  ["status"=>false,"message"=>SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}
	
	function isMenuExist($key){
		return $this->menu->isExist($key);
	}
	
	public function deleteMenu(){
		
	}
	
	public function test(){
		$table = 'MST_MENU';
		$data = ["id"=>1,"nama"=>"TESTING DONG","link"=>"testtttt","icon"=>"icon","parent_id"=>0,"ordinal"=>1];
		$data = $this->menu->saveMenu($data);
		var_dump($data);
	}
	
	function logout(){
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login','Anda sudah berhasil keluar dari aplikasi');
        $this->load->view('auth/login', 'refresh');
    }
	
}
