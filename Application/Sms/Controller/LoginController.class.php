<?php
namespace Sms\Controller;
use Think\Controller;

class LoginController extends Controller{
	public function selectIdentity(){
		if(!IS_POST){
			$this->error("Invalid input");
		}
		$select=I('post.select');
		if($select==0){
			$form=D('user');//TODO,need to write the model of user table
			$user=I('post.user');
			$psd=I('post.password');
			$result=$form->where("user=$user")->select();
			if($result!=NULL){
				$this->error("User exists");
			}
			else{
				setup($user,$psd);
			}
		}
		else{
			$form=D('user');//TODO,need to write the model of user table
			$user=I('post.user');
			$psd=I('post.password');
			$result=$form->where("user=$user AND password=$psd")->select();
			if($result==false){
				$this->error("Program error");
			}
			if($result==NULL){
				$this->error("Wrong user or password");
			}
			else{
				$this->success("Login successfully");
				$identity=$result['identity'];
				switch($identity){
					case 0:
						studentLogin();break;
					case 1:
						teacherLogin();break;
					case 2:
						administorLogin();break;
				}
			}
		}
	}
	public function setup($user,$psd){
		
	}
}