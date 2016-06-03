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
					$form=A('Student');
					$form->auth(I('post.'));
					break;
				case 'teacher':
					$form=A('Teacher');
					$form->auth(I('post.'));
					break;
				case 'admin':
					//TODO
					break;
				default:	//Should never happens
					$this->error('Unknown user type');
					break;
			}
			
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