<?php
namespace Sms\Controller;
use Think\Controller;
use Sms\Model\CourseModel;
//Just For test. Do not use it.
class TestController extends Controller {
	//auth and check session
	public function auth($user,$pwd) {
		authUser($user, $pwd);
		var_dump(session());
	}
	//check www-form with array
	public function ajax() {
		!IS_GET or $this->show();
		if (IS_POST) {
			var_dump(IS_AJAX);
			var_dump(I('post.'));
		}
	}
	public function session() {
		var_dump(session());
	}
	public function fetchSql($student_id) {
		$form = new CourseModel();
		$sub1 = $form->table(array('course'=>'C','enroll'=>'E'))
		->where(array(
				'C.course_id = E.course_id',
				'E.student_id'=>array('NEQ',$student_id)
		))
		->field(array('C.*'))->buildSql();
		
		$res = $form->getEnrollableByStudentId($student_id);
		var_dump($res);
	}
}