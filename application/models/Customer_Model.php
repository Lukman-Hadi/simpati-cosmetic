<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Customer_model extends MY_model
{
	private $table = "MST_CUSTOMER";
	private $tableGroup = "MST_CUSTOMER_GROUP";
	function __construct()
	{
		parent::__construct();
	}
	//to controller
	function getAllGroup()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'created_at';
		$order = $this->input->get('order') != null ? strval($this->input->get('order')) : 'DESC';
		$search = $this->input->get('search') != null ? strval($this->input->get('search')) : '';

		$this->db->select('id');
		$this->db->from($this->tableGroup);
		$this->db->where("is_deleted", 0);
		if ($this->input->get('search')) {
			$this->db->like('nama', $search, 'both');
			$this->db->like('description', $search, 'both');
		}
		$result['total'] = $this->db->get()->num_rows();

		$this->db->select('id,nama,description,is_active');
		$this->db->from($this->tableGroup);
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

	function saveGroup($data)
	{
		return $this->insertOrUpdate($this->tableGroup, $data);
	}

	function isExist($key)
	{
		$count = $this->db->select("ID")
			->from($this->tableGroup)
			->where("ID", $key)
			->get()
			->num_rows();
		if ($count > 0) return true;
		return false;
	}

	function getGroupById($id)
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
		$data = $this->db->select("id,nama,description")->where('id', $id)->where('is_deleted', 0)->get($this->tableGroup);
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

	function deleteGroup($id)
	{
		$query =  $this->softDelete($this->tableGroup, $id);
		$result = array();
		if ($query) {
			$result = ["status" => TRUE, "message" => DELETE_SUCCESS];
		} else {
			$result = ["status" => FALSE, "message" => GENERAL_ERROR];
		}
		return $result;
	}

	function setActiveGroup($id)
	{
		$query = $this->setIsActive($this->tableGroup, $id);
		if ($query) {
			$result = ["status" => TRUE, "message" => UPDATE_SUCCESS];
		} else {
			$result = ["status" => FALSE, "message" => GENERAL_ERROR];
		}
		return $result;
	}

	function getListBrand()
	{
		$data = $this->db->select('id,nama')
			->from($this->table)
			->where('is_active', 1)
			->where('is_deleted', 0)
			->get();
		if ($data->num_rows()) {
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

	function getListCustomerGroupSelect()
	{
		$offset = $this->input->get('page') != null ? intval($this->input->get('page')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 10;
		$search = $this->input->get('q') != null ? strval($this->input->get('q')) : '';
		$id = $this->input->get('id') != null ? strval($this->input->get('id')) : '';

		if ($offset > 0) {
			$offset -= 1;
		}
		$this->db->select('id,nama as text')
			->from($this->tableGroup)
			->where('IS_DELETED', 0)
			->where('IS_ACTIVE', 1);
		if ($this->input->get('q')) {
			$this->db->like('nama', $search, 'both');
		}
		$result['total'] = $this->db->get()->num_rows();
		$this->db->select('id,nama as text')
			->from($this->tableGroup)
			->where('IS_DELETED', 0)
			->where('IS_ACTIVE', 1);
		if ($this->input->get('q')) {
			$this->db->like('nama', $search, 'both');
		}
		if ($this->input->get('id') == '0' || $this->input->get('id')) {
			$this->db->where('id', $id);
		}
		$this->db->limit($limit, ($offset * $limit));
		$query = $this->db->get()->result_array();
		$result = array_merge($result, ['items' => $query]);
		return $result;
	}

	function getAll()
	{
		$offset = $this->input->get('offset') != null ? intval($this->input->get('offset')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 20;
		$sort = $this->input->get('sort') != null ? strval($this->input->get('sort')) : 'mc.created_at';
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

		$this->db->select('mc.id,mc.nama,mc.description,mc.is_active,mcg.nama as group');
		$this->db->from($this->table." mc");
		$this->db->join($this->tableGroup." mcg","mcg.id = mc.customer_group_id");
		$this->db->where("mc.is_deleted", 0);
		if ($this->input->get('search')) {
			$this->db->like('mc.nama', $search, 'both');
			$this->db->like('mc.description', $search, 'both');
			$this->db->like('mcg.nama', $search, 'both');
		}
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $offset);
		$this->db->last_query();
		$query = $this->db->get();
		$item = $query->result_array();
		$result = array_merge($result, ['rows' => $item]);
		return $result;
	}

	function save($data)
	{
		return $this->insertOrUpdate($this->table, $data);
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
		$data = $this->db->select("id,nama,description,customer_group_id as group_id")->where('id', $id)->where('is_deleted', 0)->get($this->table);
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
	
	function getListCustomerSelect()
	{
		$offset = $this->input->get('page') != null ? intval($this->input->get('page')) : 0;
		$limit = $this->input->get('limit') != null ? intval($this->input->get('limit')) : 10;
		$search = $this->input->get('q') != null ? strval($this->input->get('q')) : '';
		$id = $this->input->get('id') != null ? strval($this->input->get('id')) : '';

		if ($offset > 0) {
			$offset -= 1;
		}
		$this->db->select('id,nama as text')
			->from($this->table)
			->where('IS_DELETED', 0)
			->where('IS_ACTIVE', 1);
		if ($this->input->get('q')) {
			$this->db->like('nama', $search, 'both');
		}
		$result['total'] = $this->db->get()->num_rows();
		$this->db->select('id,nama as text')
			->from($this->table)
			->where('IS_DELETED', 0)
			->where('IS_ACTIVE', 1);
		if ($this->input->get('q')) {
			$this->db->like('nama', $search, 'both');
		}
		if ($this->input->get('id') == '0' || $this->input->get('id')) {
			$this->db->where('id', $id);
		}
		$this->db->limit($limit, ($offset * $limit));
		$query = $this->db->get()->result_array();
		$result = array_merge($result, ['items' => $query]);
		return $result;
	}
}
