<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
use Sms\Model\CourseModel;
use Sms\Model\StudentModel;
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
	
	public function enroll() {
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$Cmodel = new CourseModel();
		if(IS_GET) {
			if (I('get.student_id',null) === null)
				$this->error('Student ID expected');
			$Smodel = new StudentModel();
			$profile = $Smodel->getByStudent_id(I('get.student_id'));
			$enrollable = $Cmodel->getEnrollableByStudentId(I('get.student_id'));
			//var_dump($profile);
			//var_dump($enrollable);
			$this->assign('studentProfile',$profile);
			$this->assign('enrollableCourses',$enrollable);
			$this->display();
		}
		else if (IS_POST) {
			$res = $Cmodel->validateAndInsertEnroll(I('post.'));
			if ($res === true)
				$this->redirect('enroll',array('student_id'=>I('post.student_id')),1,'Success');
			else 
				$this->error($res);
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
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form=new CourseModel();
		if(IS_POST){
			$condition=$form->authFindByStudent(I('post.'));
			//var_dump($condition);
		}
		else if(IS_GET) $condition=$form->authFindByStudent(I('get.'));
		$query=$form->getCourseAndStudentBy($condition)->buildSql();
		$res=$form->table($query.'a')->select();
		//var_dump($res);
		$avg=$form->table($query.'a')->avg('grades');
		//var_dump($avg);	
		
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
}