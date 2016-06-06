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

function auth() {
	if(IS_POST){
		$res = authUser(I('post.user'),I('post.password'));
		if ($res) {
			utility();
		}
		else
			$this->error($res);
	}
	else if(IS_GET){
		$this->display();
	}
}
