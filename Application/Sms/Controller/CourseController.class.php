<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class CourseController extends Controller{
public function insert() {
		if (IS_POST) {
			$form = D('course');
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
			$form = D('course');
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
	public function edit($course_recid=1){
		$Form=D('course');
		$this->assign('vo',$Form->find($course_recid));
		$this->display();
	}
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		if (!IS_GET)
			$this->error('Invalid method');
		
		$form = M('course');
		$query = $form->create(I('get.'));
		//var_dump($query);
		$res = $form->where($query)->select();
		var_dump($res); // show the result
	}
}