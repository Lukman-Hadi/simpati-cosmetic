<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!is_login())redirect(site_url('login'));
		$this->load->model("Barang_model", "barang");
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
		$data['js_files'][]		= PATH_ASSETS . 'js/jquery.mask.min.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/common.js';
		$data['js_files'][]		= PATH_ASSETS . 'js/validation.js';
		$this->template->load('template/template', 'barang/add', $data);
	}

	public function getProducts()
	{
		$this->output->set_content_type('application/json');
		$result = $this->barang->getAll();
		echo json_encode($result);
	}

	public function getSingleProducts()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->get("id");
		$result = $this->barang->getById($id);
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

	public function deleteBarang()
	{
		$this->output->set_content_type('application/json');
		$id = $this->input->post('id');
		$response =  $this->barang->delete($id);
		echo json_encode($response);
	}
}
