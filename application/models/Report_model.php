<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Report_model extends MY_model
{
	function __construct()
	{
		parent::__construct();
	}

	function keluarMasuk($brand, $startDate, $endDate)
	{
		// $sql = "SELECT
		// 			P.ID AS id,
		// 			P.PRODUCT_CODE AS product_code,
		// 			P.NAMA as product_name,
		// 			PV.VARIANT_CODE as variant_code,
		// 			PV.NAMA as variant_name,
		// 			MB.NAMA as brand_name,
		// 			SUM(DSD.QTY_TOTAL) as qty_out,
		// 			SUM(DSD.SUBTOTAL) as total_amount_out,
		// 			SUM(PD.TOTAL_QTY) as qty_in,
		// 			SUM((PD.TOTAL_QTY * PD.BUY_PRICE)) as total_amount_in
		// 		FROM
		// 			PRODUCT_VARIANT PV
		// 		JOIN PRODUCTS P 
		// 			on P.ID = PV.PRODUCT_ID
		// 		join mst_brand mb 
		// 			on MB.ID = P.brand_id
		// 		LEFT JOIN DIRECT_SALE_DETAIL DSD 
		// 			ON	DSD.VARIANT_ID = PV.ID
		// 		LEFT JOIN DIRECT_SALE_HEADER DSH 
		// 			ON	DSD.HEADER_ID = DSH.ID
		// 		LEFT JOIN PEMBELIAN_DETAIL PD 
		// 			on PD.VARIANT_ID = PV.ID
		// 		LEFT JOIN PEMBELIAN P2 
		// 			on P2.ID = PD.HEADER_ID
		// 		WHERE
		// 			1 = 1
		// 			AND ((DSH.TRANS_DATE BETWEEN '$startDate' AND '$endDate')
		// 				OR (P2.TRANS_DATE BETWEEN '$startDate' AND '$endDate'))
		// 			AND (DSD.QTY_TOTAL > 0
		// 				OR PD.TOTAL_QTY > 0)
		// 			AND P.BRAND_ID IN ($brand)
		// 		GROUP BY
		// 			PV.ID
		// 		order by 
		// 			P.ID ASC";
		$sql = "SELECT
					id ,
					product_code ,
					product_name ,
					variant_code ,
					variant_name ,
					brand_name ,
					SUM((CASE WHEN RN_SELL = 1 THEN 1 ELSE 0 END)* QTY_TOTAL) qty_out ,
					SUM((CASE WHEN RN_SELL = 1 THEN 1 ELSE 0 END)* SUBTOTAL) total_amount_out ,
					SUM((CASE WHEN RN_BUYY = 1 THEN 1 ELSE 0 END)* TOTAL_QTY) qty_in ,
					SUM((CASE WHEN RN_BUYY = 1 THEN 1 ELSE 0 END)*(TOTAL_QTY * BUY_PRICE)) total_amount_in
				FROM
					(
					SELECT
						P.ID AS ID,
						PV.ID AS VARIANT_ID,
						P.PRODUCT_CODE AS PRODUCT_CODE,
						P.NAMA AS PRODUCT_NAME,
						PV.VARIANT_CODE AS VARIANT_CODE,
						PV.NAMA AS VARIANT_NAME,
						MB.NAMA AS BRAND_NAME,
						DSD.QTY_TOTAL,
						DSD.SUBTOTAL,
						PD.TOTAL_QTY,
						PD.BUY_PRICE,
						ROW_NUMBER() OVER (PARTITION BY DSD.ID) AS RN_SELL,
						ROW_NUMBER() OVER (PARTITION BY PD.ID) AS RN_BUYY
					FROM
						PRODUCT_VARIANT PV
					JOIN PRODUCTS P ON
						P.ID = PV.PRODUCT_ID
					JOIN MST_BRAND MB ON
						MB.ID = P.BRAND_ID
					LEFT JOIN DIRECT_SALE_DETAIL DSD ON
						DSD.VARIANT_ID = PV.ID
					LEFT JOIN DIRECT_SALE_HEADER DSH ON
						DSD.HEADER_ID = DSH.ID
					LEFT JOIN PEMBELIAN_DETAIL PD ON
						PD.VARIANT_ID = PV.ID
					LEFT JOIN PEMBELIAN P2 ON
						P2.ID = PD.HEADER_ID
					WHERE
						1 = 1
						AND P.BRAND_ID IN ($brand)
						AND ((DSH.TRANS_DATE BETWEEN STR_TO_DATE('$startDate','%Y-%m-%d') AND STR_TO_DATE('$endDate','%Y-%m-%d'))
							OR (P2.TRANS_DATE BETWEEN STR_TO_DATE('$startDate','%Y-%m-%d') AND STR_TO_DATE('$endDate','%Y-%m-%d')))
						AND (DSD.QTY_TOTAL > 0
							OR PD.TOTAL_QTY > 0)
					ORDER BY
						P.ID ASC) TES
				GROUP BY
					VARIANT_ID
				ORDER BY
					ID ASC";
		$data =  $this->queryArray($sql);
		$out = array();
		$index = 0;
		foreach ($data as $d) {
			// $out[$d['id']]['id'] = $d['id'];
			// $out[$d['id']]['product_code'] = $d['product_code'];
			// $out[$d['id']]['product_name'] = $d['product_name'];
			// $out[$d['id']]['brand_name'] = $d['brand_name'];
			// $out[$d['id']]['variant'][] = array(
			// 	'variant_code' => $d['variant_code'],
			// 	'variant_name' => $d['variant_name'],
			// 	'qty_out' => $d['qty_out'],
			// 	'total_amount_out' => $d['total_amount_out'],
			// 	'qty_in' => $d['qty_in'],
			// 	'total_amount_in' => $d['total_amount_in'],
			// );
			$key = array_search($d['id'], array_column($out, 'id'));
			if ($key !== false) {
				$out[$key]['variant'][] = array(
					'variant_code' => $d['variant_code'],
					'variant_name' => $d['variant_name'],
					'qty_out' => $d['qty_out'],
					'total_amount_out' => $d['total_amount_out'],
					'qty_in' => $d['qty_in'],
					'total_amount_in' => $d['total_amount_in'],
				);
			} else {
				$out[$index]['id'] = $d['id'];
				$out[$index]['product_code'] = $d['product_code'];
				$out[$index]['product_name'] = $d['product_name'];
				$out[$index]['brand_name'] = $d['brand_name'];
				$out[$index]['variant'][] = array(
					'variant_code' => $d['variant_code'],
					'variant_name' => $d['variant_name'],
					'qty_out' => $d['qty_out'],
					'total_amount_out' => $d['total_amount_out'],
					'qty_in' => $d['qty_in'],
					'total_amount_in' => $d['total_amount_in'],
				);
				$index++;
			}
		}
		return $out;
	}

	function daftarBarang($brand)
	{
		$sql = "SELECT product_code,product_name,brand_name,list_variant,IFNULL(PRICE,MARGIN) AS sell_value,sell_method,price_dist,base_price,limit_reminder, product_smallest_pack as packing
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
						,P.PRICE_DIST AS PRICE_DIST
						,P.BASE_PRICE AS BASE_PRICE
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
					WHERE MB.ID IN ($brand)
					GROUP BY P.ID
				) PRODUCTS";
		$data =  $this->queryArray($sql);
		return $data;
	}

	function listStockPerVariant($brand)
	{
		$sql = "SELECT
					`PS`.`ID` AS `stock_id`,
					CASE
						WHEN P.NAMA = PV.NAMA THEN PV.NAMA
						ELSE CONCAT(P.NAMA, ' - ', PV.NAMA )
					END AS nama,
					`PV`.`VARIANT_CODE` AS `variant_code`,
					`MB`.`NAMA` AS `brand_name`,
					`PS`.`TOTAL_STOCK` AS `total_stock`,
					`PS`.`BUY_PRICE` AS `buy_price`,
					`PS`.`EXPIRED_DATE` AS `expiry_date`,
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
						PPU.SORT_ORDER) pack_amount
				FROM
					`PRODUCT_VARIANT` `PV`
				JOIN `PRODUCTS` `P` ON
					`PV`.`PRODUCT_ID` = `P`.`ID`
					AND `P`.`IS_DELETED` = 0
					AND `P`.`IS_ACTIVE` = 1
				JOIN `MST_BRAND` `MB` ON
					`P`.`BRAND_ID` = `MB`.`ID`
				LEFT JOIN `PRODUCT_STOCKS` `PS` ON
					`PS`.`PRODUCT_VARIANT_ID` = `PV`.`ID`
				WHERE
					`PV`.`IS_DELETED` = 0
					AND `PV`.`IS_ACTIVE` = 1
					and MB.ID in ($brand)";
		$item = $this->queryArray($sql);
		foreach ($item as $index => $value) {
			$item[$index]["Pack"] = convertTotalToPackUnit($value["total_stock"], $value["pack_amount"]);
		}
		return $item;
	}
	function listStockPerProduct($brand)
	{
		$sql = "SELECT id,product_code,product_name,brand_name,list_variant, limit_reminder, pack_amount, total_stock 
				FROM(
					SELECT 
						P.ID AS ID
						,P.PRODUCT_CODE AS PRODUCT_CODE
						,P.NAMA AS PRODUCT_NAME
						,MB.NAMA AS BRAND_NAME
						,GROUP_CONCAT(DISTINCT PV.NAMA ORDER BY PV.NAMA ASC SEPARATOR ', ') AS LIST_VARIANT
						,CASE WHEN (UPPER(PV.NAMA) = P.NAMA) THEN PV.LIMIT_REMINDER ELSE (MIN(PV.LIMIT_REMINDER)) END AS LIMIT_REMINDER
						,SUM(PS.TOTAL_STOCK) AS TOTAL_STOCK
						,(SELECT
								GROUP_CONCAT(CONCAT(MP.AMOUNT,'.',MP.NAMA)SEPARATOR'|')
								FROM PRODUCT_PACKING_UNIT PPU 
								JOIN MST_PACKING MP ON PPU.PACKING_ID = MP.ID 
								WHERE PRODUCT_ID =P.ID ORDER BY PPU.SORT_ORDER) PACK_AMOUNT
					FROM PRODUCTS P
					LEFT JOIN PRODUCT_VARIANT PV 
						ON P.ID = PV.PRODUCT_ID
						AND PV.IS_ACTIVE = 1
						AND PV.IS_DELETED = 0
					LEFT JOIN MST_BRAND MB 
						ON MB.ID = P.BRAND_ID
						AND MB.IS_ACTIVE = 1
						AND MB.IS_DELETED = 0
					LEFT JOIN PRODUCT_STOCKS PS 
						ON PS.PRODUCT_VARIANT_ID = PV.ID
					WHERE P.IS_ACTIVE = 1 AND P.IS_DELETED = 0 AND MB.ID IN ($brand)
					GROUP BY P.ID
					) PRODUCTS";
		$item = $this->queryArray($sql);
		foreach ($item as $index => $value) {
			$item[$index]["Pack"] = convertTotalToPackUnit($value["total_stock"], $value["pack_amount"]);
		}
		return $item;
	}
	
	function getTotalJenisBarang(){
		$sql = "SELECT COUNT(1) as total FROM PRODUCTS P WHERE IS_ACTIVE = 1 AND IS_DELETED =0";
		$data = $this->querySingle($sql);
		return $data;
	}
	
	function getTotalStock(){
		$sql = "SELECT SUM(TOTAL_STOCK) as total FROM PRODUCT_STOCKS";
		$data = $this->querySingle($sql);
		return $data;
	}
	
	function getProdukTerjualRank(){
		$sql = "SELECT
					P.NAMA as product_name,
					MB.NAMA as brand_name,
					CONCAT(SUM(QTY_TOTAL), ' ',(SELECT
													MP.NAMA
												FROM
													MST_PACKING MP
												JOIN PRODUCT_PACKING_UNIT PPU ON
													MP.ID = PPU.PACKING_ID
													AND P.ID = PPU.PRODUCT_ID
												ORDER BY
													PPU.SORT_ORDER DESC
												LIMIT 1)
					) AS total
				FROM
					DIRECT_SALE_DETAIL DSD
				JOIN DIRECT_SALE_HEADER DSH ON
					DSH.ID = DSD.HEADER_ID
				LEFT JOIN PRODUCT_VARIANT PV ON
					PV.ID = DSD.VARIANT_ID
				LEFT JOIN PRODUCTS P ON
					P.ID = PV.PRODUCT_ID
				LEFT JOIN MST_BRAND MB ON
					P.BRAND_ID = MB.ID
				WHERE
					MONTH(DSH.TRANS_DATE) = MONTH(NOW())
				GROUP BY
					P.ID
				ORDER BY
					SUM(DSD.QTY_TOTAL) DESC
				LIMIT 10";
		$data = $this->queryArray($sql);
		return $data;
	}
	
	function getStockTerbanyak(){
		$sql = "SELECT
					P.NAMA as product_name,
					MB.NAMA as brand_name,
					CONCAT(SUM(PS.TOTAL_STOCK), ' ',(SELECT
													MP.NAMA
												FROM
													MST_PACKING MP
												JOIN PRODUCT_PACKING_UNIT PPU ON
													MP.ID = PPU.PACKING_ID
													AND P.ID = PPU.PRODUCT_ID
												ORDER BY
													PPU.SORT_ORDER DESC
												LIMIT 1)
					) AS total
				FROM
					product_stocks ps 
				LEFT JOIN PRODUCT_VARIANT PV ON
					PV.ID = PS.PRODUCT_VARIANT_ID
				LEFT JOIN PRODUCTS P ON
					P.ID = PV.PRODUCT_ID
				LEFT JOIN MST_BRAND MB ON
					P.BRAND_ID = MB.ID
				GROUP BY
					P.ID
				ORDER BY
					SUM(PS.TOTAL_STOCK) DESC
				LIMIT 10";
		$data = $this->queryArray($sql);
		return $data;
	}
	
	function getProdukHampirHabis(){
		$sql = "SELECT
					(CASE PV.NAMA WHEN P.NAMA THEN P.NAMA ELSE CONCAT(P.NAMA,'-',PV.NAMA) END) as product_name,
					MB.NAMA AS brand_name,
					SUM(PS.TOTAL_STOCK) AS total_qty,
					CONCAT(SUM(PS.TOTAL_STOCK), ' ',(SELECT
													MP.NAMA
												FROM
													MST_PACKING MP
												JOIN PRODUCT_PACKING_UNIT PPU ON
													MP.ID = PPU.PACKING_ID
													AND P.ID = PPU.PRODUCT_ID
												ORDER BY
													PPU.SORT_ORDER DESC
												LIMIT 1)
					) AS total,
					PV.LIMIT_REMINDER 
				FROM
					PRODUCT_STOCKS PS 
				LEFT JOIN PRODUCT_VARIANT PV ON
					PV.ID = PS.PRODUCT_VARIANT_ID
				LEFT JOIN PRODUCTS P ON
					P.ID = PV.PRODUCT_ID
				LEFT JOIN MST_BRAND MB ON
					P.BRAND_ID = MB.ID
				GROUP BY
					PV.ID
				HAVING SUM(PS.TOTAL_STOCK) <= (CASE PV.LIMIT_REMINDER WHEN 0 THEN 100 ELSE PV.LIMIT_REMINDER END)  
				ORDER BY
					SUM(PS.TOTAL_STOCK) ASC
				LIMIT 10";
		$data = $this->queryArray($sql);
		return $data;
	}
	
	function getProdukKadalarsa(){
		
	}
	
}
