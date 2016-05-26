<?php
namespace Sms\Controller;
use Think\Controller;
class StudentController extends Controller{
	public function insert() {
		if (IS_POST) {
			$form = D('student');
			$data = $form->create();
			//var_dump($data);
			if ($data) {
				$res = $form->add();
				if($res) {
					$this->success("New record $res#");
				}else
					$this->error($form->getError());
			}
			else 
				$this->error($form->getError());
		}
		else {
			//TODO: show insert page
		}
	}
	public function update() {
		//use student_recid to identify students
		//If nothing updated, returns error
		if (IS_POST) {
			$form = D('student');
			$data = $form->create();
			if ($data) {
				$res = $form->save();
				if($res) {
					$this->success("Record updated");
				}else
					$this->error($form->getError());
			}
			else 
				$this->error($form->getError());
		}
		else {
			//TODO: show update page
		}
	}
	public function edit($student_id=0){
		$Form=D('student');
		$this->assign('vo',$Form->find($student_id));
		$this->display();
	}
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		if (!IS_GET)
			$this->error('Invalid method');
		
		$form = M('student');
		$query = $form->create(I('get.'));
		//var_dump($query);
		$res = $form->where($query)->select();
		var_dump($res); // show the result
	}
}