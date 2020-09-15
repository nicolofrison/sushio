<?php namespace App\Controllers;

use App\Models\GroupsModel;
use App\Models\UsersModel;

class Home extends BaseController
{

	public function index()
	{
		//return view('welcome_message');
		return view('Login');
	}

	public function login() {
        $action = $this->request->getPost()['action'];
        $name = $this->request->getPost()['name'];
        $surname = $this->request->getPost()['surname'];
        // for now not considered
        $username = $this->request->getPost()['username'];
        $groupName = $this->request->getPost()['groupName'];
        $groupPassword = $this->request->getPost()['groupPassword'];

        //var_dump($this->request->getPost());exit;
        if ($action === 'joinGroup') {
            $this->groupModel->deleteOlders();
            $group = $this->groupModel->where('name', $groupName)->first();
            if (!isset($group) || $group['password'] !== $groupPassword) {
                header('Content-Type: application/json');
                echo json_encode(array('success'=>false,'message'=>lang('Error.groupWrongCredentials')));
                exit;
            }

            $res = $this->userModel->where("username", $username)->where("group_id", $group['group_id'])->first();
            if (isset($res) && $username === $res['username'] && !empty($username)) {
                header('Content-Type: application/json');
                echo json_encode(array('success'=>false,'message'=>lang('Error.usernameAlreadyExist')));
                exit;
            }

            $userResult = $this->userModel->insert(array('name' => $name, 'surname' => $surname, 'username' => $username, 'group_id' => $group['group_id']));

            $this->session->set('user_id', $userResult);
            $this->session->set('group_id', $group['group_id']);

            header('Content-Type: application/json');
            echo json_encode(array('success'=>true,'message' => $userResult));
            exit;
        } else {
            $this->groupModel->deleteOlders();
            $group = $this->groupModel->where('name', $groupName)->first();
            if (isset($group)) {
                header('Content-Type: application/json');
                echo json_encode(array('success'=>false,'message'=> lang('Error.groupAlreadyExist')));
                exit;
            }

            $groupResult = $this->groupModel->insert(array('name'=>$groupName,'password'=>$groupPassword));
            if (!isset($groupResult)) {
                header('Content-Type: application/json');
                echo json_encode(array('success'=>false,'message' => lang('Error.groupAdd')));
                exit;
            }

            $userResult = $this->userModel->insert(array('name'=>$name,'surname'=>$surname, 'username' => $username,'group_id'=>$groupResult));
            if (!isset($userResult)) {
                header('Content-Type: application/json');
                echo json_encode(array('success'=>false,'message' => lang('Error.userAdd')));
                exit;
            }

            $this->session->set('user_id', $userResult);
            $this->session->set('group_id', $groupResult);
            header('Content-Type: application/json');
            echo json_encode(array('success'=>true,'message' => 'ok'));
            exit;
        }
    }

	//--------------------------------------------------------------------

}
