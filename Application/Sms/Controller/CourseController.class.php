<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
use Sms\Model\CourseModel;
class CourseController extends Controller{
	public function insert() {
			IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
			!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
	
			$form = D('Course');
			$data = $form->create(I('post.'),Model::MODEL_INSERT);
			$error2 = $form->validateCourse($data);
			if ($error2 !== true)
				$this->error($error2);
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
			$error2 = $form->validateCourse($data);
			if ($error2 !== true)
				$this->error($error2);
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
	public function edit(){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$course_recid=I('get.course_recid');
		$form=D('Course');
		$this->assign('vo',$form->find($course_recid));
		$form = new Model();
		$this->assign('teacher_list',$form->table('teacher')->field('teacher_id,name')->select());
		$this->display();
	}
	public function find() {
		//var_dump(I('get.'));
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
		//TODO: Totally rewrite this
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		if(IS_GET){
			$form=new Model();
			$course=$form->table('course')->getByCourse_id(I('get.course_id'));
			$this->assign('course',$course);
			$this->display();
		}
		else if(IS_POST){
			$form=D('Student');
			$query['student_id']=I('post.student_id');
			$student=$form->where($query)->find();
			$grade=date('Y')-$student['entrance_year'];
			if(I('post.allowed_grade')<=$grade&&I('post.cancel_grade')>$grade){
				$enroll=M('enroll');//may change to the D method and add the model
				$data = $enroll->create(array(
						'course_id'=>I('post.course_id'),
						'enroll_year'=>I('post.enroll_year'),
						'student_id'=>I('post.student_id')						
				));
				if($data){
					$res=$enroll->add();
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
	/**
	 *show but cannot change the search results
	 */
	public function findByTeacher(){
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		if(IS_GET){
			$this->display();
		}
		else if(IS_POST){
			$form=new CourseModel();
			$condition=$form->authFindByTeacher(I('post.'));
			$this->redirect('find',$condition);
		}
	}
	
	public function findByStudentDisplay(){
		IS_POST or !C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form=new CourseModel();
		$condition=$form->authFindByStudent(I('post.'));
		$res=$form->getCourseAndStudentBy($condition);
		//var_dump($res);	
		
		$this->assign('list',$res);
		$this->display();
		
	}
}