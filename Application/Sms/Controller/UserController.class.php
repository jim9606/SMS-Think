<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class LoginController extends Controller{
	public function passwd(){
		if(IS_GET)
		{
			$this->assign('user',session('user'));
			$this->display();
		}
		else if(IS_POST)
		{
			$form = new Model();
			$res = $form->table('user')->where(array('user'=>session('user')))->save(array('password',I('post.password')));
			if($res) $this->success("Record updated");
			else $this->error(($res === 0) ? "Not modified" : $form->getError());
		}
	}
	public function utility($user){
		$form=new Model();
		$res=$form->table('user')->where($user)->select();
		$this->assign('res',$res);
		$this->display();
	}
}