<?php
namespace Sms\Model;
use Think\Model;
class CourseModel extends Model {
	protected $insertFields = 'course_id,name,teacher_id,credit,allowed_year,cancel_year';
	protected $updateFields = 'course_recid,name,teacher_id,credit,allowed_year,cancel_year';
	protected $_validate = array(
			array('course_id','7','ID length must be 7',self::EXISTS_VALIDATE,'length'),
			array('course_id','','ID exists',self::EXISTS_VALIDATE,'unique'),
			array('course_id','require','ID expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			array('course_id','number','ID must be 7 digits'),
			
			array('name','1,20','Invalid name',self::EXISTS_VALIDATE,'length'),
			array('name','require','Name expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
			array('teacher_id','checkTeacherId','Teacher not exists',self::EXISTS_VALIDATE,'callback'),
			array('teacher_id','require','Teacher ID expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
			array('credit','number','Invalid credit'),
			array('credit','require','Credit expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
			array('allowed_year','1900,2999','Invalid entrance year',self::EXISTS_VALIDATE,'between'),
			array('allowed_year','require','Entrance year expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
			array('cancel_year','1900,2999','Invalid entrance year',self::EXISTS_VALIDATE,'between'),
			array('cancel_year','require','Entrance year expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
	);
	protected function checkTeacherId($teacher_id) {
		$form = new TeacherModel();
		return ($form->getByTeacher_id($teacher_id) != null);
	}
	
	protected $_auto = array(
			//if cancel_year is null,set it to max
			array('cancel_year','fillCancel_year',self::MODEL_BOTH,'callback')
	);
	protected function fillCancel_year($year) {
		return ($year) ? $year : 2999;
	}
	
	public function validateAndInsertEnroll($data) {
		$form = new Model();
		$enrollable = $this->getEnrollableByStudentId($data['student_id']);
		$selected = null;
		foreach ($enrollable as $row) {
			if ($data['course_id'] === $row['course_id'] and $row['enroll_year_lb'] <= $data['enroll_year'] and $data['enroll_year'] <= $row['enroll_year_ub']) {
				$selected = $row;break;
			}
		}
		if ($selected === null)
			return 'Invalid enrollment';
		//var_dump($form->table('enroll')->fetchSql()->add($data));
		$res = $form->table('enroll')->add($data);
		if (!res)
			return $form->getError();
		else
			return true;
	}
	


	/**
	 * Validate course
	 * @param array $data
	 * @return string|true True if success
	 */
	public function validateCourse($data) {
		if (isset($data['allowed_year']) and @$data['cancel_year'] != null and $data['allowed_year'] > $data['cancel_year'])
			return 'Cancel year should not smaller than allowed year';
		return true;
	}
	
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
		//Uncomm
		return $this->table(array('course'=>'C','student'=>'S','teacher'=>'T'))
		->where(array(
				'C.allowed_year <= S.entrance_year',
				'S.entrance_year <= C.cancel_year',
				'C.teacher_id = T.teacher_id'
		))
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
				'S.entrance_year'=>'enroll_year_lb',
				'C.cancel_year'=>'enroll_year_ub'
		))
		->select();
	}
	
	/**
	 * Give the condition to search the enroll table and add the course name and student name
	 * @return Model SubQuery
	 */
	public function getCourseAndStudentBy($condition) {
		//var_dump($condition);
		$query=array();
		if($condition['course_id']) $query['E.course_id']=$condition['course_id'];
		if($condition['student_id']) $query['E.student_id']=$condition['student_id'];
		if($condition['class']) $query['S.class']=$condition['class'];
		//var_dump($query);
		return $this->table(array('course'=>'C','student'=>'S','enroll'=>'E'))
		->where(array(
				'E.course_id = C.course_id',
				'E.student_id = S.student_id'
		))
		->where($query)
		->field(array(
				'S.name'=>'student_name',
				'C.name'=>'course_name',
				'E.enroll_id',
				'E.student_id',
				'E.enroll_year',
				'E.course_id',
				'E.grades'
		));
	}

	public function authFindByStudent($data){
		$condition=array();
		//valide the data
		if(@$data['class']){
			$condition['class']=$data['class'];
		}
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
		//var_dump($condition);
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