<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccessControl extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Menu_model", "menu");
		$this->load->model("Acl_model", "acl");
	}
	public function menuAccess()
	{
		$data['title']			= MASTER . 'Role';
		$data['subtitle']		= 'List Role Aplikasi ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'setting/hakakses/listrole', $data);
	}
	public function menuControl()
	{
		$this->load->model("Role_model", "role");
		$data['title'] = 'Geser Untuk Memberikan Akses';
		$data['subtitle']		= 'List Role Aplikasi ' . APPLICATION_NAME;
		// $data['level'] = $this->db->get_where('tbl_levels', array('id_jabatan' => $this->uri->segment(3)))->row_array();
		// $data['menus'] = $this->db->get_where('tbl_menus', array('id_main !=' => null))->result();
		// $this->template->load('template', 'master/akses', $data);
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['roleId'] = $this->uri->segment(3);
		$role = $this->role->isExist($data['roleId']);
		if ($data['roleId'] && $role) {
			$data['menus'] = $this->menu->menuControlList($data['roleId']);
			$this->template->load('template/template', 'setting/hakakses/menucontrol', $data);
		} else {
			redirect('/');
		}
	}

	public function saveAccessMenu()
	{
		$this->output->set_content_type('application/json');
		if(!$this->input->post('role_id')){
			$res = array(
				"status" 	=> FALSE,
				"message" 	=> 'Gagal menyimpan data, Refresh kembali halaman ini',
			);
			echo json_encode($res);
			return;
		}
		$menuId = $this->input->post('menu_id');
		$roleId = $this->input->post('role_id');
		$toSave = array();
		foreach ($menuId as $menu) {
			$toSave[] = array(
				"role_id" => $roleId,
				"menu_id" => $menu,
			);
		}
		$query = $this->acl->save($roleId,$toSave);
		if($query){
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		}else{
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);	
		return;
	}

	public function test()
	{
		$this->output->set_content_type('application/json');
		$menus = buildTree($this->menu->getAllMenuControl());
		echo json_encode($this->menu->menuControlList(1));
	}
}
