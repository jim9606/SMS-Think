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
			$form=D('user');//TODO,need to wrizte the model of user table
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
			$cond['user'] = I('post.user');
			$cond['password'] = I('post.password');
			$result=$form->where($cond)->count();
			if($result==false){
				$this->error("Program error");
			}
			if($result==NULL){
				$this->error("Wrong user or password");
			}
			else{
				//$this->success("Login successfully");
				$identity=$result['identity'];
				switch($identity){
					case 0:
						studentLogin();break;
					case 1:
						teacherLogin();break;
					case 2:
						administorLogin();break;
					default:
						$this->error("data error");break;
				}
			}
		}
	}
	public function setup($user,$psd){
		$this->assign('user',$user);
		$this->assign('password',$psd);
		$this->display();
	}
	public function addNewUser(){
		if(!IS_POST){
			$this->error('Invalid values');
		}
		$psd=I('post.password');
		$psd_a=I('post.password_again');
		if($psd!=$psd_a){
			$this->error('The two times password are different');
		}
		$identity=I('post.identity');
		switch($identity){
			case 0:
				
				break;
			case 1:
				break;
			case 2:
				break;
		}
	}
}