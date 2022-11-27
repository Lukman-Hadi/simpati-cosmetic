<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Export_model extends MY_model
{

	function __construct()
	{
		parent::__construct();
		$this->user = $this->session->userdata('username');
	}

	function exportBarang()
	{
		$sql = "SELECT product_code,product_name,brand_name,list_variant,harga,limit_reminder, product_smallest_pack as packing
					FROM(
						SELECT P.PRODUCT_CODE AS PRODUCT_CODE
							,P.NAMA AS PRODUCT_NAME
							,MB.NAMA AS BRAND_NAME
							,CASE WHEN (P.PRICE IS NOT NULL) THEN concat('Rp. ',FORMAT(P.PRICE,0)) ELSE concat(p.margin,' %') END AS HARGA
							,P.BARCODE AS BARCODE
							,case when pv.nama <> p.nama then GROUP_CONCAT(DISTINCT PV.NAMA ORDER BY PV.NAMA ASC SEPARATOR ', ') else '-' end AS LIST_VARIANT
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
					) PRODUCTS";
		return $this->queryObject($sql);
	}
	
	function getBrand(){
		return $this->db->select('id,nama')->from('mst_brand')->where('is_deleted',0)->where('is_active',1)->get()->result();
	}
	
	function getListVariant(){
		$sql = "SELECT
					PV.ID AS id,
					PV.VARIANT_CODE as variant_code, 
					upper(CONCAT(P.NAMA , (CASE WHEN P.NAMA <> PV.NAMA THEN CONCAT(' - ', PV.NAMA) ELSE '' END))) AS product,
					MB.NAMA as brand_name,
					P.BASE_PRICE as base_price
				FROM
					PRODUCT_VARIANT PV
				JOIN PRODUCTS P ON
					P.ID = PV.PRODUCT_ID
					AND P.IS_ACTIVE = 1
					AND P.IS_DELETED = 0
				join mst_brand mb 
					on MB.ID = P.brand_id 
				WHERE
					PV.IS_ACTIVE = 1
					AND PV.IS_DELETED = 0";
		return $this->queryObject($sql);
	}
}
