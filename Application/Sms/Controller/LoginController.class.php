<?php
namespace Sms\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function auth() {
		if (!IS_POST)
			$this->error('Invalid method');
		
		$res = authUser(I('post.user'),I('post.password'));
		if ($res) {
			switch (session('type')) {
				case 'student':
					break;
				case 'teacher':
					break;
				case 'admin':
					break;
				default:	//Should never happens
					$this->error('Unknown user type');
			}
		}
		else
			$this->error($res);
	}
}