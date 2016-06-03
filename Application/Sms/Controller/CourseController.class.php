<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class CourseController extends Controller{
	public function insert() {
			IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
			!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
	
			$form = D('Course');
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
	
			$form = D('Course');
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
	public function edit($course_recid=1){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form=D('Course');
		$this->assign('vo',$form->find($course_recid));
		$form = new Model();
		$this->assign('teacher_list',$form->table('teacher')->field('teacher_id,name')->select());
		$this->display();
	}
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		IS_GET or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
			
		$form = M('Course');
		$query = $form->create(I('get.'));
		$res = $form->where($query)->select();		
		$this->assign('list',$res);
		$this->display();
	}
	public function score(){
		!C('PERMISSION_CONTROL') or session('permissions')['score'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		//use course_id to find the course
		if(IS_GET){
			$form=new Model();
			$query['course_id']=I('get.course_id');
			$res = $form->table('enroll')->where($query)->select();
			$this->assign('list',$res);
			$this->display();
		}
		else if(IS_POST){
			//TODO
		}
	}
	public function enroll(){
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		if(IS_GET){
			if(session('type')=="student"){
				$form=new Model();
				$query['course_id']=I('get.');
				$course=$form->table('course')->where($query)->find();
				$this->assign('course',$course);
				$this->display();
			}
			else $this->error("You are not student!");
		}
		else if(IS_POST){
			$form=D('Student');
			$query['student_id']=I('post.student_id');
			$student=$form->where($query)->find();
			$grade=date('Y')-$student['entrance_year'];
			if(I('post.allowed_grade')<=$grade&&I('post.cancel_grade')>$grade){
				$query['course_id']=I('post.course_id');
				$query['enroll_year']=I('post.enroll_year');
				$enroll=M('enroll');//may change to the D method and add the model
				$data = $enroll->create(I('post.'),Model::MODEL_INSERT);
				if($data){
					$res=$enroll->add($res);
					if($res) {
						$this->success("New record $res#");
					}
					else
						$this->error($form->getError());
					}
					else
						$this->error($form->getError());				
			}
			else $this->error("Your grade is not allowed to enroll this course.");
		}
	}
}