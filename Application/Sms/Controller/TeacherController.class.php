<?php
namespace Sms\Controller;
use Think\Controller;

class TeacherController extends Controller{
	public function insert() {
		if (IS_POST) {
			$form = D('teacher');
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
		//use teacher_recid to identify students
		//If nothing updated, returns error
		if (IS_POST) {
			$form = D('teacher');
			$data = $form->create();
			var_dump($data);
			if ($data) {
				$res = $form->save($data);
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
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		if (!IS_GET)
			$this->error('Invalid method');
		
		$form = M('teacher');
		$query = $form->create(I('get.'));
		//var_dump($query);
		$res = $form->where($query)->select();
		var_dump($res); // show the result
	}
}