<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Reader;
class Stock extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!is_login()) redirect(site_url('login'));
		$this->load->model("Stock_model", "stock");
		$this->load->model("Barang_model", "barang");
	}

	public function stockAdjustment()
	{
		$data['title']			= ADD_DESC . ' Penyesuaian Stock';
		$data['subtitle']		= 'Penyesuaian Stock ' . APPLICATION_NAME;
		$data['description']	= ADD_INSTRUCTION . ' Penyesuaian Stock ' . APPLICATION_NAME . FROM_INSTRUCTION;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'stock/adjustment', $data);
	}

	public function addStock()
	{
		$data['title']			= ADD_DESC . ' Stock';
		$data['subtitle']		= ' Stock ' . APPLICATION_NAME;
		$data['description']	= ADD_INSTRUCTION . ' Stock ' . APPLICATION_NAME . FROM_INSTRUCTION;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'stock/add', $data);
	}

	public function listStock()
	{
		$data['title']			= LIST_DESC . ' Stock';
		$data['subtitle']		= LIST_DESC . ' Stock ' . APPLICATION_NAME;
		$data['description']	= 'Stock ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'stock/liststock', $data);
	}

	public function listAdjustmentAddStock()
	{
		$data['title']			= LIST_DESC . ' Penambahan Stock';
		$data['subtitle']		= LIST_DESC . ' Penambahan Stock ' . APPLICATION_NAME;
		$data['description']	= LIST_DESC . ' Penambahan Stock ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'stock/listadjustmentaddstock', $data);
	}

	public function listAdjustmentAddDetail()
	{
		$row = $this->stock->getAdjustmentAddDetail($this->uri->segment(3));
		if (!$row) {
			return redirect('/');
		}
		$data['data'] = $row;
		$data['title']			= 'Penambahan Stock ' . $data['data']["header"]->ref_no;
		$data['subtitle']		= LIST_DESC . ' Penambahan Stock ' . APPLICATION_NAME;
		$data['description']	= LIST_DESC . ' Penambahan Stock ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'stock/listadjustmentadddetail', $data);
	}

	public function getListStock()
	{
		$this->output->set_content_type('application/json');
		$result = $this->stock->getListStock();
		echo json_encode($result);
	}

	public function getListStockForAdjustment()
	{
		$this->output->set_content_type('application/json');
		$result = $this->stock->getListStockForAdjustment();
		echo json_encode($result);
	}

	public function getListProducts()
	{
		$this->output->set_content_type('application/json');
		$result = $this->barang->getListProductVariant();
		echo json_encode($result);
	}

	public function getListProductsSell()
	{
		$this->output->set_content_type('application/json');
		$result = $this->stock->getListProductsSell();
		echo json_encode($result);
	}

	public function getExpiryDate()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$isExist = $this->stock->isStockExist($id);
		$res = [];
		if ($isExist) {
			$exp = $this->stock->getExpiryStockById($id);
			$res = array(
				"status" 	=> TRUE,
				"message" 	=> SUCCESS_GET_DATA,
				"data"		=> $exp
			);
		} else {
			$exp = [];
			$res = array(
				"status" 	=> FALSE,
				"message" 	=> STOCK_NOT_FOUND,
				"data"		=> $exp
			);
		}
		echo json_encode($res);
	}

	public function getProducts()
	{
		$this->output->set_content_type('application/json');
		$result = $this->barang->getAll();
		echo json_encode($result);
	}

	public function saveStockFromUpload()
	{
		$this->output->set_content_type('application/json');
		if (!empty($_FILES['file']['name'])) {
			$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			if ($extension == 'csv') {
				$reader = new Reader\Csv;
			} else if ($extension == 'xlsx') {
				$reader = new Reader\Xlsx;
			} else if ($extension == 'xls') {
				$reader =  new Reader\Xls;
			} else {
				$response =  ["status" => false, "message" => 'file harus Xls / Xlsx'];
				echo json_encode($response);
				return;
			}
			$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
			$allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			$arrayCount = count($allDataInSheet);
			$flag = 0;
			$createArray = array('id', 'kode_barang', 'nama_barang', 'merk', 'qty(pcs)', 'harga_beli', 'tanggal_expired');
			$makeArray = array('id' => 'id', 'kode_barang' => 'kode_barang', 'nama_barang' => 'nama_barang', 'merk' => 'merk', 'qty(pcs)' => 'qty(pcs)', 'harga_beli' => 'harga_beli', 'tanggal_expired' => 'tanggal_expired');
			$SheetDataKey = array();
			foreach ($allDataInSheet as $dataInSheet) {
				foreach ($dataInSheet as $key => $value) {
					if (in_array(trim($value), $createArray)) {
						$value = preg_replace('/\s+/', '', $value);
						$SheetDataKey[trim($value)] = $key;
					}
				}
			}
			$dataDiff = array_diff_key($makeArray, $SheetDataKey);
			if (empty($dataDiff)) {
				$flag = 1;
			}
			// match excel sheet column
			if ($flag == 1) {
				for ($i = 2; $i <= $arrayCount; $i++) {
					$idVariant = $SheetDataKey['id'];
					$kodeVariant = $SheetDataKey['kode_barang'];
					$namaVariant = $SheetDataKey['nama_barang'];
					$brand = $SheetDataKey['merk'];
					$qty = $SheetDataKey['qty(pcs)'];
					$hargaBeli = $SheetDataKey['harga_beli'];
					$tanggalExpired = $SheetDataKey['tanggal_expired'];
					
					$idVariant = filter_var(trim($allDataInSheet[$i][$idVariant]), FILTER_SANITIZE_NUMBER_INT);
					$kodeVariant = filter_var(trim($allDataInSheet[$i][$kodeVariant]), FILTER_SANITIZE_STRING);
					$namaVariant = filter_var(trim($allDataInSheet[$i][$namaVariant]), FILTER_SANITIZE_STRING);
					$brand = filter_var(trim($allDataInSheet[$i][$brand]), FILTER_SANITIZE_STRING);
					$qty = filter_var(trim($allDataInSheet[$i][$qty]), FILTER_SANITIZE_NUMBER_INT);
					$hargaBeli = filter_var(trim($allDataInSheet[$i][$hargaBeli]), FILTER_SANITIZE_NUMBER_INT);
					$tanggalExpired = trim($allDataInSheet[$i][$tanggalExpired]);
					if(empty($qty)){
						continue;
					}
					$fetchData[] = array('id_variant' => $idVariant, 'kode_variant' => $kodeVariant, 'nama_variant' => $namaVariant, 'qty' => $qty, 'harga_beli' => $hargaBeli, 'tanggalExpired' => $tanggalExpired);
				}
			}
			$refNo = $this->input->post("ref_no");
			$methodId = $this->input->post("method_id");
			$remarks = $this->input->post("remarks");
			$type = "Penambahan";
			$header = array(
				"ref_no" => $refNo,
				"method" => $methodId,
				"type"	=> $type,
				"remarks"	=> $remarks
			);
			$detail = [];
			$toSave = [];
			foreach ($fetchData as $key => $value) {
				$detail[] = array(
					"product_variant_id" => $value['id_variant'],
					"packing_id" => 5,
					"expired_date" => $value['tanggalExpired'],
					"total_stock" => $value['qty'],
					"buy_price" => intval(str_replace(",", "", $value['harga_beli'])),
					"type"	=> "plus"
				);
				$toSave[] = array(
					"product_variant_id" => $value['id_variant'],
					"total_stock" => $value['qty'] * $this->stock->getAmountByPack(5, $value['id_variant']),
					"buy_price" => intval(str_replace(",", "", $value['harga_beli'])),
					"expired_date" => $value['tanggalExpired'] == '' ? '0000-00-00' : date_create_from_format("d/m/Y", $value['tanggalExpired'])->format("Y-m-d"),
					"type"	=> "plus"
				);
			}
			$query = $this->stock->saveStock($toSave, $header, $detail);
			$response = array();
			if ($query) {
				$response =  ["status" => true, "message" => SAVE_SUCCESS];
			} else {
				$response =  ["status" => false, "message" => SAVE_FAILED];
			}
			echo json_encode($response);
		}
	}

	public function saveStock()
	{
		$this->output->set_content_type('application/json');
		//TODO VALIDATION, IS BUY PRICE AVAIL ON STOCK ADJUSTMENT? IF YES THEN ADD TO UI AND WRITE LOGIC HERE TO DIVIDE UNIT PRICE BY PACK
		$refNo = $this->input->post("ref_no");
		$methodId = $this->input->post("method_id");
		$remarks = $this->input->post("remarks");
		$type = "Penambahan";

		$adjustmentMethod = $this->input->post("method_id");
		$variantId = $this->input->post("variant_id");
		$packingId = $this->input->post("packing_id");
		$expiryDate = $this->input->post("expiry_date");
		$amount = $this->input->post("amount");
		$buyPrice = $this->input->post("buy_price");
		$stockId = $this->input->post("stockId");

		//TODO VALIDATION;
		$header = array(
			"ref_no" => $refNo,
			"method" => $methodId,
			"type"	=> $type,
			"remarks"	=> $remarks
		);
		$detail = [];
		for ($i = 0; $i < count($variantId); $i++) {
			$detail[] = array(
				"product_variant_id" => $variantId[$i],
				"packing_id" => $packingId[$i],
				"expired_date" => $expiryDate[$i],
				"total_stock" => $amount[$i],
				"buy_price" => intval(str_replace(",", "", $buyPrice[$i])),
				"type"	=> "plus"
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


		$query = $this->stock->saveStock($toSave, $header, $detail);
		$response = array();
		if ($query) {
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		} else {
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);
	}

	public function saveAdjusmentStock()
	{
		$this->output->set_content_type('application/json');
		//TODO VALIDATION, IS BUY PRICE AVAIL ON STOCK ADJUSTMENT? IF YES THEN ADD TO UI AND WRITE LOGIC HERE TO DIVIDE UNIT PRICE BY PACK
		$refNo = $this->input->post("ref_no");
		$methodId = "APLIKASI";
		$remarks = $this->input->post("remarks");
		$type = "Penyesuaian";

		$adjustmentType = $this->input->post("type");
		$variantId = $this->input->post("variant_id");
		$packingId = $this->input->post("packing_id");
		$expiryDate = $this->input->post("expiry_date");
		$total = $this->input->post("total");
		$buyPrice = $this->input->post("buy_price");
		$stockId = $this->input->post("stock_id");

		// $expiryDateBefore = $this->input->post("expiry_date_before");
		// $totalBefore = $this->input->post("total_before");
		// $buyPriceBefore = $this->input->post("buy_price_before");

		$header = array(
			"ref_no" => $refNo,
			"method" => $methodId,
			"type"	=> $type,
			"remarks"	=> $remarks
		);
		$detail = [];
		for ($i = 0; $i < count($variantId); $i++) {
			$detail[] = array(
				"product_variant_id" => $variantId[$i],
				"packing_id" => $packingId[$i],
				"type" => $adjustmentType[$i],
				"expired_date" => $expiryDate[$i] == '-' ? "00-00-0000" : $expiryDate[$i],
				"total_stock" => $total[$i],
				"buy_price" => intval(str_replace(".", "", $buyPrice[$i])),
				"stock_id" => $stockId[$i]
			);
		}
		$toSave = [];
		for ($i = 0; $i < count($variantId); $i++) {
			$toSave[] = array(
				"stock_id" => $stockId[$i],
				"product_variant_id" => $variantId[$i],
				"total_stock" => $total[$i],
				"buy_price" => intval(str_replace(".", "", $buyPrice[$i])),
				"expired_date" => $expiryDate[$i],
				"type"	=> $adjustmentType[$i]
			);
		}


		$query = $this->stock->saveAdjustmentStock($toSave, $header, $detail);
		$response = array();
		if ($query) {
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		} else {
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);
	}

	public function getListAdjsutmentAddStock()
	{
		$this->output->set_content_type("application/json");
		$data =  $this->stock->getAdjustmentStockList();
		echo json_encode($data);
		return;
	}

	public function listAllStock()
	{
		$data['title']			= LIST_DESC . ' Stock';
		$data['subtitle']		= LIST_DESC . ' Stock ' . APPLICATION_NAME;
		$data['description']	= 'Stock ' . APPLICATION_NAME;
		$data['css_files'][]	= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2.min.css';
		$data['css_files'][]	= PATH_ASSETS . 'vendor/select2/dist/css/select2-bootstrap.css';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-table/bootstrap-table.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/select2/dist/js/select2.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'stock/listallstock', $data);
	}

	public function getListAllStock()
	{
		$this->output->set_content_type('application/json');
		$result = $this->stock->getListAllStock();
		echo json_encode($result);
	}

	public function saveBarang()
	{
		//TODO add validation
		$this->output->set_content_type('application/json');
		// echo json_encode($this->input->post());
		$productId = $this->input->post("product_id");
		$productCode = $this->input->post("product_code");
		$productName = $this->input->post("nama");
		$barcode = $this->input->post("barcode");
		$brandId = $this->input->post("brand_id");
		$limit = $this->input->post("limit_primary");
		$sellMethod = $this->input->post("selling_method");
		$sellMethodValue = $this->input->post("selling_method_value");
		$packageUnitid = $this->input->post("packing_id");
		$description = $this->input->post("description");
		$variantId = $this->input->post("variant_id");
		$variantCode = $this->input->post("variant_code");
		$variantName = $this->input->post("variant_name") ? $this->input->post("variant_name") : [];
		$variantDescription = $this->input->post("variant_description");
		$variantLimit = $this->input->post("variant_limit");

		if ($productId) {
		} else {
			if (!$this->form_validation->run("product_save")) {
				$errorMessage = $this->form_validation->error_array();
				$res = array(
					"status" 	=> FALSE,
					"message" 	=> VALIDATION_ERROR,
					"data"		=> $errorMessage
				);
				echo json_encode($res);
				return;
			} else {
				if ($variantName) {
					$validation = $this->variantValidation();
					if ($validation->error) {
						$res = array(
							"status" 	=> FALSE,
							"message" 	=> VALIDATION_ERROR,
							"data"		=> $validation->errorMessage
						);
						echo json_encode($res);
						return;
					}
				}
			}
		}
		$sellMethodValue = str_replace([",", "."], "", $sellMethodValue);
		if ($sellMethod == "margin") {
			$sellMethodValue = ["margin" => $sellMethodValue];
		} else {
			$sellMethodValue = ["price" => $sellMethodValue];
		}
		$productHeader = array(
			// "id" => $id,
			"nama" => $productName,
			"product_code" => $productCode,
			"barcode" => $barcode,
			"brand_id" => $brandId,
			"description" => $description,
		);
		$productHeader = array_merge($productHeader, $sellMethodValue);

		$productVariant[] = array(
			// "id" => $variantId[0],
			"nama" => $productName,
			"variant_code" => $productCode,
			"description" => $description,
			"limit_reminder" => $limit,
		);

		$countVariant = count($variantName);
		if ($countVariant >= 1) {
			$productVariant = array();
			for ($i = 0; $i < $countVariant; $i++) {
				$productVariant[] = array(
					// "id" => $variantId[$i+1],
					"nama" => $variantName[$i],
					"variant_code" => $productCode . "-" . $variantCode[$i],
					"description" => $variantDescription[$i],
					"limit_reminder" => $variantLimit[$i]
				);
			}
		};
		$query = $this->barang->save($productHeader, $productVariant, $packageUnitid);
		$response = array();
		if ($query) {
			$response =  ["status" => true, "message" => SAVE_SUCCESS];
		} else {
			$response =  ["status" => false, "message" => SAVE_FAILED];
		}
		echo json_encode($response);
		// $res = [$productHeader, $productVariant, $packageUnitid];
		// echo json_encode($res);
	}

	public function isProductCodeExist($key)
	{
		return !$this->barang->isProductCodeExist($key);
	}

	public function packingValidation($key)
	{
	}

	public function variantValidation()
	{

		$variantCode = $this->input->post("variant_code");
		$variantName = $this->input->post("variant_name");
		$productCode = $this->input->post("product_code");
		$variantDescription = $this->input->post("variant_description");
		$variantLimit = $this->input->post("variant_limit");

		$errorMessage = array();
		for ($i = 0; $i < count($variantName); $i++) {
			if ($variantCode[$i] == '') {
				$errorMessage["test" . ($i + 1)] = "ini kosong";
			} else {
				if ($this->barang->isVariantCodeExist($variantCode[$i], $productCode)) {
					$errorMessage["test" . ($i + 1)] = "ini dupes dari db";
				}
			}

			if ($variantLimit[$i] != '' && !preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $variantLimit[$i])) {
				$errorMessage["testissss" . ($i + 1)] = "ini bukan angka";
			}
		}

		for ($i = 0; $i < count($variantName) - 1; $i++) {
			for ($j = $i + 1; $j < count($variantName); $j++) {
				if ($variantCode[$i] === $variantCode[$j]) {
					$errorMessage["test" . ($j + 1)] = "ini dupes dari inputttt";
				}
			}
		}

		if (count($errorMessage) > 0) {
			return (object) ["error" => true, "errorMessage" => $errorMessage];
		} else {
			return (object) ["error" => false, "errorMessage" => $errorMessage];
		}
		// $role = $this->input->post('role_id');
		// $brand = $this->input->post('brand_id');
		// if ($role) {
		// 	if (count($role) > 0) {
		// 		if (in_array(ID_ROLE_BA, $role)) {
		// 			if ($brand) {
		// 				return true;
		// 			} else {
		// 				$this->form_validation->set_message('validationRole', 'Jika role adalah BA, pilih minimal 1 Brand.');
		// 				return false;
		// 			}
		// 		} else {
		// 			return true;
		// 		}
		// 	}
		// } else {
		// 	$this->form_validation->set_message('validationRole', 'Pilih minimal 1 {field}.');
		// 	return false;
		// }
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
}
