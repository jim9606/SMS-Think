<?php
namespace Sms\Controller;
use Think\Controller;
use Think\Model;
use Sms\Model\TeacherModel;
class TeacherController extends Controller{
public function insert() {
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = D('Teacher');
		$data = $form->create(I('post.'),Model::MODEL_INSERT);
		if ($data) {
			$res = $form->add($data);
			if($res) {
				$res2 = addUser('teacher', $data['teacher_id']);
				if ($res2)
					$this->success("New record $res#");
				else 
					$this->error("User add failed : $res2");
			}else
				$this->error($form->getError());
		}else 
			$this->error($form->getError());
	}
	public function update() {
		//use teacher_recid to identify students
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = D('Teacher');
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
	public function edit(){
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$teacher_recid=I('get.teacher_recid');
		$Form=D('Teacher');
		$this->assign('vo',$Form->find($teacher_recid));
		$this->display();
	}
	public function find() {
		//Do not contain any empty values
		//Invalid keys will be ignored
		IS_GET or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		
		$form = M('Teacher');
		$query = $form->create(I('get.'));
		$res = $form->where($query)->select();
		$this->assign('list',$res);
		$this->display();// show the result
	}
	public function findTeacher(){
		IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['read'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$data=I('post.');
		$condition=array();
		//valide the data
		$form=M('Teacher');
		if(@$data['name']){
			$condition['teacher_id']=$form->getFieldByName($data['name'],'teacher_id');
		}
		if(@$data['teacher_id']){
			$condition['teacher_id']=$data['teacher_id'];
		}
		$this->redirect('find',$condition);
	}
	
	public function delete($teacher_id) {
		//IS_POST or $this->error(C('MSG_API_INVALID_METHOD'));
		!C('PERMISSION_CONTROL') or session('permissions')['admin'] or $this->error(C('MSG_API_PERMISSION_DENIED'));
		$form = D('Teacher');
		$res = $form->where(array('teacher_id'=>$teacher_id))->delete();
		if($res) {
			$form = D('User');
			$res = $form->where(array('user'=>$teacher_id))->delete();
			$this->success("Deleted $teacher_id#");
		}else
			//if $res === 0 No update because of no modified values
			$this->error(($res === 0) ? "Not modified" : $form->getError());
	}
	
	public function add() {
		$Tform = new TeacherModel();
		$res = $Tform->field('teacher_id,name')->select();
		$this->assign('teacher_list',$res);
	}
}