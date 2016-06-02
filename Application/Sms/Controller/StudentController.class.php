<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class StudentController extends Controller{
	public function insert() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = D('Student');
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
		//use student_recid to identify students
		//If nothing updated, returns error
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));

		$form = D('Student');
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
	public function edit($student_recid=1){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$Form=D('Student');
		$this->assign('vo',$Form->find($student_recid));
		$this->display();
	}
	public function find($id) {
		//Do not contain any empty values
		//Invalid keys will be ignored
		IS_GET or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form = new Model();
		$inform=$form->tale('student')->getByStudent_id($id);
		$this->show(
				'id:'.$inform['student_id'].
				'<br/>name:'.$inform['name'].
				'<br/>gender:'.$inform['gender'].
				'<br/>entrance age:'.$inform['entrance_age']
				.'<br/>entrance year:'.$inform['entrance_year']
				.'<br/>class:'.$inform['class']
				);
		$enrolls=$form->table('enroll')->getByStudent_id($id);
		$this->show('<br/>Courses:');
		foreach ($enroll as $enrolls){
			$course_id=$enroll['course_id'];
			$course=$form->table('course')->getByCourse_id($course_id);
			$this->show('<br/>'.$course['name']);
		}
	}
}