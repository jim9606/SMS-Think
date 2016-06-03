<?php
namespace Sms\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function auth() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		
		$res = authUser(I('post.user'),I('post.password'));
		if ($res) {
			switch (session('type')) {
				case 'student':
				case 'teacher':					
				case 'admin':
					break;
				default:	//Should never happens
					$this->error('Unknown user type');
					break;
			}
			$user=A('User');
			$user->utility(I('post.'));
		}
		else
			$this->error($res);
	}
	public function logout() {
		session(null);
		//TODO: jump to login page
		$this->success('You have logged out');
	}
	
}