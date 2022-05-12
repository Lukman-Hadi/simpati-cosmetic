<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Acl_model extends MY_model
{
	private $table = "ACL_ROLE_MENU";
	function __construct()
	{
		parent::__construct();
	}

	function save($roleId, $toSave)
	{
		$save =  $this->deleteInsertBatch($this->table, $roleId, $toSave,'role_id');
		if($save){
			return true;
		}else{
			return false;
		}
	}
}
