<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends MY_model
{
	private $table = "MST_MENU";
    function __construct()
    {
        parent::__construct();
    }
	//to controller
    function getAll()
    {
    	$offset = $this->input->get('offset')!=null ? intval($this->input->get('offset')) : 0;
        $limit = $this->input->get('limit')!=null ? intval($this->input->get('limit')) : 20;
        $sort = $this->input->get('sort')!=null ? strval($this->input->get('sort')) : 'created_at';
        $order = $this->input->get('order')!=null ? strval($this->input->get('order')) : 'DESC';
        $search = $this->input->get('search')!=null ? strval($this->input->get('search')) : '';

        $this->db->select('id');
        $this->db->from($this->table);
		$this->db->where("is_deleted",0);
		$this->db->where_not_in("id",0);
        if($this->input->get('search')){
        	$this->db->like('nama',$search,'both');
        }
        $result['total'] = $this->db->get()->num_rows();
		
		$this->db->select('mm.id,mm.nama,mm.link,mm.icon,(SELECT M.nama FROM MST_MENU M WHERE M.id = mm.parent_id) as main,mm.is_active,mm.ordinal');
        $this->db->from("mst_menu mm");
		$this->db->where("is_deleted",0);
		$this->db->where_not_in("id",0);
        if($this->input->get('search')){
        	$this->db->like('nama',$search,'both');
        }
        $this->db->order_by($sort,$order);
        $this->db->limit($limit,$offset);
        $query=$this->db->get();
        $item = $query->result_array();    
        $result = array_merge($result, ['rows' => $item]);
        return $result;
    }
	function getListMenu(){
		$offset = $this->input->get('page')!=null ? intval($this->input->get('page')) : 0;
        $limit = $this->input->get('limit')!=null ? intval($this->input->get('limit')) : 10;
        $search = $this->input->get('q')!=null ? strval($this->input->get('q')) : '';
        $id = $this->input->get('id')!=null ? strval($this->input->get('id')) : '';
		
		if($offset>0){
			$offset -= 1;
		}
		$this->db->select('id,nama as text')
					->from($this->table)
					// ->where('PARENT_ID',0)
					->where('IS_DELETED',0)
					->where('IS_ACTIVE',1);
		if($this->input->get('q')){
			$this->db->like('nama',$search,'both');
		}
		$result['total'] = $this->db->get()->num_rows();
		$this->db->select('id,nama as text')
					->from($this->table)
					// ->where('PARENT_ID',0)
					->where('IS_DELETED',0)
					->where('IS_ACTIVE',1);
		if($this->input->get('q')){
			$this->db->like('nama',$search,'both');
		}
		if($this->input->get('id')=='0'||$this->input->get('id')){
			$this->db->where('id',$id);
		}
		$this->db->limit($limit,($offset*$limit));
		$query = $this->db->get()->result_array();
		$result = array_merge($result, ['items' => $query]);
		return $result;
	}
	function saveMenu($data){
		return $this->insertOrUpdate($this->table,$data);
	}
	function isExist($key){
		$count = $this->db->select("ID")
					->from($this->table)
					->where("ID",$key)
					->get()
					->num_rows();
		if($count>0)return true;
		return false;
	}
	function getMenuById($id){
		$result = array();
		if(is_null($id)||$id ==0){
			$result = array(
				"status" 	=> FALSE,
				"message" 	=> MUST_PROVIDED,
				"data"		=> null
			);
			return $result;
		}
		$sql = "SELECT m.*, (SELECT nama FROM MST_MENU WHERE id = m.parent_id) parent_name FROM MST_MENU m WHERE id = $id";
		// $data = $this->db->select("*")->where('id',$id)->get('MST_MENU');
		$data = $this->db->query($sql);
		if($data->num_rows()==1){
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
	function deleteMenu($id){
		$query =  $this->softDelete($this->table,$id);
		$result = array();
		if($query){
			$result = ["status"=>TRUE,"message"=>DELETE_SUCCESS];
		}else{
			$result = ["status"=>FALSE,"message"=>GENERAL_ERROR];
		}
		return $result;
	}
	function setActive($id){
		$query = $this->setIsActive($this->table,$id);
		if($query){
			$result = ["status"=>TRUE,"message"=>UPDATE_SUCCESS];
		}else{
			$result = ["status"=>FALSE,"message"=>GENERAL_ERROR];
		}
		return $result;
	}
	function getSubMenus($id,$is_main)
    {
    	// $this->db->from('tbl_menus');
    	// $this->db->where('id_main',$is_main);
    	// return $this->db->get();
        $this->db->from('tbl_menus');
    	$this->db->join('tbl_levels','tbl_menus._id=tbl_levels.id_menu');
        $this->db->where('status',1);
    	$this->db->where('tbl_levels.id_jabatan',$id);
    	$this->db->where('tbl_menus.id_main',$is_main);
    	return $this->db->get();
    }
	//to view
	function getMenus(){
		// $sql = "SELECT id,nama AS title, icon, link, parent_id, ordinal FROM mst_menu WHERE id <> 0 AND is_active = 1 AND is_deleted = 0";
		$sql = "SELECT id,nama AS title, icon, link, parent_id, ordinal FROM mst_menu WHERE id <> 0 AND is_active = 1 AND is_deleted = 0 order by ordinal,parent_id asc;";
		return $this->queryArray($sql);
	}
	
	function menuControlList($roleId){
		$sql = "SELECT menu.id, CONCAT(pm.nama, ' > ', menu.nama) AS title, arm.menu_id AS access FROM $this->table menu
				LEFT JOIN $this->table pm ON pm.id = menu.parent_id
				LEFT JOIN acl_role_menu arm on arm.menu_id = menu.id AND arm.role_id = $roleId
				WHERE menu.is_active =1 AND menu.is_deleted = 0
				AND menu.id NOT IN (
					SELECT distinct mc.id FROM $this->table mp
					LEFT JOIN $this->table mc ON mp.id = mc.parent_id
					LEFT JOIN $this->table mgc ON mc.id = mgc.parent_id
					WHERE mgc.is_active = 1 AND mgc.is_deleted =0
				)
				ORDER BY pm.nama,menu.ordinal ASC";
		return $this->queryObject($sql);
	}
	
	function getAllMenuControl(){
		$sql = "SELECT mm.id, mm.nama, arm.menu_id as access, mm.link, mm.parent_id, mm.ordinal FROM mst_menu mm
				left join acl_role_menu arm on mm.id = arm.menu_id and arm.role_id = 1
				where mm.is_deleted = 0
				and mm.id <> 0
				order by mm.ordinal,mm.parent_id asc";
		return $this->queryArray($sql);
	}
	
	function getMenuByRole($roleId){
		$sql = "SELECT DISTINCT id, nama AS title, parent_id,link, parent_id, ordinal FROM (
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
	
	function testAlfin($tgl){
		$sql = "select *,(select count(*) 
					from penggunaan_mesin 
					where id_mesin = mm.id
					and tanggal = ?) jumlah 
				from mst_mesin mm";
		return $this->db->query($sql,[$tgl])->result();
	}
	
	
	
}
