<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->model("Menu_model","menu");
	}

	public function listBarang()
	{
		$data['title']			= LIST_DESC . ' Barang';
		$data['subtitle']		= 'Daftar Barang Aplikasi ' . APPLICATION_NAME;
		$data['description']	= TABLE_DESC . ' Barang pada aplikasi ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'barang/listbarang', $data);
	}

	public function add()
	{
		$data['title']			= ADD_DESC . ' Barang Baru';
		$data['subtitle']		= 'Data Barang ' . APPLICATION_NAME;
		$data['description']	= ADD_INSTRUCTION . ' Daftar Barang Aplikasi ' . APPLICATION_NAME . FROM_INSTRUCTION;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'barang/add', $data);
	}
}
