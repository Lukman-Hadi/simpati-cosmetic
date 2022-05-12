<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Brand_model", "brand");
		$this->load->model("Kemasan_model", "kemasan");
	}

	public function brand()
	{
		$data['title']			= MASTER . 'Merek';
		$data['subtitle']		= 'List Merek Produk ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Merek pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'master/brand', $data);
	}

	public function packing()
	{
		$data['title']			= MASTER . 'Kemasan';
		$data['subtitle']		= 'List Kemasan Produk ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Kemasan pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'master/packing', $data);
	}

	public function getBrand()
	{
		$this->output->set_content_type('application/json');
		$result = $this->brand->getAll();
		echo json_encode($result);
	}

	public function getSingleBrand()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->brand->getById($id);
		echo json_encode($result);
	}

	public function saveBrand()
	{
		$this->output->set_content_type('application/json');
		if (!$this->form_validation->run("role")) {
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
			$description = $this->input->post("description");

			if ($id) {
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
					"description"	=> $description,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			} else {
				$toSave = array(
					"nama"			=> $nama,
					"description"	=> $description,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			}

			$query = $this->brand->save($toSave);
			$response = array();
			if ($query) {
				$response =  ["status" => true, "message" => SAVE_SUCCESS];
			} else {
				$response =  ["status" => false, "message" => SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}

	public function destroyBrand()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->brand->delete($id);
		echo json_encode($response);
	}

	public function setActiveBrand()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->brand->setActive($id);
		echo json_encode($response);
	}

	public function getBrandList()
	{
		$result = $this->brand->getListBrand();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}

	public function getPacking()
	{
		$this->output->set_content_type('application/json');
		$result = $this->kemasan->getAll();
		echo json_encode($result);
	}

	public function getPackingList()
	{
		$result = $this->kemasan->getListPacking();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}

	public function savePacking()
	{
		$this->output->set_content_type('application/json');
		$this->output->set_content_type('application/json');
		$id = $this->input->post("id");
		$nama = $this->input->post("nama");
		$unit = $this->input->post("unit");
		$amount = $this->input->post("amount");
		$parent_id = $this->input->post("parent_id");
		$description = $this->input->post("description");

		if ($id) {
			if (!$this->form_validation->run("packing_update")) {
				$errorMessage = $this->form_validation->error_array();
				$res = array(
					"status" 	=> FALSE,
					"message" 	=> VALIDATION_ERROR,
					"data"		=> $errorMessage
				);
				echo json_encode($res);
				return;
			} else {
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
					"description"	=> $description,
					"amount"		=> $amount,
					"parent_id"		=> $parent_id,
					"is_deleted"	=> 0
				);
			}
		} else {
			if (!$this->form_validation->run("packing_add")) {
				$errorMessage = $this->form_validation->error_array();
				$res = array(
					"status" 	=> FALSE,
					"message" 	=> VALIDATION_ERROR,
					"data"		=> $errorMessage
				);
				echo json_encode($res);
				return;
			} else {
				$toSave = array(
					"nama"			=> $nama,
					"description"	=> $description,
					"unit"			=> $unit,
					"amount"		=> $amount,
					"parent_id"		=> $parent_id,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			}
		}
		$query = $this->kemasan->save($toSave);
		$response = array();
		if ($query) {
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		} else {
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);
	}
	public function isPackingExist($key)
	{
		return !$this->kemasan->isExist($key);
	}

	public function setActivePacking()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->kemasan->setActive($id);
		echo json_encode($response);
	}

	public function getSinglePacking()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->kemasan->getById($id);
		echo json_encode($result);
	}
	
	public function destroyPacking(){
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->kemasan->delete($id);
		echo json_encode($response);
	}
}
