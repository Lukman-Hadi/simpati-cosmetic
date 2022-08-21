<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Pembelian_model extends MY_model
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
	
	function savePembelian($data, $header, $detail)
	{
		$this->db->trans_start();
		$this->insertUpdateStockBatch('product_stocks', $data);
		$this->insertOrUpdate("PEMBELIAN", $header);
		$id  = $this->db->insert_id();
		for ($i = 0; $i < count($detail); $i++) {
			$detail[$i]['header_id'] = $id;
		}
		$test = '';
		$this->db->insert_batch("PEMBELIAN_DETAIL", $detail);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return true;
		}
	}
	
	function getListPembelian()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->db->escape($this->input->get('sort'))) : 'P.TRANS_DATE';
		$order = $this->input->get('order') != null ? strval($this->db->escape($this->input->get('order'))) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';

		$sqlCount = "SELECT 
						COUNT(DISTINCT P.ID) as total
					FROM PEMBELIAN P
					LEFT JOIN PEMBELIAN_DETAIL PD 
						ON PD.HEADER_ID = P.ID
					LEFT JOIN MST_SUPPLIER MS 
						ON P.SUPPLIER_ID = MS.ID 
					WHERE P.IS_DELETED =0
					AND(
						UPPER(P.INVOICE_NO) LIKE UPPER('%$search%') OR
						UPPER(MS.NAMA) LIKE UPPER('%$search%') OR
						UPPER(P.USER_MODIFIED) LIKE UPPER('%$search%')
					)";
		$sql = "SELECT 
					P.ID as id
					,P.INVOICE_NO as invoice_no
					,MS.NAMA as supplier
					,P.TRANS_DATE as trans_date
					,P.USER_MODIFIED as user_modified
					,P.remarks as remarks
					, SUM((PD.TOTAL_QTY * PD.BUY_PRICE)) as grand_total 
				FROM PEMBELIAN P
				JOIN PEMBELIAN_DETAIL PD 
					ON PD.HEADER_ID = P.ID
				LEFT JOIN MST_SUPPLIER MS 
					ON P.SUPPLIER_ID = MS.ID 
				WHERE P.IS_DELETED =0
				AND(
					UPPER(P.INVOICE_NO) LIKE UPPER('%$search%') OR
					UPPER(MS.NAMA) LIKE UPPER('%$search%') OR
					UPPER(P.USER_MODIFIED) LIKE UPPER('%$search%')
				)
				GROUP BY P.ID
				ORDER BY $sort $order
				LIMIT $limit OFFSET $offset";
		$count = $this->querySingle($sqlCount);
		$item = $this->queryArray($sql);
		$result['total'] = $count['total'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}
	
	function getPembelianDetail($id)
	{
		$sql = "SELECT 
					P.ID as id
					,P.INVOICE_NO as invoice_no
					,MS.NAMA as supplier
					,P.TRANS_DATE as trans_date
					,P.USER_MODIFIED as user_modified
					, SUM((PD.TOTAL_QTY * PD.BUY_PRICE)) as grand_total 
					,P.REMARKS AS remarks
				FROM PEMBELIAN P
				LEFT JOIN PEMBELIAN_DETAIL PD 
					ON PD.HEADER_ID = P.ID
				LEFT JOIN MST_SUPPLIER MS 
					ON P.SUPPLIER_ID = MS.ID 
				WHERE P.ID = ?";
		$header = $this->db->query($sql, [$id]);
		if ($header->num_rows() < 1) {
			return false;
		}
		$sqlDetail = "SELECT 
						PD.ID as id
						,PD.VARIANT_NAME as variant_name
						,CONCAT(PD.AMOUNT,' ',MP.NAMA) as qty_pack
						,PD.TOTAL_QTY as qty_total
						,PD.BUY_PRICE as price
						,MB.NAMA as brand_name
						,(PD.BUY_PRICE * PD.TOTAL_QTY) as subtotal
						FROM PEMBELIAN_DETAIL PD 
					JOIN PEMBELIAN PB 
						ON PB.ID = PD.HEADER_ID
					LEFT JOIN PRODUCT_VARIANT PV 
						ON PD.VARIANT_ID = PV.ID 
					LEFT JOIN PRODUCTS P 
						ON P.ID = PV.PRODUCT_ID
					LEFT JOIN MST_BRAND MB 
						ON MB.ID = P.BRAND_ID
					LEFT JOIN MST_PACKING MP 
						ON MP.ID = PD.PACKING_ID
					WHERE PB.ID = ?";
		$detail = $this->db->query($sqlDetail, [$id])->result();
		$data = ["header" => $header->row(), "detail" => $detail];
		return $data;
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
}
