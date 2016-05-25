<?php
namespace Sms\Model;
use Think\Model;

class TeacherModel extends Model {
	protected $_validate = array(
		array('id','1,8','Invalid ID length',self::EXIST_VALIDATE,'length'),
		array('id','require','ID expected',self::EXIST_VALIDATE,'',self::MODEL_INSERT),
		array('id','','ID exists',self::EXIST_VALIDATE,'unique',self::MODEL_INSERT),
		
		array('name','1,8','Invalid name length',self::EXIST_VALIDATE,'length'),
		array('name','require','Name expected',self::EXIST_VALIDATE,'',self::MODEL_INSERT),
	);
}