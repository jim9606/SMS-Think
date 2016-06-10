<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class UserController extends Controller{
	public function utility(){
		$form=new Model();
		$res=$form->table('user')->getByUser(session('user'));
		if($res){
			$this->assign('res',$res);
			$this->display();
		}
		else{
			$this->error("No return data.");
		}
	}
	public function auth() {
		if(IS_POST){
			$res = authUser(I('post.user'),I('post.password'));
			if ($res === true) {
				$this->redirect('utility');
			}
			else
				$this->error($res);
		}
		else if(IS_GET){
			$this->display();
		}
	}
	public function logout() {
		session(null);
		//TODO: jump to login page
		$this->success('You have logged out','auth');
	}
	public function passwd(){
		if (!session('?type') or session('type') === 'anon')
			$this->error('You should log in first','auth');
		if(IS_GET)
		{
			$this->display();
		}
		else if(IS_POST)
		{
			$form = new Model();
			$res = $form->table('user')->where(array('user'=>session('user')))->save(array('password'=>I('post.password')));
			if($res) 
				$this->success('Password Changed','utility');
			else 
				$this->error(($res === 0) ? "Not modified" : $form->getError() , 'utility');
		}
	}
}