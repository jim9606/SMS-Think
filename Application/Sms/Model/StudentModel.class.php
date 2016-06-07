<?php
namespace Sms\Model;
use Think\Model;
class StudentModel extends Model {
	protected $insertFields = 'student_id,name,gender,entrance_age,entrance_year,class';
	protected $updateFields = 'student_recid,name,gender,entrance_age,entrance_year,class';
	protected $_validate = array(
		array('student_id','10','ID length must be 10',self::EXISTS_VALIDATE,'length'),
		array('student_id','','ID exists',self::EXISTS_VALIDATE,'unique'),
		array('student_id','require','ID expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
		array('student_id','number','ID must be 10 digits'),
			
		array('name','1,20','Invalid name',self::EXISTS_VALIDATE,'length'),
		array('name','require','Name expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
		array('gender','0,1','Invalid gender',self::EXISTS_VALIDATE,'in'),
		array('gender','require','Gender expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
		array('entrance_age','10,50','Invalid entrance age',self::EXISTS_VALIDATE,'between'),
		array('entrance_age','require','Entrance age expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
		array('entrance_year','1900,2999','Invalid entrance year',self::EXISTS_VALIDATE,'between'),
		array('entrance_year','require','Entrance year expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
			
		array('class','1,20','Invalid class length',self::EXISTS_VALIDATE,'length'),
		array('class','require','Class expected',self::MUST_VALIDATE,'',self::MODEL_INSERT),
	);
}