<?php
namespace Sms\Model;
use Think\Model;
class CourseModel extends Model {
	protected $insertFields = 'course_id,name,teacher_id,credit,allowed_year,cancel_year';
	protected $updateFields = 'course_recid,name,teacher_id,credit,allowed_year,cancel_year';
	protected $_validate = array(
			
	);
	
	/**
	 * Get which courses a student can enroll by student_id
	 * @param string $student_id
	 * @return Associate array
	 * student_id,student_name,student_class,
	 * course_id,course_name,credit,
	 * teacher_id,teacher_name,
	 * enroll_year_LB,enroll_year_UB
	 */
	public function getEnrollableByStudentId($student_id) {
		return $this->table(array('course'=>'C','student'=>'S','teacher'=>'T'))
		->where(array(
				'C.allowed_year <= S.entrance_year',
				'S.entrance_year <= C.cancel_year',
				'C.teacher_id = T.teacher_id'))
		->where(array('S.student_id'=>$student_id))
		->field(array(
				'S.student_id',
				'S.name'=>'student_name',
				'S.class'=>'student_class',
				'C.course_id',
				'C.name'=>'course_name',
				'C.credit',
				'C.teacher_id',
				'T.name'=>'teacher_name',
				'S.entrance_year'=>'enroll_year_LB',
				'C.cancel_year'=>'enroll_year_UB'
		))
		->select();
	}
	
	public function authFindByStudent($data){
		var_dump($data);
		$condition=array();
		//valide the data
		if(@$data['student_id'] or @$data['student_name']){
			$form=new StudentModel();
			if(@$data['student_name']){
				$condition['student_id']=$form->getFieldByName($data['student_name'],'student_id');
			}
			if(@$data['student_id']){
				$condition['student_id']=$data['student_id'];
			}
		}
		if(@$data['course_id'] or @$data['course_name']){
			$form=new CourseModel();
			if(@$data['course_name']){
				$condition['course_id']=$form->getFieldByName($data['course_name'],'course_id');
			}
			if(@$data['course_id']){
				$condition['course_id']=$data['course_id'];
			}
		}
		//$var_dump($condition);
		return $condition;
	}
	
	public function authFindByTeacher($data){
		//var_dump($data);
		$condition=array();
		//valide the data
		if(@$data['course_id'] or @$data['course_name']){
			if(@$data['course_name']){
				$condition['name']=$data['course_name'];
			}
			if(@$data['course_id']){
				$condition['course_id']=$data['course_id'];
			}	
		}
		if(@$data['teacher_id'] or @$data['teacher_name']){
			$form=new TeacherModel();			
			if(@$data['teacher_name']){
				$condition['teacher_id']=$form->getFieldByName($data['teacher_name'],'teacher_id');
			}
			if(@$data['teacher_id']){
				$condition['teacher_id']=$data['teacher_id'];
			}
		}
		return $condition;
	}
}