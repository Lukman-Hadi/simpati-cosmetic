<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {
    public function __construct() {
        parent::__construct();
		// $this->load->model("Menu_model","menu");
	}
	
	public function penjualanPesanan(){
		$data['title']			= MASTER.'Merek';
		$data['subtitle']		= 'List Merek Produk '.APPLICATION_NAME;
		$data['description']	= TABLE_DESC.' Merek pada aplikasi '.APPLICATION_NAME.MULTI_SELECT_TABLE.FILTER_TABLE_DESC;
		$data['css_files'][]	= PATH_ASSETS.'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS.'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS.'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS.'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS.'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS.'vendor/select2/dist/js/i18n/id.js';
		$this->template->load('template/template','penjualan/penjualanpesanan',$data);
	}
}
