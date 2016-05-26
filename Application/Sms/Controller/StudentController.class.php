<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class StudentController extends Controller{
	protected function modify($type){
		$Form = D('student');
		$data = $Form->create(I('post.'),$type);
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
	public function insert(){
		$this->modify(Model::MODEL_INSERT);
	}
	public function edit($student_id=0){
		$Form=D('student');
		$this->assign('vo',$Form->find($student_id));
		$this->display();
	}
	public function update(){
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