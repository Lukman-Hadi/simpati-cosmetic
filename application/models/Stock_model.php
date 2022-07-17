<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Stock_model extends MY_model
{
	private $table = "PRODUCTS p";
	private $variantTable = "PRODUCT_VARIANT pv";
	private $packageTable = "MST_PACKING mp";
	private $productPackingUnittable = "PRODUCT_PACKING_UNIT ppu";
	private $stockTable = "PRODUCT_STOCKS ps";
	private $brandTable = "MST_BRAND mb";

	function __construct()
	{
		parent::__construct();
	}
	//to controller
	function getListStock()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'PS.ID';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';
		$this->db->select("count(1) as total");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID AND P.IS_DELETED = 0 AND P.IS_ACTIVE = 1")->join($this->brandTable, "P.BRAND_ID = MB.ID")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID", "LEFT");
		$this->db->where("PV.IS_DELETED = 0 AND PV.IS_ACTIVE =1");
		if ($this->input->get('search')) {
			$this->db->group_start();
			$this->db->like('PV.NAMA', $search, 'both');
			$this->db->or_like('PV.VARIANT_CODE', $search, 'both');
			$this->db->or_like('MB.NAMA', $search, 'both');
			$this->db->or_like('PS.EXPIRED_DATE', $search, 'both');
			$this->db->group_end();
		}
		$count = $this->db->get()->row_array();
		$this->db->select("PS.ID AS stock_id
							,CASE WHEN P.NAMA = PV.NAMA THEN PV.NAMA ELSE CONCAT(P.NAMA,' - ',PV.NAMA ) END AS nama
							,PV.VARIANT_CODE as variant_code
							,MB.NAMA as brand_name
							,PS.TOTAL_STOCK as total_stock
							,PS.BUY_PRICE as buy_price
							,PS.EXPIRED_DATE as expiry_date
							,(SELECT
								GROUP_CONCAT(CONCAT(MP.AMOUNT,'.',MP.NAMA)SEPARATOR'|')
								FROM PRODUCT_PACKING_UNIT PPU 
								JOIN MST_PACKING MP ON PPU.PACKING_ID = MP.ID 
								WHERE PRODUCT_ID =P.ID ORDER BY PPU.SORT_ORDER) pack_amount");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID AND P.IS_DELETED = 0 AND P.IS_ACTIVE = 1")->join($this->brandTable, "P.BRAND_ID = MB.ID")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID", "LEFT");
		$this->db->where("PV.IS_DELETED = 0 AND PV.IS_ACTIVE =1");
		if ($this->input->get('search')) {
			$this->db->group_start();
			$this->db->like('PV.NAMA', $search, 'both');
			$this->db->or_like('PV.VARIANT_CODE', $search, 'both');
			$this->db->or_like('MB.NAMA', $search, 'both');
			$this->db->or_like('PS.EXPIRED_DATE', $search, 'both');
			$this->db->group_end();
		}
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$item = $query->result_array();
		foreach ($item as $index => $value) {
			$item[$index]["Pack"] = $this->convertTotalToPackUnit($value["total_stock"], $value["pack_amount"]);
		}
		// $count = $this->querySingle($sqlCount);
		$result['total'] = $count['total'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function getListStockSell()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'PS.ID';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';
		$this->db->select("count(distinct pv.id) as total");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID AND P.IS_DELETED = 0 AND P.IS_ACTIVE = 1")->join($this->brandTable, "P.BRAND_ID = MB.ID")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID", "LEFT");
		$this->db->where("PV.IS_DELETED = 0 AND PV.IS_ACTIVE =1");
		if ($this->input->get('search')) {
			$this->db->like('PV.NAMA', $search, 'both');
			$this->db->or_like('PV.VARIANT_CODE', $search, 'both');
			$this->db->or_like('MB.NAMA', $search, 'both');
			$this->db->or_like('PS.EXPIRED_DATE', $search, 'both');
		}
		$count = $this->db->get()->row_array();
		$this->db->select("PV.ID AS id ,
							PV.ID AS variant_id,
								CASE
									WHEN P.NAMA = PV.NAMA THEN PV.NAMA
									ELSE CONCAT(P.NAMA, ' - ', PV.NAMA )
								END AS nama ,
								concat(p.nama ,
							(case when p.nama <> pv.nama then concat(' - ',pv.nama)
							else '' end)) as product,
								SUM(PS.TOTAL_STOCK) as total_stock,
								CAST(CASE
									WHEN P.PRICE IS NULL THEN (AVG(PS.BUY_PRICE) + ((AVG(PS.BUY_PRICE) * P.MARGIN)/ 100))
									ELSE P.PRICE
								END as DECIMAL) AS price ,
								(
									SELECT
										GROUP_CONCAT(CONCAT(MP.AMOUNT, '.', MP.NAMA)SEPARATOR '|')
									FROM
										PRODUCT_PACKING_UNIT PPU
									JOIN MST_PACKING MP ON
										PPU.PACKING_ID = MP.ID
									WHERE
										PRODUCT_ID = P.ID
									ORDER BY
										PPU.SORT_ORDER
								) pack_amount,
								(select group_concat(concat(ppu.sort_order,'.',mp.id,'.',mp.nama,'.',mp.amount) ORDER BY ppu.sort_order desc separator'|') from product_packing_unit ppu 
									join mst_packing mp
										on mp.id = ppu.packing_id
									where ppu.product_id =p.id) as packing_units ");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID AND P.IS_DELETED = 0 AND P.IS_ACTIVE = 1")->join($this->brandTable, "P.BRAND_ID = MB.ID")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID", "LEFT");
		$this->db->where("PV.IS_DELETED = 0 AND PV.IS_ACTIVE =1");
		if ($this->input->get('search')) {
			$this->db->like('PV.NAMA', $search, 'both');
			$this->db->or_like('PV.VARIANT_CODE', $search, 'both');
			$this->db->or_like('PS.EXPIRED_DATE', $search, 'both');
		}
		$this->db->group_by('PV.id');
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $offset);
		// echo $this->db->last_query();die();
		$query = $this->db->get();
		$item = $query->result_array();
		foreach ($item as $index => $value) {
			$item[$index]["Pack"] = $this->convertTotalToPackUnit($value["total_stock"], $value["pack_amount"]);
		}
		// $count = $this->querySingle($sqlCount);
		$result['total'] = $count['total'];
		foreach ($item as $key => $value) {
			$packArr = [];
			$pack = explode("|", $value["packing_units"]);
			foreach ($pack as $p) {
				$splitted = explode(".", $p);
				$packArr[] = array(
					"sortOrder" => intval($splitted[0]),
					"id" => intval($splitted[1]),
					"text" => $splitted[2],
					"amount" => intval($splitted[3])
				);
			}
			$item[$key]["packing_units"] = $packArr;
		}
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function getListProductsSell()
	{
		$offset = $this->input->get('page') != null ? intval($this->input->get('page')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 10;
		$search = $this->input->get('q') != null ? strval($this->input->get('q')) : '';

		if ($offset > 0) {
			$offset -= 1;
		}
		$this->db->select("count(distinct(pv.id)) as total");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID and p.is_active = 1 and p.is_deleted = 0")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID");
		$this->db->where('pv.IS_DELETED', 0)
			->where('pv.IS_ACTIVE', 1)
			->where('PS.TOTAL_STOCK >', 0);
		if ($search) {
			$this->db->group_start();
			$this->db->like("p.nama", $search, 'BOTH')
				->or_like("pv.nama", $search, "BOTH")
				->or_like("p.product_code", $search, "BOTH")
				->or_like("pv.variant_code", $search, "BOTH");
			$this->db->group_end();
		}
		$count = $this->db->get()->row_array();
		$result['total'] = $count["total"];
		$this->db->select("pv.id as id,
							pv.id as variant_id,
							concat(pv.variant_code,' - ',p.nama,
							(case when p.nama <> pv.nama then concat(' - ',pv.nama)
							else '' end)
						) as text,
						concat(p.nama ,
							(case when p.nama <> pv.nama then concat(' - ',pv.nama)
							else '' end)) as product,
						(select group_concat(concat(ppu.sort_order,'.',mp.id,'.',mp.nama,'.',mp.amount) ORDER BY ppu.sort_order desc separator'|') from product_packing_unit ppu 
						join mst_packing mp
							on mp.id = ppu.packing_id
						where ppu.product_id =p.id) as packing_units,
						SUM(PS.TOTAL_STOCK) as total_stock,
						CAST(CASE
									WHEN P.PRICE IS NULL THEN (AVG(PS.BUY_PRICE) + ((AVG(PS.BUY_PRICE) * P.MARGIN)/ 100))
									ELSE P.PRICE
								END as DECIMAL) AS price")
			->from($this->variantTable)
			->join($this->table, "p.ID = pv.PRODUCT_ID and p.is_active = 1 and p.is_deleted = 0")
			->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID");
		$this->db->where('pv.IS_DELETED', 0)
			->where('pv.IS_ACTIVE', 1)
			->where('PS.TOTAL_STOCK >', 0);
		$this->db->group_start();
		$this->db->like("p.nama", $search, 'BOTH')
			->or_like("pv.nama", $search, "BOTH")
			->or_like("p.product_code", $search, "BOTH")
			->or_like("pv.variant_code", $search, "BOTH");
		$this->db->group_end();
		$this->db->group_by('PV.id');
		$this->db->limit($limit, ($offset * $limit));
		$query = $this->db->get()->result_array();
		// $test = $this->db->last_query();
		// echo $test;
		foreach ($query as $key => $value) {
			$packArr = [];
			$pack = explode("|", $value["packing_units"]);
			foreach ($pack as $p) {
				$splitted = explode(".", $p);
				$packArr[] = array(
					"sortOrder" => intval($splitted[0]),
					"id" => intval($splitted[1]),
					"text" => $splitted[2],
					"amount" => intval($splitted[3])
				);
			}
			$query[$key]["packing_units"] = $packArr;
		}
		$result = array_merge($result, ['items' => $query]);
		return $result;
	}

	function getListStockForAdjustment()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'PS.ID';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';
		$excludeId = $this->input->get('id') != null ? $this->input->get('id') : '';
		$this->db->select("count(1) as total");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID AND P.IS_DELETED = 0 AND P.IS_ACTIVE = 1")->join($this->brandTable, "P.BRAND_ID = MB.ID")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID");
		$this->db->where("PV.IS_DELETED = 0 AND PV.IS_ACTIVE =1");
		if ($excludeId) {
			$this->db->where_not_in('PS.id', explode(",", $excludeId));
		}
		if (in_array(ID_ROLE_BA, $this->session->userdata("role"))) {
			$this->db->where_in("P.BRAND_ID", $this->session->userdata("brand"));
		}
		if ($this->input->get('search')) {
			$this->db->group_start();
			$this->db->like('PV.NAMA', $search, 'both');
			$this->db->or_like('PV.VARIANT_CODE', $search, 'both');
			$this->db->or_like('MB.NAMA', $search, 'both');
			$this->db->or_like('PS.EXPIRED_DATE', $search, 'both');
			$this->db->group_end();
		}
		$count = $this->db->get()->row_array();
		$this->db->select("PS.ID AS stock_id
							,CASE WHEN P.NAMA = PV.NAMA THEN PV.NAMA ELSE CONCAT(P.NAMA,' - ',PV.NAMA ) END AS nama
							,PV.VARIANT_CODE as variant_code
							,PV.ID AS variant_id
							,MB.NAMA as brand_name
							,PS.TOTAL_STOCK as total_stock
							,PS.BUY_PRICE as buy_price
							,PS.EXPIRED_DATE as expiry_date
							,(SELECT PACKING_ID FROM PRODUCT_PACKING_UNIT WHERE PRODUCT_ID = P.ID ORDER BY SORT_ORDER DESC LIMIT 1) packing_id
							,(SELECT
								GROUP_CONCAT(CONCAT(MP.AMOUNT,'.',MP.NAMA)SEPARATOR'|')
								FROM PRODUCT_PACKING_UNIT PPU 
								JOIN MST_PACKING MP ON PPU.PACKING_ID = MP.ID 
								WHERE PRODUCT_ID =P.ID ORDER BY PPU.SORT_ORDER) pack_amount");
		$this->db->from($this->variantTable);
		$this->db->join($this->table, "PV.PRODUCT_ID = P.ID AND P.IS_DELETED = 0 AND P.IS_ACTIVE = 1")->join($this->brandTable, "P.BRAND_ID = MB.ID")->join($this->stockTable, "PS.PRODUCT_VARIANT_ID = PV.ID");
		$this->db->where("PV.IS_DELETED = 0 AND PV.IS_ACTIVE =1");
		if ($excludeId) {
			$this->db->where_not_in('PS.id', explode(",", $excludeId));
		}
		if (in_array(ID_ROLE_BA, $this->session->userdata("role"))) {
			$this->db->where_in("P.BRAND_ID", $this->session->userdata("brand"));
		}
		if ($this->input->get('search')) {
			$this->db->group_start();
			$this->db->like('PV.NAMA', $search, 'both');
			$this->db->or_like('PV.VARIANT_CODE', $search, 'both');
			$this->db->or_like('MB.NAMA', $search, 'both');
			$this->db->or_like('PS.EXPIRED_DATE', $search, 'both');
			$this->db->group_end();
		}
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$item = $query->result_array();
		foreach ($item as $index => $value) {
			$item[$index]["Pack"] = $this->convertTotalToPackUnit($value["total_stock"], $value["pack_amount"]);
		}
		// $count = $this->querySingle($sqlCount);
		$result['total'] = $count['total'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function convertTotalToPackUnit($total, $unitString)
	{
		$units = explode("|", $unitString);
		$remain = $total;
		for ($i = 0; $i < count($units); $i++) {
			$packUnit = explode(".", $units[$i]);
			$amount[] = $packUnit[0];
			$unit[] = $packUnit[1];
		}
		for ($i = 0; $i < count($units); $i++) {
			$currentAmountPerUnit = 1;
			for ($j = $i; $j < count($units); $j++) {
				$currentAmountPerUnit *= $amount[$j];
			}
			if ($remain % $currentAmountPerUnit == $remain) {
				$currentTotalUnit = 0;
				$remain = $remain;
			} else {
				$tempRemain = $remain % $currentAmountPerUnit;
				$currentTotalUnit = ($remain - $tempRemain) / $currentAmountPerUnit;
				$remain = $tempRemain;
			}
			$temp[] = ["total" => $currentTotalUnit, "unit" => $unit[$i]];
			if ($remain == 0) {
				// break;
			}
		}
		return $temp;
	}

	function getExpiryStockById($id)
	{
		$this->db->select("id,expired_date,total_stock")->from($this->stockTable)->where("PRODUCT_VARIANT_ID", $id);
		return $this->db->get()->result();
	}
	function isStockExist($id)
	{
		$res = false;
		$this->db->select("1")->from($this->stockTable)->where("PRODUCT_VARIANT_ID", $id)->where("TOTAL_STOCK > 1");
		$result = $this->db->get()->num_rows();
		if ($result > 0) {
			$res = true;
		}
		return $res;
	}

	function saveAdjusmentStock($data)
	{
	}

	function checkStock($id, $amount)
	{
	}

	function saveStock($data, $header, $detail)
	{
		$this->db->trans_start();
		$this->insertUpdateStockBatch('product_stocks', $data);
		$this->insertOrUpdate("STOCK_ADJUSTMENT", $header);
		$id  = $this->db->insert_id();
		for ($i = 0; $i < count($detail); $i++) {
			$detail[$i]['stock_adjustment_id'] = $id;
		}
		$test = '';
		$this->db->insert_batch("STOCK_ADJUSTMENT_DETAIL", $detail);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	function saveAdjustmentStock($data, $header, $detail)
	{
		$this->db->trans_start();
		$this->insertOrUpdate("STOCK_ADJUSTMENT", $header);
		$id  = $this->db->insert_id();
		$this->insertDetailAndBeforeStock($id, $detail);
		$this->insertUpdateStockBatch('product_stocks', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	function getAmountByPack($packageUnitid, $variantId)
	{
		$this->db->select("mp.id,mp.amount,ppu.sort_order");
		$this->db->from($this->productPackingUnittable);
		$this->db->join($this->packageTable, "mp.id = ppu.packing_id");
		$this->db->join($this->table, "p.id = ppu.product_id");
		$this->db->join($this->variantTable, "p.id = pv.product_id");
		$this->db->where("PV.ID", $variantId);
		$this->db->order_by("ppu.sort_order", "asc");
		$hierarchyPacking = $this->db->get()->result();
		$amount = 1;
		$index = 0;

		foreach ($hierarchyPacking as $key => $value) {
			if ($value->id == $packageUnitid) {
				$index = $key;
				break;
			}
		}
		for ($index; $index < count($hierarchyPacking); $index++) {
			$amount *= $hierarchyPacking[$index]->amount;
		}
		return $amount;
	}

	function getAll()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->db->escape($this->input->get('sort'))) : 'CREATED_AT';
		$order = $this->input->get('order') != null ? strval($this->db->escape($this->input->get('order'))) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->db->escape($this->input->get('search'))) : '';

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
		$this->insertOrUpdate($this->table, $productHeader);
		$productHeaderid = $this->db->insert_id();
		for ($i = 0; $i < count($productVariant); $i++) {
			$productVariant[$i]["product_id"] = $productHeaderid;
		}
		$packageUnit = array();
		foreach ($packageUnitid as $key => $value) {
			$packageUnit[] = array(
				"product_id" => $productHeaderid,
				"packing_id" => $value,
				"sort_order" => $key + 1
			);
		}
		$this->db->insert_batch($this->variantTable, $productVariant);
		$this->db->insert_batch($this->productPackingUnittable, $packageUnit);
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

	function getRef($month, $year)
	{
		$sql = "SELECT COUNT(1)+1 as NUM FROM STOCK_ADJUSTMENT WHERE MONTH(CREATED_AT) = $month and YEAR(CREATED_AT) = $year";
		$count =  $this->querySingle($sql);
		return $count["NUM"];
	}

	function getAdjustmentStockList()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->db->escape($this->input->get('sort'))) : 'CREATED_AT';
		$order = $this->input->get('order') != null ? strval($this->db->escape($this->input->get('order'))) : 'DESC';
		$search = $this->input->get('search') != null ? '%' . strval($this->db->escape($this->input->get('search'))) . '%' : '%%';

		$sqlCount = "SELECT COUNT(1) as total_rows FROM STOCK_ADJUSTMENT WHERE IS_DELETED = 0 AND (
						UPPER(REF_NO) LIKE ?
						OR UPPER(USER_MODIFIED) LIKE ?
					)";
		$sql = "SELECT 
					SA.ID AS id
					,SA.REF_NO AS ref_no 
					,SA.`METHOD` AS method
					,SA.`TYPE` AS type
					,SA.USER_MODIFIED AS user_modified 
					,SA.CREATED_AT AS created_at
					,SUM(CASE WHEN SAD.`TYPE` = 'PENYESUAIAN' THEN (SAD.TOTAL_STOCK-SADB.TOTAL_STOCK)
						WHEN SAD.TYPE = 'PENGHAPUSAN' THEN -(SADB.TOTAL_STOCK)
						WHEN SAD.TYPE = 'PLUS' THEN (SAD.TOTAL_STOCK)
						END) AS total_stock
					,SUM(
						CASE WHEN SAD.`TYPE` = 'PENYESUAIAN' THEN ((SAD.TOTAL_STOCK * SAD.BUY_PRICE)-(SADB.TOTAL_STOCK * SADB.BUY_PRICE))
						WHEN SAD.TYPE = 'PENGHAPUSAN' THEN -(SADB.TOTAL_STOCK * SADB.BUY_PRICE)
						WHEN SAD.TYPE = 'PLUS' THEN (SAD.TOTAL_STOCK * SAD.BUY_PRICE)
						END) AS total_price
				FROM STOCK_ADJUSTMENT SA
				JOIN STOCK_ADJUSTMENT_DETAIL SAD ON SA.ID = SAD.STOCK_ADJUSTMENT_ID
				LEFT JOIN STOCK_ADJUSTMENT_DETAIL_BEFORE SADB ON SAD.ID = SADB.STOCK_ADJUSTMENT_DETAIL_ID
				WHERE SA.IS_DELETED =0
				AND (
					UPPER(REF_NO) LIKE UPPER(?)
					OR UPPER(USER_MODIFIED) LIKE UPPER(?)
				)
				GROUP BY SA.ID
				ORDER BY ? ?
				LIMIT ? 
				OFFSET ?
				";
		$count = $this->db->query($sqlCount, [$search, $search])->row_array();
		$item = $this->db->query($sql, [$search, $search, $sort, $order, $limit, $offset])->result();
		$result['total'] = $count['total_rows'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function getAdjustmentAddDetail($id)
	{
		$this->db->select("ref_no,method,type,remarks,user_modified,created_at");
		$this->db->from("stock_adjustment");
		$this->db->where("id", $id);
		$header = $this->db->get();
		if ($header->num_rows() < 1) {
			return false;
		}
		$sqlDetail = "	SELECT
							CASE
								WHEN P.NAMA = PV.NAMA THEN PV.NAMA
								ELSE CONCAT(P.NAMA, ' - ', PV.NAMA )
							END AS nama ,
							`PV`.`VARIANT_CODE` AS variant_code ,
							`MB`.`NAMA` AS brand_name ,
							SAD.TYPE as type,
							SAD.`BUY_PRICE` AS buy_price ,
							CONCAT(SAD.TOTAL_STOCK, ' ', MP.NAMA) AS total_display ,
							SAD.TOTAL_STOCK AS total,
							SAD.`EXPIRED_DATE` AS expired_date ,
							CONCAT(SADB.TOTAL_STOCK, ' ', MP.NAMA) AS total_stock_display_before ,
							SADB.TOTAL_STOCK AS total_stock_before,
							SADB.BUY_PRICE AS buy_price_before ,
							SADB.EXPIRED_DATE AS expired_date_before
						FROM
							STOCK_ADJUSTMENT_DETAIL SAD
						JOIN PRODUCT_VARIANT PV ON
							PV.ID = SAD.PRODUCT_VARIANT_ID
						JOIN PRODUCTS P ON
							P.ID = PV.PRODUCT_ID
						JOIN MST_BRAND MB ON
							P.BRAND_ID = MB.ID
						JOIN MST_PACKING MP ON
							MP.ID = SAD.PACKING_ID
						LEFT JOIN STOCK_ADJUSTMENT_DETAIL_BEFORE SADB ON
							SADB.STOCK_ADJUSTMENT_DETAIL_ID = SAD.ID
						WHERE SAD.stock_adjustment_id = ?";
		$detail = $this->db->query($sqlDetail, [$id])->result();
		$data = ["header" => $header->row(), "detail" => $detail];
		return $data;
	}








	/*
	function getAll()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'created_at';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';

		$this->db->select('id');
		$this->db->from($this->table);
		$this->db->where("is_deleted", 0);
		if ($this->input->get('search')) {
			$this->db->like('nama', $search, 'both');
			$this->db->like('description', $search, 'both');
		}
		$result['total'] = $this->db->get()->num_rows();

		$this->db->select('id,nama,description,is_active');
		$this->db->from($this->table);
		$this->db->where("is_deleted", 0);
		if ($this->input->get('search')) {
			$this->db->like('nama', $search, 'both');
			$this->db->like('description', $search, 'both');
		}
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$item = $query->result_array();
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function save($data)
	{
		return $this->insertOrUpdate($this->table, $data);
	}

	function isExist($key)
	{
		$count = $this->db->select("ID")
			->from($this->table)
			->where("ID", $key)
			->get()
			->num_rows();
		if ($count > 0) return true;
		return false;
	}

	function getById($id)
	{
		$result = array();
		if (is_null($id) || $id == 0) {
			$result = array(
				"status" 	=> FALSE,
				"message" 	=> MUST_PROVIDED,
				"data"		=> null
			);
			return $result;
		}
		$data = $this->db->select("id,nama,description")->where('id', $id)->where('is_deleted', 0)->get($this->table);
		if ($data->num_rows() == 1) {
			$result = array(
				"status" 	=> TRUE,
				"message" 	=> SUCCESS_GET_DATA,
				"data"		=> $data->result()
			);
		} else {
			$result = array(
				"status" 	=> FALSE,
				"message" 	=> FAILED_GET_DATA,
				"data"		=> null
			);
		}
		return $result;
	}

	function delete($id)
	{
		$query =  $this->softDelete($this->table, $id);
		$result = array();
		if ($query) {
			$result = ["status" => TRUE, "message" => DELETE_SUCCESS];
		} else {
			$result = ["status" => FALSE, "message" => GENERAL_ERROR];
		}
		return $result;
	}

	function setActive($id)
	{
		$query = $this->setIsActive($this->table, $id);
		if ($query) {
			$result = ["status" => TRUE, "message" => UPDATE_SUCCESS];
		} else {
			$result = ["status" => FALSE, "message" => GENERAL_ERROR];
		}
		return $result;
	}
	
	function getListBrand(){
		$data= $this->db->select('id,nama')
						->from($this->table)
						->where('is_active',1)
						->where('is_deleted',0)
						->get();
		if($data->num_rows()){
			$result = array(
				"status" 	=> TRUE,
				"message" 	=> SUCCESS_GET_DATA,
				"data"		=> $data->result()
			);
		}else{
			$result = array(
				"status" 	=> FALSE,
				"message" 	=> FAILED_GET_DATA,
				"data"		=> null
			);
		}
		return $result;
	}
	*/
}
