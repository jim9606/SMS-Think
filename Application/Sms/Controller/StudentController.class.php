<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
class StudentController extends Controller{
	public function insert() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = D('Student');
		$data = $form->create(I('post.'),Model::MODEL_INSERT);
		if ($data) {
			$res = $form->add($data);
			$form2=M('User');
			$user['type']='student';
			$user['user']=I('post.student_id');
			$user['password']=C('USER_DEFAULT_PASSWORD');
			$data2 = $form->create($user,Model::MODEL_UPDATE);
			if($data2){
				$res2=$form2->add($data2);
				if($res) {
					if($res2) $this->success("Record updated");
					else $this->error("User add failed");
				}
				else
					//if $res === 0 No update because of no modified values
					$this->error(($res === 0) ? "Not modified" : $form->getError());
				}
				else $this->error($form2->getError());
		}else 
			$this->error($form->getError());
	}
	public function update() {
		//use student_recid to identify students
		//If nothing updated, returns error
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));

		$form = D('Student');
		$data = $form->create(I('post.'),Model::MODEL_UPDATE);
		if ($data) {
			$res = $form->save($data);
			//var_dump($res);
			if($res) {
				$this->success("Record updated");
			}else
				//if $res === 0 No update because of no modified values
				$this->error(($res === 0) ? "Not modified" : $form->getError());
			}
			else 
				$this->error($form->getError());
	}
	public function delete($student_id) {
		//TODO: delete a student
	}
	public function add() {
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$this->show();
	}
	public function edit(){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$student_recid=I('get.student_recid');
		$Form=D('Student');
		$this->assign('vo',$Form->find($student_recid));
		$this->display();
	}
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		IS_GET or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
			
		$form = M('Student');
		$query = $form->create(I('get.'));
		//!(session('type') === 'student') or $query['student_id']=session('user');
		$res = $form->where($query)->order('student_id asc')->select();		
		$this->assign('list',$res);
		$this->show();
	}

}