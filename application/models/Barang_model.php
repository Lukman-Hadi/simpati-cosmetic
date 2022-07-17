<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Barang_model extends MY_model
{
	private $table = "PRODUCTS p";
	private $variantTable = "PRODUCT_VARIANT pv";
	private $productPackingUnittable = "PRODUCT_PACKING_UNIT ppu";

	function __construct()
	{
		parent::__construct();
		$this->user = $this->session->userdata('username');
	}
	//to controller
	function getAll()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'CREATED_AT';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? $this->db->escape_like_str($this->input->get('search')) : '';

		$sqlCount = "SELECT count(1) AS total FROM PRODUCTS WHERE IS_DELETED = 0";
		$sql = "SELECT id,product_code,product_name,brand_name,list_variant,IFNULL(PRICE,MARGIN) AS sell_value,sell_method,limit_reminder,is_active, product_smallest_pack as packing
				FROM(
					SELECT 
						P.ID AS ID
						,P.PRODUCT_CODE AS PRODUCT_CODE
						,P.NAMA AS PRODUCT_NAME
						,MB.NAMA AS BRAND_NAME
						,P.PRICE AS PRICE
						,P.MARGIN AS MARGIN
						,P.IS_ACTIVE AS IS_ACTIVE
						,P.BARCODE AS BARCODE
						,CASE WHEN (P.PRICE IS NOT NULL) THEN 'RP' ELSE '%' END AS SELL_METHOD 
						,GROUP_CONCAT(DISTINCT PV.NAMA ORDER BY PV.NAMA ASC SEPARATOR ', ') AS LIST_VARIANT
						,CASE WHEN (UPPER(PV.NAMA) = P.NAMA) THEN PV.LIMIT_REMINDER ELSE (MIN(PV.LIMIT_REMINDER)) END AS LIMIT_REMINDER
						,P.CREATED_AT AS CREATED_AT
						,MP.NAMA AS PRODUCT_SMALLEST_PACK
					FROM PRODUCT_VARIANT PV
					JOIN PRODUCTS P 
						ON P.ID = PV.PRODUCT_ID
						AND P.IS_ACTIVE = 1
						AND P.IS_DELETED = 0
					JOIN MST_BRAND MB 
						ON MB.ID = P.BRAND_ID
						AND MB.IS_ACTIVE = 1
						AND MB.IS_DELETED = 0
					LEFT JOIN PRODUCT_PACKING_UNIT PPU 
						ON PPU.PRODUCT_ID = P.ID
						AND PPU.SORT_ORDER = (SELECT MAX(SORT_ORDER) FROM PRODUCT_PACKING_UNIT PPW WHERE PPW.PRODUCT_ID = P.ID)
					LEFT JOIN MST_PACKING MP 
						ON MP.ID = PPU.PACKING_ID
						AND MP.IS_ACTIVE = 1
						AND MP.IS_DELETED = 0
					GROUP BY P.ID
				) PRODUCTS
				WHERE UPPER(PRODUCTS.PRODUCT_CODE) LIKE UPPER('%$search%')
				OR UPPER(PRODUCTS.PRODUCT_NAME) LIKE UPPER('%$search%')
				OR UPPER(PRODUCTS.BRAND_NAME) LIKE UPPER('%$search%')
				OR UPPER(PRODUCTS.LIST_VARIANT) LIKE UPPER('%$search%')
				OR UPPER(PRODUCTS.BARCODE) LIKE UPPER('%$search%')
				ORDER BY $sort $order
				LIMIT $limit OFFSET $offset";
		$count = $this->querySingle($sqlCount);
		$item = $this->queryArray($sql);
		$result['total'] = $count['total'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function save($productHeader, $productVariant, $packageUnitid)
	{
		$this->db->trans_start();
		$this->insertOrUpdate('PRODUCTS', $productHeader);
		$productHeaderid = $this->db->insert_id();
		for ($i = 0; $i < count($productVariant); $i++) {
			$productVariant[$i]["product_id"] = $productHeaderid;
			$productVariant[$i]["user_modified"] = $this->user;
		}
		$packageUnit = array();
		foreach ($packageUnitid as $key => $value) {
			$packageUnit[] = array(
				"product_id" => $productHeaderid,
				"packing_id" => $value,
				"sort_order" => $key + 1
			);
		}
		$this->db->insert_batch('PRODUCT_VARIANT', $productVariant);
		$this->db->insert_batch('PRODUCT_PACKING_UNIT', $packageUnit);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	function isProductCodeExist($key)
	{
		$count = $this->db->select("ID")
			->from($this->table)
			->where("PRODUCT_CODE", $key)
			->get()
			->num_rows();
		if ($count > 0) return true;
		return false;
	}

	function isVariantCodeExist($code, $productCode)
	{
		$count = $this->db->query("select 1 from product_variant pv
										join products p on p.id = pv.product_id
										where pv.variant_code = '$code' and p.product_code = '$productCode'")->num_rows();
		if ($count > 0) return true;
		return false;
	}

	function getListProductVariant()
	{
		$offset = $this->input->get('page') != null ? intval($this->input->get('page')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 10;
		$search = $this->input->get('q') != null ? strval($this->input->get('q')) : '';

		if ($offset > 0) {
			$offset -= 1;
		}
		$this->db->select("1")
			->from($this->variantTable)
			->join($this->table, "p.ID = pv.PRODUCT_ID and p.is_active = 1 and p.is_deleted = 0");
		$this->db->where('pv.IS_DELETED', 0)
			->where('pv.IS_ACTIVE', 1);
		if (in_array(ID_ROLE_BA, $this->session->userdata("role"))) {
			$this->db->where_in("p.brand_id", $this->session->userdata("brand"));
		}
		if ($search) {
			$this->db->group_start();
			$this->db->like("p.nama", $search, 'BOTH')
				->or_like("pv.nama", $search, "BOTH")
				->or_like("p.product_code", $search, "BOTH")
				->or_like("pv.variant_code", $search, "BOTH");
			$this->db->group_end();
		}
		$result['total'] = $this->db->get()->num_rows();
		$this->db->select("pv.id as id,
							concat(pv.variant_code,' - ',p.nama,
							(case when p.nama <> pv.nama then concat(' - ',pv.nama)
							else '' end)
						) as text,
						concat(p.nama ,
							(case when p.nama <> pv.nama then concat(' - ',pv.nama)
							else '' end)) as product,
						(select group_concat(concat(ppu.sort_order,'.',mp.id,'.',mp.nama)separator'|') from product_packing_unit ppu 
						join mst_packing mp
							on mp.id = ppu.packing_id
						where ppu.product_id =p.id
						order by ppu.sort_order asc) as packing_units")
			->from($this->variantTable)
			->join($this->table, "p.ID = pv.PRODUCT_ID and p.is_active = 1 and p.is_deleted = 0");
		$this->db->where('pv.IS_DELETED', 0)
			->where('pv.IS_ACTIVE', 1);
		if (in_array(ID_ROLE_BA, $this->session->userdata("role"))) {
			$this->db->where_in("p.brand_id", $this->session->userdata("brand"));
		}
		if ($search) {
			$this->db->group_start();
			$this->db->like("p.nama", $search, 'BOTH')
				->or_like("pv.nama", $search, "BOTH")
				->or_like("p.product_code", $search, "BOTH")
				->or_like("pv.variant_code", $search, "BOTH");
			$this->db->group_end();
		}
		$this->db->limit($limit, ($offset * $limit));
		$query = $this->db->get()->result_array();
		foreach ($query as $key => $value) {
			$packArr = [];
			$pack = explode("|", $value["packing_units"]);
			foreach ($pack as $p) {
				$splitted = explode(".", $p);
				$packArr[] = array(
					"sortOrder" => intval($splitted[0]),
					"id" => intval($splitted[1]),
					"text" => $splitted[2],
				);
			}
			$query[$key]["packing_units"] = $packArr;
		}
		$result = array_merge($result, ['items' => $query]);
		return $result;
	}

	function getById($id)
	{
		$id = intval($id);
		$sqlHeader = "SELECT 
						P.NAMA as nama
						,P.PRODUCT_CODE as productCode
						,MB.NAMA as brand
						,P.BARCODE  as barcode
						,IFNULL(PRICE,MARGIN) as sellValue
						,CASE WHEN (P.PRICE IS NOT NULL) THEN 'RP' ELSE '%' END AS sellMethod
						,P.USER_MODIFIED as userModified
						,IFNULL(P.UPDATED_AT,P.CREATED_AT) as lastUpdated
						FROM PRODUCTS P 
						JOIN MST_BRAND MB 
							ON MB.ID = P.BRAND_ID
							AND MB.IS_DELETED =0
						WHERE P.ID = $id";
		$sqlDetail = "SELECT 
						PV.NAMA as nama
						,PV.VARIANT_CODE as variantCode
						,PV.LIMIT_REMINDER as limitReminder
						,PV.DESCRIPTION as description
						FROM PRODUCT_VARIANT PV 
						JOIN PRODUCTS P 
							ON P.ID = PV.PRODUCT_ID
							AND P.IS_DELETED =0
						WHERE P.ID = $id
						AND PV.IS_DELETED =0";
		$sqlPack = "SELECT 
						MP.NAMA as nama
						,MP.UNIT as unit
						,MP.DESCRIPTION as description
						FROM PRODUCT_PACKING_UNIT PPU 
						JOIN MST_PACKING MP 
							ON PPU.PACKING_ID = MP.ID
							AND MP.IS_DELETED = 0
						WHERE PRODUCT_ID = $id
						ORDER BY PPU.SORT_ORDER ASC";
		$response = [];
		$productHeader = $this->querySingle($sqlHeader);
		if ($productHeader == null) {
			$response = ["status" => false, "message" => FAILED_GET_DATA, "data" => []];
		} else {
			$productVariant = $this->queryArray($sqlDetail);
			$productPack = $this->queryArray($sqlPack);
			$productHeader["productVariant"] = $productVariant;
			$productHeader["productPack"] = $productPack;
			$response = ["status" => true, "message" => SUCCESS_GET_DATA, "data" => $productHeader];
		}
		return $response;
	}

	function delete($id)
	{
		$query =  $this->softDelete('products', $id);
		$query2 =  $this->softDeleteChild('product_variant', 'product_id', $id);
		$result = array();
		if ($query && $query2) {
			$result = ["status" => TRUE, "message" => DELETE_SUCCESS];
		} else {
			$result = ["status" => FALSE, "message" => GENERAL_ERROR];
		}
		return $result;
	}
}
