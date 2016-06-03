<?php
namespace Sms\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function edit(){
		$form = M('user');
		$query = $form->create(I('get.'));
		$user=$form->where($query)->select();
		$this->assign('vo',$user);
		$this->display();
	}
	public function update() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
	
		$form = D('user');
		$data = $form->create(I('post.'),Model::MODEL_UPDATE);
		if ($data) {
			$res = $form->save($data);
			var_dump($res);
			if($res) {
				$this->success("Record updated");
			}else
				//if $res === 0 No update because of no modified values
				$this->error(($res === 0) ? "Not modified" : $form->getError());
		}
		else
			$this->error($form->getError());
	}
	
}