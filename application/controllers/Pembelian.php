<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!is_login())redirect(site_url('login'));
		$this->load->model("Stock_model", "stock");
		$this->load->model("Barang_model", "barang");
		$this->load->model("Pembelian_model", "pembelian");
	}

	public function addPembelian()
	{
		$data['title']			= ADD_DESC . ' Pembelian';
		$data['subtitle']		= ' Pembelian ' . APPLICATION_NAME;
		$data['description']	= ADD_INSTRUCTION . ' Pembelian ' . APPLICATION_NAME . FROM_INSTRUCTION;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'pembelian/addpembelian', $data);
	}

	public function savePembelian()
	{
		$this->output->set_content_type('application/json');
		$invoiceNo = $this->input->post("invoice_no");
		$supplierId = $this->input->post("supplier_id");
		$remarks = $this->input->post("remarks");
		$transDate = $this->input->post("trans_date");

		$variantId = $this->input->post("variant_id");
		$variantName = $this->input->post("variant_name");
		$packingId = $this->input->post("packing_id");
		$expiryDate = $this->input->post("expiry_date");
		$amount = $this->input->post("amount");
		$buyPrice = $this->input->post("buy_price");

		$validate = $this->runValidation();
		if ($validate->error) {
			$res = array(
				"status" 	=> FALSE,
				"message" 	=> $validate->errorType,
				"data"		=> $validate->errorMessage
			);
			echo json_encode($res);
			return;
		}
		$header = array(
			"invoice_no" => $invoiceNo,
			"supplier_id" => $supplierId,
			"remarks"	=> $remarks,
			"trans_date" => $transDate
		);
		$detail = [];
		for ($i = 0; $i < count($variantId); $i++) {
			$detail[] = array(
				"variant_id" => $variantId[$i],
				"variant_name" => $variantName[$i],
				"packing_id" => $packingId[$i],
				"expired_date" => $expiryDate[$i],
				"total_qty" => $amount[$i] * $this->stock->getAmountByPack($packingId[$i], $variantId[$i]),
				"amount" => $amount[$i],
				"buy_price" => intval(str_replace(",", "", $buyPrice[$i]))
			);
		}
		$toSave = [];
		for ($i = 0; $i < count($variantId); $i++) {
			$toSave[] = array(
				"product_variant_id" => $variantId[$i],
				"total_stock" => $amount[$i] * $this->stock->getAmountByPack($packingId[$i], $variantId[$i]),
				"buy_price" => intval(str_replace(",", "", $buyPrice[$i])),
				"expired_date" => $expiryDate[$i] == '' ? '0000-00-00' : $expiryDate[$i],
				"type"	=> "plus"
			);
		}

		$query = $this->pembelian->savePembelian($toSave, $header, $detail);
		// $this->stock->saveStock($toSave, $header, $detail);
		$response = array();
		if ($query) {
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		} else {
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);
		return;
	}

	public function runValidation()
	{
		if (!$this->form_validation->run("savePembelian")) {
			$errorMessage = $this->form_validation->error_array();
			return (object) ["error" => true, "errorType" => VALIDATION_ERROR, "errorMessage" => $errorMessage];
		}

		$variantId = $this->input->post("variant_id");
		$packingId = $this->input->post("packing_id");
		$amount = $this->input->post("amount");
		$buyPrice = $this->input->post("buy_price");

		$errorNul = false;
		$message = [];
		if ($variantId == '' || $packingId == '' || $amount == '' || $buyPrice == '') {
			return (object) ["error" => true, "errorType" => "Tidak ada item yang dipilih", "errorMessage" => $message];
		}
		for ($i = 0; $i < count($variantId); $i++) {
			if (($amount[$i] == '' || $amount[$i] < 1) || ($buyPrice[$i] == '' || intval(str_replace(",", "", $buyPrice[$i])) < 1)) {
				$message[] = ["message" => "Jumlah Item atau harga item tidak boleh 0 atau kosong melebihi stok yang ada", "id" => $variantId[$i]];
				$errorNul = true;
			}
		}
		if ($errorNul) {
			return (object) ["error" => true, "errorType" => "validationNull", "errorMessage" => $message];
		}
		return (object) ["error" => false, "errorType" => "", "errorMessage" => ""];
	}

	public function listPembelian()
	{
		$data['title']			= LIST_DESC . ' Pembelian';
		$data['subtitle']		= LIST_DESC . ' Pembelian ' . APPLICATION_NAME;
		$data['description']	= LIST_DESC . ' Pembelian ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'pembelian/listpembelian', $data);
	}

	public function listPembelianDetail()
	{
		$row = $this->pembelian->getPembelianDetail($this->uri->segment(3));
		if (!$row) {
			return redirect('/');
		}
		$data['data'] = $row;
		$data['title']			= 'Pembelian ' . $data['data']["header"]->invoice_no;
		$data['subtitle']		= LIST_DESC . ' Pembelian ' . APPLICATION_NAME;
		$data['description']	= LIST_DESC . ' Pembelian ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'pembelian/pembeliandetail', $data);
	}
	
	public function getListPembelian()
	{
		$this->output->set_content_type('application/json');
		$result = $this->pembelian->getListPembelian();
		echo json_encode($result);
	}
}
