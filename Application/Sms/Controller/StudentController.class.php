<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class StudentController extends Controller{
	protected function modify($type){
		$form = D('student');
		$data = $form->create();
		var_dump($data);
		if($data) {
			if ($type == Model::MODEL_INSERT)
				$res = $form->add();
			else 
				$res = $form->save();
			if($res) {
				$this->success($res);
			}else{
				$this->error($form->getError());
			}
		}else{
			$this->error($form->getError());
		}
	}
	public function insert() {
		$this->modify(Model::MODEL_INSERT);
	}
	public function update() {
		$this->modify(Model::MODEL_UPDATE);
	}
	public function find() {
		$Form = D('student');
		$Form->create();
		$res = $Form->find();
		var_dump($Form->buildSql());
		var_dump($res);
	}
}