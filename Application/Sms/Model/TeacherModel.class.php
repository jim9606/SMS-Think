<?php
namespace Sms\Model;
use Think\Model;
class TeacherModel extends Model {
	protected $insertFields = 'teacher_id,name';
	protected $updateFields = 'teacher_recid,name';
	protected $_validate = array(
		array('teacher_id','5','ID length must be 5',self::EXISTS_VALIDATE,'length'),
		array('teacher_id','','ID exists',self::EXISTS_VALIDATE,'unique'),
		array('teacher_id','require','ID expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
		array('name','1,20','Invalid name',self::EXISTS_VALIDATE,'length'),
		array('name','require','Name expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
	);
}