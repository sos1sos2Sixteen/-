<?php
//require_once('../functions.php');
//require_once('../userModel.php');

function getNickName() {
	return null;
}

function getAccount() {
	//$user = new userModel();
	//return $user->getStuNum();
	return 123;
}

function isGraduateThisYear() {
	// $auth_info = whulib_json_decode(
	// 	post("http://202.114.65.166/aleph-x/bor/oper",
	// 		['BorForm' =>
	// 			['username'=>'byj',
	// 			'password'=>'xxzx2017byj',
	// 			'op'=>'bor-info',
	// 			'bor_id'=>getAccount(),
	// 			'op_param'=>'',
	// 			'op_param2'=>'',
	// 			'op_param3'=>'']
	// ]));
	// return intval(intval($auth_info['z305_expiry_date']) >= 20170101);
	return true;
}
