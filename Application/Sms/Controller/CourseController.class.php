<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
use Sms\Model\CourseModel;
use Sms\Model\StudentModel;
use Sms\Model\TeacherModel;
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
		//Do not contain any empty values
		//Invalid keys will be ignored
		IS_GET or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
			
		$form = M('Course');
		$query = $form->create(I('get.'));
		$res = $form->where($query)->select();		
		
		if (session('permissions')['score']) {
			$allowed_courses = $form->where(array('teacher_id'=>session('user')))->field('course_id')->select();
			$allowed_courses = array_column($allowed_courses,'course_id');
			foreach ($res as &$course) {
				if (in_array($course['course_id'],$allowed_courses))
					$course['allow_score'] = true;
				else
					$course['allow_score'] = false;
			}
		}
		$this->assign('list',$res);
		$this->display();
	}
	public function score(){
		!C('PERMISSION_CONTROL') or session('permissions')['score'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		//use course_id to find the course
		if(IS_GET){
			$form = new CourseModel();
			$res = $form->getCourseAndStudentBy(array('course_id'=>I('get.course_id')))->select();
			$this->assign('list',$res);
			$this->display();
		}
		else if(IS_POST){
			$form = new Model();
			$data = I('post.')['score'];
			foreach($data as $k=>$val) {
				if ($val === '') $val = null;
				$res = $form->table('enroll')->where(array('enroll_id'=>$k))->data(array('grades'=>$val))->save();
				if ($res === false)
					$this->error($form->getError());
			}
			$this->success("Success",'find');
		}
	}
	
	public function enroll() {
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$Cmodel = new CourseModel();
		if(IS_GET) {
			if (I('get.student_id',null) === null)
				$this->error('Student ID expected');
			$Smodel = new StudentModel();
			$profile = $Smodel->getByStudent_id(I('get.student_id'));
			$enrollable = $Cmodel->getEnrollableByStudentId(I('get.student_id'));
			
			$this->assign('studentProfile',$profile);
			$this->assign('enrollableCourses',$enrollable);
			$this->display();
			
		}
		else if (IS_POST) {
			$res = $Cmodel->validateAndInsertEnroll(I('post.'));
			if ($res === true)
				//$this->redirect('enroll',array('student_id'=>I('post.student_id')),1,'Success');
				$this->success("Success to enroll",U('enroll',array('student_id'=>I('post.student_id'))));
			else 
				$this->error($res);
		}
	}
	
	public function retreat($student_id,$course_id){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form = D('Enroll');
		$condition=array('student_id'=>$student_id,'course_id'=>$course_id);
		$res = $form->where($condition)->delete();
		if($res) {
			$this->success("Retreat successfully");
		}else
			//if $res === 0 No update because of no modified values
			$this->error(($res === 0) ? "Not modified" : $form->getError());
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
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form=new CourseModel();
		if(IS_POST){
			$condition=$form->authFindByStudent(I('post.'));
		}
		else if(IS_GET) $condition=$form->authFindByStudent(I('get.'));
		$query=$form->getCourseAndStudentBy($condition)->buildSql();
		$res=$form->table($query.'a')->select();
		$avg=$form->table($query.'a')->avg('grades');
		
		$this->assign('list',$res);
		$this->assign('avg',$avg);
		$this->display();
		
	}
	public function findCourse(){
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$data=I('post.');
		$condition=array();
		//valide the data
		$form=M('Course');
		if(@$data['name']){
			$condition['course_id']=$form->getFieldByName($data['name'],'course_id');
		}
		if(@$data['course_id']){
			$condition['course_id']=$data['course_id'];
		}
		$this->redirect('find',$condition);
	}
	
	public function findByClass(){
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		if(IS_GET){
			$this->display();
		}
		else if(IS_POST){
			$form=new CourseModel();
			$condition=$form->authFindByClass(I('post.'));
			
		}
	}
	
	public function delete($course_id) {
		//IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form = D('Course');
		$res = $form->where(array('course_id'=>$course_id))->delete();
		if($res) {
			$this->success("Deleted $course_id#");
		}else
			//if $res === 0 No update because of no modified values
			$this->error(($res === 0) ? "Not modified" : $form->getError());
	}
}