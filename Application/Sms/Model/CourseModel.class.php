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
	
}