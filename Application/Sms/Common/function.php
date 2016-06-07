<?php
use Think\Model;
use Think\Controller;

/**
 * Check if user exists
 * @param string $user
 * @return bool
 */
function checkUserExists($user) {
	$form = new Model();
	$form->table('user');
	$res = $form->where(array('user'=>$user))->count();
	return $res;
}

/**
 * Authenicate users
 * @param string $user
 * @param string $pwd
 * @return bool|string true for success or error message
 */
function authUser($user,$pwd) {
	$form = new Model();
	$res = $form->table('user')->getByUser($user);
	if ($res === null) {
		session('type','anon');
		session('permissions',C('USER_PERMISSIONS')['anon']);
		return 'User not found';
	}
	if ($res['password'] !== $pwd) {
		session('type','anon');
		session('permissions',C('USER_PERMISSIONS')['anon']);
		return 'Incorrect password';
	}
	session('type',$res['type']);
	session('permissions',C('USER_PERMISSIONS')[$res['type']]);
	session('user',$user);
	return true;
}

/**
 * 
 * @param string $type
 * @param string $user
 * @param string $pwd = C('USER_DEFAULT_PASSWORD')
 * @return string|true true if success
 */
function addUser($type,$user,$pwd = null) {
	$pwd or $pwd = C('USER_DEFAULT_PASSWORD');
	$form = new Model('user');
	$form->create(array('type'=>$type,'user'=>$user,'password'=>$pwd));
	$res = $form->add();
	if (!$res) {
		return $form->getError();
	}
	return true;
}