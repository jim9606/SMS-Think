<?php
namespace Sms\Controller;
use Think\Controller;
class TeacherController extends Controller{
public function insert() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = D('Teacher');
		$data = $form->create(I('post.'),Model::MODEL_INSERT);
		if ($data) {
			$res = $form->add($data);
			if($res) {
				$this->success("New record $res#");
			}else
				$this->error($form->getError());
		}else 
			$this->error($form->getError());
	}
	public function update() {
		//use teacher_recid to identify students
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = D('Teacher');
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
	public function edit($teacher_recid=1){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$Form=D('Teacher');
		$this->assign('vo',$Form->find($teacher_recid));
		$this->display();
	}
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		IS_GET or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = M('Teacher');
		$query = $form->create(I('get.'));
		$res = $form->where($query)->select();
		var_dump($res); // show the result
	}
}