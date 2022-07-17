<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!is_login())redirect(site_url('login'));
		$this->load->model("User_model", "user");
		$this->load->model("Role_model", "role");
	}

	/*
		* Load Asset and Render View
		*
	*/

	public function users()
	{
		$data['title']			= MASTER . 'User';
		$data['subtitle']		= 'List User Aplikasi ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' User pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'master/useraplikasi/user', $data);
	}

	public function role()
	{
		$data['title']			= MASTER . 'Role';
		$data['subtitle']		= 'List Role Aplikasi ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Role pada aplikasi ' . APPLICATION_NAME . MULTI_SELECT_TABLE;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'master/useraplikasi/role', $data);
	}

	/*
		* 
		*
	*/

	public function getUsers()
	{
		$this->output->set_content_type('application/json');
		$result = $this->user->getAll();
		echo json_encode($result);
	}
	public function saveUser()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post("id");
		$nama = $this->input->post("nama");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$role = $this->input->post("role_id");
		$brand = $this->input->post("brand_id");
		if ($id) {
			if (!$this->form_validation->run("user_update")) {
				$errorMessage = $this->form_validation->error_array();
				$res = array(
					"status" 	=> FALSE,
					"message" 	=> VALIDATION_ERROR,
					"data"		=> $errorMessage
				);
				echo json_encode($res);
				return;
			}
			if ($password) {
				$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => 5]);
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
					"password"		=> $hashedPassword,
				);
			} else {
				$toSave = array(
					"id"			=> $id,
					"nama"			=> $nama,
				);
			}
		} else {
			if (!$this->form_validation->run("user_add")) {
				$errorMessage = $this->form_validation->error_array();
				$res = array(
					"status" 	=> FALSE,
					"message" 	=> VALIDATION_ERROR,
					"data"		=> $errorMessage
				);
				echo json_encode($res);
				return;
			}
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => 5]);
			$toSave = array(
				"id"			=> $id,
				"nama"			=> $nama,
				"username"		=> $username,
				"password"		=> $hashedPassword,
				"is_active"		=> 1,
				"is_deleted"	=> 0
			);
		}
		$query = $this->user->save($toSave, $role, $brand);
		$response = array();
		if ($query) {
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		} else {
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);
	}

	public function validationRole()
	{
		$role = $this->input->post('role_id');
		$brand = $this->input->post('brand_id');
		if ($role) {
			if (count($role) > 0) {
				if (in_array(ID_ROLE_BA, $role)) {
					if ($brand) {
						return true;
					} else {
						$this->form_validation->set_message('validationRole', 'Jika role adalah BA, pilih minimal 1 Brand.');
						return false;
					}
				} else {
					return true;
				}
			}
		} else {
			$this->form_validation->set_message('validationRole', 'Pilih minimal 1 {field}.');
			return false;
		}
	}

	public function isUsernameExist($key)
	{
		return !$this->user->isExist($key);
	}

	public function destroyUser()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->user->delete($id);
		echo json_encode($response);
	}
	public function setActiveUser()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->user->setActive($id);
		echo json_encode($response);
	}
	public function getSingleUser()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->user->getById($id);
		echo json_encode($result);
	}

	public function getRole()
	{
		$this->output->set_content_type('application/json');
		$result = $this->role->getAll();
		echo json_encode($result);
	}

	public function saveRole()
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

			$query = $this->role->save($toSave);
			$response = array();
			if ($query) {
				$response =  ["status" => true, "message" => SAVE_SUCCESS];
			} else {
				$response =  ["status" => false, "message" => SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}

	public function destroyRole()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->role->delete($id);
		echo json_encode($response);
	}

	public function setActiveRole()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->role->setActive($id);
		echo json_encode($response);
	}

	public function getSingleRole()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->role->getById($id);
		echo json_encode($result);
	}

	public function getRoleList()
	{
		$result = $this->role->getListRole();
		$this->output->set_content_type('application/json');
		echo json_encode($result);
	}
}
