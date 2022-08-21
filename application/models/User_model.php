<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_model extends MY_model
{
	private $table = "MST_USERS";
	private $tableUserRole = "USER_ROLE";
	private $tableUserBrand = "USER_BRAND";
	function __construct()
	{
		parent::__construct();
	}
	//to controller
	function getAll()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'created_at';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';

		$sqlCount = "SELECT count(1) AS total FROM MST_USERS MU 
					JOIN user_role ur
						ON ur.user_id = mu.id
					JOIN mst_role mr
						ON mr.id = ur.role_id
						AND mr.is_active = 1
						AND mr.is_deleted = 0
						AND mr.id <> 1 
					WHERE mu.IS_DELETED = 0";
		$sql = "SELECT
					id,
					nama,
					username,
					user_role,
					IFNULL(user_brand,'-') AS user_brand,
					is_active
				FROM
					(SELECT
						mu.id AS id,
						mu.nama AS nama,
						mu.username AS username,
						mu.is_active AS is_active,
						GROUP_CONCAT(DISTINCT mr.nama ORDER BY mr.nama ASC SEPARATOR ', ') AS user_role,
						GROUP_CONCAT(DISTINCT mb.nama ORDER BY mb.nama ASC SEPARATOR ', ') AS user_brand,
						mu.created_at AS created_at
					FROM
						$this->table mu
					JOIN user_role ur
						ON ur.user_id = mu.id
					JOIN mst_role mr
						ON mr.id = ur.role_id
						AND mr.is_active = 1
						AND mr.is_deleted = 0
						AND mr.id <> 1
					LEFT JOIN user_brand ub
						ON ub.user_id = mu.id
					LEFT JOIN mst_brand mb
						ON mb.id = ub.brand_id
						AND mb.is_active = 1
						AND mb.is_deleted = 0
					WHERE mu.is_deleted = 0
					GROUP BY
						mu.id) users
				WHERE UPPER(users.nama) LIKE UPPER('%$search%')
				OR UPPER(users.username) LIKE UPPER('%$search%')
				OR UPPER(users.user_role) LIKE UPPER('%$search%')
				OR UPPER(users.user_brand) LIKE UPPER('%$search%')
				ORDER BY $sort $order
				LIMIT $limit OFFSET $offset";
		$count = $this->querySingle($sqlCount);
		$item = $this->queryArray($sql);
		$result['total'] = $count['total'];
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}
	function save($data, $role, $brand)
	{
		$this->db->trans_begin();
		$this->insertOrUpdate($this->table, $data);
		if (!isset($data["id"])) {
			$id = $this->db->insert_id();
		} else {
			$id = $data["id"];
		}
		$roleData = [];
		$brandData = [];
		foreach ($role as $r) {
			$roleData[] = array(
				"user_id" => $id,
				"role_id" => $r
			);
		};
		$this->deleteInsertBatch($this->tableUserRole, $id, $roleData);
		if ($brand) {
			foreach ($brand as $b) {
				$brandData[] = array(
					"user_id" => $id,
					"brand_id" => $b
				);
			}
			$this->deleteInsertBatch($this->tableUserBrand, $id, $brandData);
		} else {
			$this->db->delete($this->tableUserBrand, ["user_id" => $id]);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
	function isExist($key)
	{
		$count = $this->db->select("USERNAME")
			->from($this->table)
			->where("USERNAME", $key)
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
		$user = $this->db->select("id,nama,username")
			->from($this->table)
			->where("id", $id)
			->get();
		if ($user->num_rows() == 1) {
			$data = $user->row_array();
			$role = $this->db->select("role_id")
				->from($this->tableUserRole)
				->where("user_id", $id)
				->get();
			$brand = $this->db->select("brand_id")
				->from($this->tableUserBrand)
				->where("user_id", $id)
				->get();
			$roleId = [];
			$brandId = [];
			foreach ($role->result_array() as $r) {
				$roleId[] = $r['role_id'];
			}
			foreach ($brand->result_array() as $b) {
				$brandId[] = $b['brand_id'];
			}
			$data["role"] = $roleId;
			$data["brand"] = $brandId;
			$result = array(
				"status" 	=> TRUE,
				"message" 	=> SUCCESS_GET_DATA,
				"data"		=> $data
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
}
