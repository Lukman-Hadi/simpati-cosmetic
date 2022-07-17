<?php
// namespace App\Models\Global_model;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class MY_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->user = $this->session->userdata('username');
	}

	protected function queryArray($sql)
	{
		return $this->db->query($sql)->result_array();
	}
	protected function queryObject($sql)
	{
		return $this->db->query($sql)->result();
	}
	protected function querySingle($sql)
	{
		return $this->db->query($sql)->row_array();
	}

	protected function insertOrUpdate($table, $data)
	{
		$keys = array();
		$data = array_merge($data, ["user_modified" => $this->user]);
		$values = array();
		$keysValues = array();
		foreach ($data as $key => $value) {
			$values[] = $this->db->escape($value);
			$keys[] = $key;
			$keysValues[] = $key . " = " . $this->db->escape($value);
		}
		$values = implode(",", $values);
		$column = implode(",", $keys);
		$keysValues = implode(", ", $keysValues);
		$sql = "INSERT INTO $table ($column) VALUES ($values) ON DUPLICATE KEY UPDATE $keysValues, updated_at = now()";
		return $this->db->query($sql);
	}

	protected function insertUpdateStockBatch($table, $arrayData)
	{
		// $this->db->trans_start();
		foreach ($arrayData as $index => $data) {
			if ($data['type'] == 'plus') {
				$cek = $this->db->select('id')->from($table)->where("product_variant_id", $data["product_variant_id"])->where('expired_date', $data["expired_date"])->where('buy_price', $data["buy_price"])->get();
				if ($cek->num_rows() > 0) {
					$id = $cek->row()->id;
					$sql = "UPDATE PRODUCT_STOCKS SET TOTAL_STOCK = TOTAL_STOCK + ? WHERE ID = $id";
					$this->db->query($sql, $this->db->escape($data["total_stock"]));
					continue;
				} else {
					unset($data["type"]);
					$this->db->insert($table, $data);
					continue;
				}
			} else if ($data['type'] == 'Penyesuaian') {
				//TODO CHECK IS THIS CONDITION NECESARY ?
				// $cek = $this->db->select('id')->from("PRODUCT_STOCKS")->where("product_variant_id", $data["product_variant_id"])->where('expired_date', $data["expired_date"])->where('buy_price', $data["buy_price"])->get();
				// if ($cek->num_rows() > 0) {
				// 	$id = $cek->row()->id;
				// }else{
				// }
				$id = $data["stock_id"];
				unset($data["stock_id"]);
				unset($data["type"]);
				$this->db->update("PRODUCT_STOCKS", $data, ["id" => $id]);
				continue;
			} else if ($data['type'] == 'Penghapusan') {
				$id = $data["stock_id"];
				unset($data["stock_id"]);
				unset($data["type"]);
				$this->db->delete("PRODUCT_STOCKS", ["id" => $id]);
				continue;
			} else {
				$keys = array();
				// $data = array_merge($data, ["user_modified" => $this->user]);
				$values = array();
				$keysValues = array();
				foreach ($data as $key => $value) {
					$values[] = $this->db->escape($value);
					$keys[] = $key;
					$keysValues[] = $key . " = " . $this->db->escape($value);
				}
				$values = implode(",", $values);
				$column = implode(",", $keys);
				$keysValues = implode(", ", $keysValues);
				$sql = "INSERT INTO $table ($column) VALUES ($values) ON DUPLICATE KEY UPDATE $keysValues";
				$this->db->query($sql);
			}
		}
		// $this->db->trans_complete();
		// if ($this->db->trans_status() === FALSE) {
		// 	return false;
		// } else {
		// 	return true;
		// }
	}

	protected function insertDetailAndBeforeStock($id, $detail)
	{
		foreach ($detail as $key => $value) {
			$value["stock_adjustment_id"] = $id;
			$this->db->insert("STOCK_ADJUSTMENT_DETAIL", $value);
			$detailId = $this->db->insert_id();
			$stockId = $value["stock_id"];
			$sql = "INSERT INTO STOCK_ADJUSTMENT_DETAIL_BEFORE (STOCK_ADJUSTMENT_DETAIL_ID,TOTAL_STOCK,BUY_PRICE,EXPIRED_DATE) 
					SELECT $detailId AS STOCK_ADJUSTMENT_DETAIL_ID,TOTAL_STOCK,BUY_PRICE,EXPIRED_DATE FROM PRODUCT_STOCKS WHERE ID = $stockId";
			$this->db->query($sql);
		}
	}

	protected function softDelete($table, $id)
	{
		$this->db->where_in('id', $id);
		return $this->db->update($table, ["is_deleted" => 1, "user_modified" => $this->user, "updated_at" => date('Y-m-d H:i:s')]);
	}
	
	protected function softDeleteChild($table, $key,$id)
	{
		$this->db->where_in($key, $id);
		return $this->db->update($table, ["is_deleted" => 1, "user_modified" => $this->user, "updated_at" => date('Y-m-d H:i:s')]);
	}

	protected function setIsActive($table, $id)
	{
		$sql = "UPDATE $table SET is_active = (CASE WHEN (SELECT is_active FROM $table WHERE id = $id)=1 then 0 else 1 end), user_modified = '$this->user', updated_at = now() where id = $id";
		return $this->db->query($sql);
	}

	protected function deleteInsertBatch($table, $id, $data, $column = "user_id")
	{
		$this->db->delete($table, [$column => $id]);
		return $this->db->insert_batch($table, $data);
	}
}
