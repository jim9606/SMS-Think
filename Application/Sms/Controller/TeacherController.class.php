<?php
namespace Sms\Controller;
use Think\Controller;
class TeacherController extends Controller{
public function insert() {
		if (!IS_POST)
			$this->error('Invalid method');
		
		$form = D('teacher');
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
		if (!IS_POST) 
			$this->error('Invalid method');
		
		$form = D('teacher');
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
		$Form=D('teacher');
		$this->assign('vo',$Form->find($teacher_recid));
		$this->display();
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