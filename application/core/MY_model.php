<?php
// namespace App\Models\Global_model;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class MY_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
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
		$user = $this->session->userdata('username');
		$keys = array();
		$data = array_merge($data, ["user_modified" => $user]);
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

	protected function softDelete($table, $id)
	{
		$user = $this->session->userdata('username');
		$this->db->where_in('id', $id);
		return $this->db->update($table, ["is_deleted" => 1, "user_modified" => $user, "updated_at" => date('Y-m-d H:i:s')]);
	}

	protected function setIsActive($table, $id)
	{
		$user = $this->session->userdata('username');
		$sql = "UPDATE $table SET is_active = (CASE WHEN (SELECT is_active FROM $table WHERE id = $id)=1 then 0 else 1 end), user_modified = '$user', updated_at = now() where id = $id";
		return $this->db->query($sql);
	}

	protected function deleteInsertBatch($table, $id, $data, $column = "user_id")
	{
		$this->db->delete($table, [$column => $id]);
		return $this->db->insert_batch($table, $data);
	}
}
