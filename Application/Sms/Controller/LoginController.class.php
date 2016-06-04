<?php
namespace Sms\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function auth() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));		
		$res = authUser(I('post.user'),I('post.password'));
		if ($res) {
			$form=A('User');
			$res=$form->utility(session('user'));
			if($res){
				$this->assign('res',$res);
				$this->display();
			}
			else{
				$this->error("No return data.");
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
	public function test(){
		echo hello;
	}
	
}