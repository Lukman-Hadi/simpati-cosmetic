<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// if (is_login()) redirect(site_url('home'));
		$this->load->model('Login_model', 'login_model');
	}

	public function index()
	{
		$this->load->view('auth/login');
	}

	public function doLogin()
	{
		$username = $this->input->post('username');
		$p = $this->input->post('password');
		$query = $this->login_model->getLogin($username)->row_array();
		if ($query) {
			if (password_verify($p, $query['password'])) {
				if ($query['is_active'] == 1) {
					unset($query['password']);
					unset($query['is_active']);
					$role = $this->login_model->getRole($query['id']);
					$query["role"] = $role;
					$query["menu"] = $this->login_model->getMenu($query["role"]);
					$this->session->set_userdata($query);
					// return redirect('/setting/menu');
					echo json_encode(array('message' => 'Login Success'));
				} else {
					echo json_encode(array('errorMsg' => 'Akun Tidak Aktif, Hubungi Administrator'));
				}
			} else {
				echo json_encode(array('errorMsg' => 'Password Salah'));
			}
		} else {
			echo json_encode(array('errorMsg' => 'User Tidak Ada'));
		}
	}
}
