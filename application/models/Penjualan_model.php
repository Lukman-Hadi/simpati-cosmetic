<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Penjualan_model extends MY_model
{
	// private $table = "MST_ROLE";
	function __construct()
	{
		parent::__construct();
	}

	function cekStock($variantId, $total)
	{
		$cek = true;
		$sql = "SELECT SUM(TOTAL_STOCK) AS total FROM PRODUCT_STOCKS PS WHERE PRODUCT_VARIANT_ID = ?";
		$totalStockDb = $this->db->query($sql, [$variantId])->row_array();
		if ($totalStockDb["total"] < $total) {
			$cek =  false;
		}
		return $cek;
	}

	function savePenjualan($header, $detail)
	{
		$this->db->trans_start();
		$this->insertOrUpdate("direct_sale_header", $header);
		$id = $this->db->insert_id();
		for ($i = 0; $i < count($detail); $i++) {
			$this->adjustStock($detail[$i]);
			$detail[$i]["header_id"] = $id;
		}
		$this->db->insert_batch("direct_sale_detail", $detail);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	function adjustStock($value, $qtyTotal = 0)
	{
		if ($qtyTotal == 0) $qtyTotal = $value["qty_total"];
		$currStock = $this->db->select("*")->from("product_stocks")->where("product_variant_id", $value["variant_id"])->order_by("id")->limit(1, 0)->get()->row();
		if ($currStock->total_stock < $qtyTotal) {
			$qtyTotal -= $currStock->total_stock;
			$this->db->delete("product_stocks", ["id" => $currStock->id]);
			$this->adjustStock($value, $qtyTotal);
		} else if ($currStock->total_stock > $qtyTotal) {
			$remain = $currStock->total_stock - $qtyTotal;
			$this->db->update("product_stocks", ["total_stock" => $remain], ["id" => $currStock->id]);
		} else if ($currStock->total_stock == $qtyTotal) {
			$this->db->delete("product_stocks", ["id" => $currStock->id]);
		}
	}

	function getRef($month, $year)
	{
		$sql = "SELECT COUNT(1)+1 as NUM FROM direct_sale_header WHERE MONTH(CREATED_AT) = $month and YEAR(CREATED_AT) = $year";
		$count =  $this->querySingle($sql);
		return $count["NUM"];
	}

	function getListPenjualan()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->db->escape($this->input->get('sort'))) : 'DSH.CREATED_AT';
		$order = $this->input->get('order') != null ? strval($this->db->escape($this->input->get('order'))) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';

		$sqlCount = "SELECT count(1) AS total 
					FROM DIRECT_SALE_HEADER DSH
					LEFT JOIN MST_CUSTOMER MC ON MC.ID = DSH.CUSTOMER_ID
					WHERE DSH.IS_DELETED = 0 
					AND (UPPER(DSH.INVOICE_NO) LIKE UPPER('%$search%')
					OR UPPER(MC.NAMA) LIKE UPPER('%$search%')
					OR UPPER(DSH.TRANS_DATE) LIKE UPPER('%$search%')
					OR UPPER(DSH.USER_MODIFIED) LIKE UPPER('%$search%'))";
		$sql = "SELECT 
					DSH.ID as id
					,DSH.INVOICE_NO as invoice_no
					,DSH.GRAND_TOTAL  as grand_total 
					,DSH.REMARKS as remarks 
					,DSH.TRANS_DATE as trans_date 
					,DSH.USER_MODIFIED  as user_modified 
					,MC.NAMA AS customer
				FROM DIRECT_SALE_HEADER DSH
				LEFT JOIN MST_CUSTOMER MC ON MC.ID = DSH.CUSTOMER_ID
				WHERE DSH.IS_DELETED = 0 
				AND (UPPER(DSH.INVOICE_NO) LIKE UPPER('%$search%')
				OR UPPER(MC.NAMA) LIKE UPPER('%$search%')
				OR UPPER(DSH.TRANS_DATE) LIKE UPPER('%$search%')
				OR UPPER(DSH.USER_MODIFIED) LIKE UPPER('%$search%'))
				ORDER BY $sort $order
				LIMIT $limit OFFSET $offset";
		$count = $this->querySingle($sqlCount);
		$item = $this->queryArray($sql);
		$result['total'] = $count['total'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function getPenjualanDetail($id)
	{
		$sql = "SELECT 
					DSH.ID as id
					,DSH.INVOICE_NO as invoice_no
					,DSH.GRAND_TOTAL  as grand_total 
					,DSH.REMARKS as remarks 
					,DSH.TRANS_DATE as trans_date 
					,DSH.USER_MODIFIED  as user_modified 
					,MC.NAMA AS customer
				FROM DIRECT_SALE_HEADER DSH
				LEFT JOIN MST_CUSTOMER MC ON MC.ID = DSH.CUSTOMER_ID
				WHERE DSH.ID = ?";
		$header = $this->db->query($sql, [$id]);
		if ($header->num_rows() < 1) {
			return false;
		}
		$sqlDetail = "SELECT 
							DSD.VARIANT_NAME as variant_name 
							, MB.NAMA as brand_name
							, DSD.QTY_TOTAL as qty_total
							, CONCAT(DSD.QTY_PACK,' ',MP.NAMA) AS qty_pack
							, DSD.PRICE as price
							, DSD.SUBTOTAL as subtotal
						FROM DIRECT_SALE_DETAIL DSD 
						JOIN DIRECT_SALE_HEADER DSH ON DSH.ID = DSD.HEADER_ID
						LEFT JOIN PRODUCT_VARIANT PV ON DSD.VARIANT_ID = PV.ID 
						LEFT JOIN PRODUCTS P ON P.ID = PV.PRODUCT_ID 
						LEFT JOIN MST_BRAND MB ON MB.ID = P.BRAND_ID
						LEFT JOIN MST_PACKING MP ON MP.ID = DSD.PACK_ID 
						WHERE DSD.HEADER_ID = ?";
		$detail = $this->db->query($sqlDetail, [$id])->result();
		$data = ["header" => $header->row(), "detail" => $detail];
		return $data;
	}
}
