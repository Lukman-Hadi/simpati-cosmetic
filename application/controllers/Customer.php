<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!is_login())redirect(site_url('login'));
		$this->load->model("Customer_model", "customer");
	}

	/*
		* Load Asset and Render View
		*
	*/

	public function customerGroup()
	{
		$data['title']			= MASTER . 'Grup Customer';
		$data['subtitle']		= 'List Grup Customer ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Grup Customer pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'customer/customergroup', $data);
	}

	public function getCustomerGroup()
	{
		$this->output->set_content_type('application/json');
		$result = $this->customer->getAllGroup();
		echo json_encode($result);
	}

	public function getSingleCustomerGroup()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->customer->getGroupById($id);
		echo json_encode($result);
	}

	public function saveCustomerGroup()
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
			$isDistributor = $this->input->post("is_distributor")?:0;

			if ($id) {
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
					"description"	=> $description,
					"is_distributor"	=> $isDistributor,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			} else {
				$toSave = array(
					"nama"			=> $nama,
					"description"	=> $description,
					"is_distributor"	=> $isDistributor,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			}

			$query = $this->customer->saveGroup($toSave);
			$response = array();
			if ($query) {
				$response =  ["status" => true, "message" => SAVE_SUCCESS];
			} else {
				$response =  ["status" => false, "message" => SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}

	public function destroyCustomerGroup()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->customer->deleteGroup($id);
		echo json_encode($response);
	}

	public function setActiveCustomerGroup()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->customer->setActiveGroup($id);
		echo json_encode($response);
	}

	public function getBrandList()
	{
		$result = $this->brand->getListBrand();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}

	public function getCustomerGroupListSelect()
	{
		$result = $this->customer->getListCustomerGroupSelect();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}

	public function customer()
	{
		$data['title']			= MASTER . 'Customer';
		$data['subtitle']		= 'List Customer ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Customer pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'customer/customer', $data);
	}

	public function getCustomer()
	{
		$this->output->set_content_type('application/json');
		$result = $this->customer->getAll();
		echo json_encode($result);
	}

	public function getSingleCustomer()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->customer->getById($id);
		echo json_encode($result);
	}

	public function saveCustomer()
	{
		$this->output->set_content_type('application/json');
		if (!$this->form_validation->run("customer")) {
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
			$customerGroupId = $this->input->post("group_id");

			if ($id) {
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
					"description"	=> $description,
					"customer_group_id"	=> $customerGroupId,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			} else {
				$toSave = array(
					"nama"			=> $nama,
					"description"	=> $description,
					"customer_group_id"	=> $customerGroupId,
					"is_active"		=> 1,
					"is_deleted"	=> 0
				);
			}

			$query = $this->customer->save($toSave);
			$response = array();
			if ($query) {
				$response =  ["status" => true, "message" => SAVE_SUCCESS];
			} else {
				$response =  ["status" => false, "message" => SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}

	public function destroyCustomer()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->customer->delete($id);
		echo json_encode($response);
	}

	public function setActiveCustomer()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->customer->setActive($id);
		echo json_encode($response);
	}
	
	public function getCustomerListSelect()
	{
		$result = $this->customer->getListCustomerSelect();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}
}
