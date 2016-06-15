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
			if($res){
				$res2 = addUser('student', $data['student_id']);
				if($res) {
					if($res2) $this->success("Record updated");
					else $this->error("User add failed : $res2");
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
		//IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form = D('Student');
		$res = $form->where(array('student_id'=>$student_id))->delete();
		if($res) {
			$form = D('User');
			$res = $form->where(array('user'=>$student_id))->delete();
			$this->success("Deleted $student_id#");
		}else
			//if $res === 0 No update because of no modified values
			$this->error(($res === 0) ? "Not modified" : $form->getError());
	}
	public function add() {
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form = M('student');
		$class = $form->field('class')->select();
		$class = array_unique(array_column($class,'class'));
		$this->assign('class_list',$class);
		$this->display();
	}
	public function edit(){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$student_recid=I('get.student_recid');
		$form=D('Student');
		$class = $form->field('class')->select();
		$class = array_unique(array_column($class,'class'));
		$this->assign('class_list',$class);
		$this->assign('vo',$form->find($student_recid));
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
		$res = $form->where($query)->order('student_id ASC')->select();	
		$this->assign('list',$res);
		$this->show();
	}
	
	public function findStudent(){
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$data=I('post.');
		$condition=array();
		//valide the data
		$form=M('Student');
		if(@$data['name']){
			$condition['student_id']=$form->getFieldByName($data['name'],'student_id');
		}
		if(@$data['student_id']){
			$condition['student_id']=$data['student_id'];
		}
		$this->redirect('find',$condition);
	}
}