<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!is_login())redirect(site_url('login'));
		// $this->load->model("Menu_model","menu");
		$this->load->model("Stock_model", "stock");
		$this->load->model("Penjualan_model", "penjualan");
	}

	public function penjualan()
	{
		$data['title']			= "Tambah Penjualan Baru";
		$data['subtitle']		= 'Tambah Penjualan Baru ' . APPLICATION_NAME;
		$data['description']	= ADD_INSTRUCTION . ' Menambah Penjualan Pada ' . APPLICATION_NAME . ". " . FROM_INSTRUCTION;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/i18n/id.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		// $this->template->load('template/template','penjualan/penjualanpesanan',$data);
		$this->template->load('template/template', 'penjualan/penjualanpesanannew', $data);
	}

	public function listPenjualan()
	{
		$data['title']			= LIST_DESC . ' Penjualan';
		$data['subtitle']		= LIST_DESC . ' Penjualan ' . APPLICATION_NAME;
		$data['description']	= LIST_DESC . ' Penjualan ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'penjualan/listpenjualan', $data);
	}
	
	public function listPenjualanDetail()
	{
		$row = $this->penjualan->getPenjualanDetail($this->uri->segment(3));
		if(!$row){
			return redirect('/');
		}
		$data['data'] = $row;
		$data['title']			= 'Penambahan Stock ' . $data['data']["header"]->invoice_no;
		$data['subtitle']		= LIST_DESC . ' Penambahan Stock ' . APPLICATION_NAME;
		$data['description']	= LIST_DESC . ' Penambahan Stock ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$this->template->load('template/template', 'penjualan/penjualandetail', $data);
	}

	public function getListPenjualan()
	{
		$this->output->set_content_type('application/json');
		$result = $this->penjualan->getListPenjualan();
		echo json_encode($result);
	}

	public function getListStockSell()
	{
		$this->output->set_content_type('application/json');
		$result = $this->stock->getListStockSell();
		echo json_encode($result);
	}
	public function addPenjualan()
	{
		$this->output->set_content_type('application/json');
		
		$noInvoice = $this->input->post("invoice_no");
		$customerId = $this->input->post("customer_id");
		$grandTotal = $this->input->post("grand_total");
		$remarks = $this->input->post("remarks");
		$transDate = $this->input->post("trans_date");

		$totalAmount = $this->input->post("amount_total");
		$totalUnit = $this->input->post("amount_unit");
		$packId = $this->input->post("packing_id");
		$sellPrice = $this->input->post("sell_price");
		$subTotal = $this->input->post("sub_total");
		$variantId = $this->input->post("variant_id");
		$variantName = $this->input->post("variant_name");

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
			"invoice_no" => $noInvoice,
			"customer_id" => $customerId,
			"grand_total" => $grandTotal,
			"trans_date" => $transDate,
			"remarks"	=> $remarks
		);
		for ($i = 0; $i < count($variantId); $i++) {
			$detail[] = array(
				"variant_id" => $variantId[$i],
				"variant_name" => $variantName[$i],
				"qty_total" => $totalAmount[$i],
				"qty_pack" => $totalUnit[$i],
				"pack_id" => $packId[$i],
				"price"	=> str_replace([",", "."], "", $sellPrice[$i]),
				"subtotal" => $subTotal[$i]
			);
		}

		$query = $this->penjualan->savePenjualan($header, $detail);
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
		if (!$this->form_validation->run("penjualan")) {
			$errorMessage = $this->form_validation->error_array();
			return (object) ["error" => true, "errorType" => VALIDATION_ERROR, "errorMessage" => $errorMessage];
		}

		$totalAmount = $this->input->post("amount_total");
		$totalUnit = $this->input->post("amount_unit");
		$packId = $this->input->post("packing_id");
		$sellPrice = $this->input->post("sell_price");
		$subTotal = $this->input->post("sub_total");
		$variantId = $this->input->post("variant_id");
		$variantName = $this->input->post("variant_name");

		$errorStockFlag = false;
		$message = [];
		if ($variantId == '' || $totalAmount == '' || $totalUnit == '' || $packId == '' || $sellPrice == '' || $subTotal == '') {
			return (object) ["error" => true, "errorType" => "Tidak ada item yang dipilih", "errorMessage" => $message];
		}
		for ($i = 0; $i < count($variantId); $i++) {
			$isStockExist = $this->penjualan->cekStock($variantId[$i], $totalAmount[$i]);
			if (!$isStockExist) {
				$message[] = ["message" => "Item " . $variantName[$i] . " melebihi stok yang ada", "id" => $variantId[$i]];
				$errorStockFlag = true;
			}
		}
		if ($errorStockFlag) {
			return (object) ["error" => true, "errorType" => "validationStock", "errorMessage" => $message];
		}
		return (object) ["error" => false, "errorType" => "", "errorMessage" => ""];
	}

	public function validateStock($variantId, $totalAmount, $index)
	{
		$cekStock = $this->penjualan->cekStock($variantId, $totalAmount);
	}
}
