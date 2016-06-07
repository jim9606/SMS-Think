<?php
namespace Sms\Model;
use Think\Model;
class CourseModel extends Model {
	protected $insertFields = 'course_id,name,teacher_id,credit,allowed_year,cancel_year';
	protected $updateFields = 'course_recid,name,teacher_id,credit,allowed_year,cancel_year';
	protected $_validate = array(
			
	);
	
	public function getEnrollableByStudent($student_id) {
		return $this->fetchSql(true)->table(array('course'=>'C','student'=>'S'))->where(array('C.allowed_year <= S.entrance_year','S.entrance_year <= C.cancel_year'))->select();
	}
	
	protected function checkEnroll($data) {
	}
}