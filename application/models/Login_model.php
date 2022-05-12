<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Login_model extends MY_model
{

	function __construct()
	{
		parent::__construct();
	}

	function getLogin($username)
	{
		$this->db->select("id,username,nama,password,is_active");
		$this->db->where("username", $username);
		$this->db->where("is_deleted", 0);
		return $this->db->get("MST_USERS");
	}
	function getUserData($userId)
	{
		$sqlUser = "SELECT id,username,nama FROM  MST_USERS WHERE id = $userId AND is_deleted = 0 AND is_active = 0";
		$sqlMenu = "";
		$data = $this->db->select("id,username,nama")
			->from("MST_USERS")
			->where("id", $userId)
			->where("is_deleted", 0)
			->where("is_active", 1)
			->get()
			->result();
		return $data;
	}
	function getMenu($roleId)
	{
		$roleId = implode(",",$roleId);
		$sql = "SELECT DISTINCT id, nama AS title, parent_id,link, parent_id, ordinal,icon FROM (
					SELECT m.id,m.nama,m.parent_id, m.icon, m.link, m.ordinal FROM mst_menu m WHERE id IN (SELECT menu_id FROM acl_role_menu WHERE role_id in ($roleId)) AND m.is_active =1 AND m.is_deleted =0
					UNION ALL
					SELECT mp.id, mp.nama, mp.parent_id,mp.icon, mp.link, mp.ordinal
					FROM mst_menu mp
					JOIN mst_menu mc ON mp.id = mc.parent_id
					WHERE mc.id in (SELECT menu_id FROM acl_role_menu WHERE role_id in ($roleId))
					AND mp.is_active =1 AND mp.is_deleted =0
					UNION ALL 
					SELECT mp.id, mp.nama, mp.parent_id,mp.icon, mp.link, mp.ordinal
					FROM mst_menu mp
					JOIN mst_menu mc ON mp.id = mc.parent_id
					JOIN mst_menu mgc ON mc.id = mgc.parent_id
					WHERE mgc.id IN (SELECT menu_id FROM acl_role_menu WHERE role_id in ($roleId))
					AND mp.is_active =1 AND mp.is_deleted =0
				) test
				WHERE id <> 0
				ORDER BY ordinal,parent_id ASC";
		return $this->queryArray($sql);
	}
	function getBrand($userId)
	{
	}
	function getRole($userId)
	{
		$this->db->select("role_id");
		$this->db->where("user_id", $userId);
		$data =  $this->db->get("USER_ROLE")->result();
		$role = [];
		foreach ($data as $d) {
			$role[] = $d->role_id;
		}
		return $role;
	}
}
