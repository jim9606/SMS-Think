<?php
namespace Sms\Model;
use Think\Model;
class CourseModel extends Model {
	protected $insertFields = 'course_id,name,teacher_id,credit,allowed_grade,cancel_grade';
	protected $updateFields = 'course_recid,name,teacher_id,credit,allowed_grade,cancel_grade';
	protected $_validate = array(
			
	);
}