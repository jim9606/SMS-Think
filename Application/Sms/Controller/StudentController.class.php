<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class StudentController extends Controller{
	public function insert(){
		$Form = D('student');
		$data = $Form->create();
		var_dump($data);
		if($data) {
			if ($type == Model::MODEL_INSERT)
				$result = $Form->add();
			else
				$result = $Form->save();
			var_dump($result);
			if($result) {
				$this->success($result);
			}else{
				$this->error($Form->getError());
			}
		}else{
			$this->error($Form->getError());
		}
	}
	public function find() {
		$Form = D('student');
		$Form->create();
		$res = $Form->find();
		var_dump($Form->buildSql());
		var_dump($res);
	}
}