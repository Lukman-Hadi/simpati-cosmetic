<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Menu_model", "menu");
	}

	public function index()
	{
		$data['title']  = 'Dashboard';
		$data['css_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.css';
		$data['js_files'][] = base_url() . 'assets/admin/vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][] = base_url() . 'assets/admin/js/dashboard.js';
		$this->template->load('template/template', 'dashboard/dashboard', $data);
	}

	/*
		* Load Asset and Render View
		*
	*/

	public function menu()
	{
		$data['title']			= MASTER . 'Menu';
		$data['subtitle']		= 'List Menu Aplikasi ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Menu pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'master/menu', $data);
	}

	public function getMenu()
	{
		$this->output->set_content_type('application/json');
		$result = $this->menu->getAll();
		echo json_encode($result);
	}

	public function getMenuListTest()
	{
		$result = $this->menu->getListMenu();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}

	public function getSingleMenu()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->menu->getMenuById($id);
		echo json_encode($result);
	}

	public function saveMenu()
	{
		$this->output->set_content_type('application/json');
		if (!$this->form_validation->run("menu")) {
			$errorMessage = $this->form_validation->error_array();
			$res = array(
				"status" 	=> FALSE,
				"message" 	=> VALIDATION_ERROR,
				"data"		=> $errorMessage
			);
			echo json_encode($res);
			return;
		} else {
			$id = $this->input->post("id");
			$nama = $this->input->post("nama");
			$link = $this->input->post("link");
			$icon = $this->input->post("icon");
			$ordinal = $this->input->post("ordinal");
			$parentId = $this->input->post("parent_id");

			if ($id) {
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
					"link"			=> $link,
					"icon"			=> $icon,
					"ordinal"		=> $ordinal,
					"parent_id"		=> $parentId,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			} else {
				$toSave = array(
					"nama"			=> $nama,
					"link"			=> $link,
					"icon"			=> $icon,
					"ordinal"		=> $ordinal,
					"parent_id"		=> $parentId,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			}

			$query = $this->menu->saveMenu($toSave);
			$response = array();
			if ($query) {
				$response =  ["status" => true, "message" => SAVE_SUCCESS];
			} else {
				$response =  ["status" => false, "message" => SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}

	function isMenuExist($key)
	{
		return $this->menu->isExist($key);
	}

	public function destroyMenu()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->menu->deleteMenu($id);
		echo json_encode($response);
	}

	public function activeNonActiveMenu()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->menu->setActive($id);
		echo json_encode($response);
	}

	public function accessControl()
	{
		$data['title']			= MASTER . 'Menu';
		$data['subtitle']		= 'List Menu Aplikasi ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Menu pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$this->template->load('template/template', 'test', $data);
	}

	public function test()
	{
		// var_dump($this->menu->getMenus()[0]->title);
		$this->output->set_content_type('application/json');
		// buildTree($this->menu->getMenus());
		// echo json_encode(buildTree($this->menu->getMenus()));
		// echo buildMenu();
		// echo json_encode(explode("/",uri_string()));
		$results = $this->menu->testAlfin('2022-03-01');
		$hasil = [];
		foreach ($results as $result) {
			$hasil[] = array(
				"id" => $result->id,
				"kode_mesin" => $result->kode_mesin,
				"nama_mesin" => $result->nama_mesin,
				"jumlah" => $result->jumlah,
				"nama" => 'alfin'
			);
		}
		echo json_encode($hasil);
	}
}
