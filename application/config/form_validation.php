<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config = array(
	'menu' => array(
		array(
			'field' => 'nama',
			'label' => 'Nama',
			'rules'	=> 'required'
		),
		array(
			'field' => 'link',
			'label' => 'Link',
			'rules'	=> 'required'
		),
		array(
			'field' => 'icon',
			'label' => 'Icon',
			'rules'	=> 'required'
		),
		array(
			'field' => 'ordinal',
			'label' => 'Ordinal',
			'rules'	=> 'required|numeric'
		),
		array(
			'field' => 'parent_id',
			'label' => 'Main',
			'rules'	=> 'required|callback_isMenuExist',
			'errors' => array(
				'isMenuExist' => VALIDATION_MENU_PARENT_ID,
			),
		),
	),
	'role' => array(
		array(
			'field' => 'nama',
			'label' => 'Nama',
			'rules'	=> 'required'
		),
		array(
			'field' => 'description',
			'label' => 'Description',
			'rules'	=> 'required'
		),
	),
	'user_add' => array(
		array(
			'field' => 'nama',
			'label' => 'Nama',
			'rules'	=> 'required'
		),
		array(
			'field' => 'username',
			'label' => 'Username',
			'rules'	=> 'required|callback_isUsernameExist',
			'errors' => array(
				'isUsernameExist' => VALIDATION_USERNAME_IS_EXIST,
			),
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules'	=> 'required|matches[password_confirmation]'
		),
		array(
			'field' => 'password_confirmation',
			'label' => 'Konfirmasi Password',
			'rules'	=> 'required|matches[password]'
		),
		array(
			'field' => 'chechboxField',
			'label' => 'Role',
			'rules' => 'callback_validationRole'
		)
	),
	'user_update' => array(
		array(
			'field' => 'nama',
			'label' => 'Nama',
			'rules'	=> 'required'
		),
		array(
			'field' => 'chechboxField',
			'label' => 'Role',
			'rules' => 'callback_validationRole'
		),
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules'	=> 'matches[password_confirmation]'
		),
		array(
			'field' => 'password_confirmation',
			'label' => 'Konfirmasi Password',
			'rules'	=> 'matches[password]'
		)
	),
	'packing_add' => array(
		array(
			'field' => 'nama',
			'label' => 'Nama',
			'rules'	=> 'required'
		),
		array(
			'field' => 'unit',
			'label' => 'Unit',
			'rules' => 'required|callback_isPackingExist',
			'errors' => array(
				'isPackingExist' => VALIDATION_CODE_IS_EXIST,
			),
		),
		array(
			'field' => 'amount',
			'label' => 'Jumlah per kemasan',
			'rules'	=> 'required|numeric'
		)
	),
	'packing_update' => array(
		array(
			'field' => 'nama',
			'label' => 'Nama',
			'rules'	=> 'required'
		),
		array(
			'field' => 'amount',
			'label' => 'Jumlah per kemasan',
			'rules'	=> 'required|numeric'
		)
	)
);
