<?php
namespace Sms\Controller;
use Think\Controller;
//Just For test. Do not use it.
class TestController extends Controller {
	public function auth($user,$pwd) {
		authUser($user, $pwd);
		var_dump(session());
	}
}