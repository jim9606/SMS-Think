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
	public function session() {
		var_dump(session());
	}
	public function intersect() {
		$a = array(1,2,3);
		$b = array(4,3,1);
		var_dump(array_intersect($a,$b));
	}
}