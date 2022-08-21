<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!is_login()) redirect(site_url('login'));
		$this->load->model("Report_model", "report");
	}
	public function keluarMasuk()
	{
		$data['title']			= REPORT . ' Keluar Masuk Barang';
		$data['description']	= 'Silahkan sesuaikan filter';
		$data['subtitle']		= REPORT . ' Keluar Masuk Barang';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/daterangepicker/daterangepicker-bs3.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/daterangepicker/daterangepicker.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'report/keluarmasuk', $data);
	}

	public function printKeluarMasuk()
	{
		// $this->output->set_content_type('application/json');
		$brand = $this->input->post('brand_id');
		$startDate = $this->input->post('start_date');
		$endDate = $this->input->post('end_date');
		$brand = implode(",", $brand);

		$dataRow = $this->report->keluarMasuk($brand, $startDate, $endDate);
		$data['title']			= REPORT . ' Keluar Masuk Barang';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/daterangepicker/daterangepicker-bs3.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/daterangepicker/daterangepicker.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['data'] = $dataRow;
		$data['date']['startDate'] = $startDate;
		$data['date']['endDate'] = $endDate;
		$this->template->load('template/templateprint', 'print/keluarmasuk', $data);
	}

	public function listBarang()
	{
		$data['title']			= REPORT . ' Daftar Barang';
		$data['description']	= 'Silahkan sesuaikan filter';
		$data['subtitle']		= REPORT . ' Daftar Barang';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/daterangepicker/daterangepicker-bs3.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/daterangepicker/daterangepicker.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'report/daftarbarang', $data);
	}

	public function printDaftarBarang()
	{
		// $this->output->set_content_type('application/json');
		$brand = $this->input->post('brand_id');
		$brand = implode(",", $brand);

		$dataRow = $this->report->daftarBarang($brand);
		$data['title']			= REPORT . ' Keluar Masuk Barang';
		$data['css_files'][]	= '';
		$data['js_files'][]		= '';
		$data['data'] = $dataRow;
		$this->template->load('template/templateprint', 'print/daftarBarang', $data);
	}
	
	public function listStock()
	{
		$data['title']			= REPORT . ' Daftar Barang';
		$data['description']	= 'Silahkan sesuaikan filter';
		$data['subtitle']		= REPORT . ' Daftar Barang';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'report/daftarstockvariant', $data);
	}

	public function printDaftarStockVariant()
	{
		// $this->output->set_content_type('application/json');
		$brand = $this->input->post('brand_id');
		$brand = implode(",", $brand);

		$dataRow = $this->report->listStockPerVariant($brand);
		$data['title']			= REPORT . ' Daftar Stock per variant';
		$data['css_files'][]	= '';
		$data['js_files'][]		= '';
		$data['data'] = $dataRow;
		$this->template->load('template/templateprint', 'print/daftarstockvariant', $data);
	}
	
	public function listStockPerProduct()
	{
		$data['title']			= REPORT . ' Daftar Barang';
		$data['description']	= 'Silahkan sesuaikan filter';
		$data['subtitle']		= REPORT . ' Daftar Barang';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'report/daftarstockall', $data);
	}

	public function printDaftarStockAll()
	{
		// $this->output->set_content_type('application/json');
		$brand = $this->input->post('brand_id');
		$brand = implode(",", $brand);

		$dataRow = $this->report->listStockPerProduct($brand);
		$data['title']			= REPORT . ' Daftar Stock per variant';
		$data['css_files'][]	= '';
		$data['js_files'][]		= '';
		$data['data'] = $dataRow;
		$this->template->load('template/templateprint', 'print/daftarstockall', $data);
	}
}
