<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UsersModel extends Model {
    protected $table      = 'users';
    protected $primaryKey = 'user_id';

    protected $allowedFields = ['name', 'surname','nickname','group_id'];

    public function isValidUser($userId, $groupId) {
        echo 'ciao1';
        $results = $this->db->query("SELECT * FROM users U JOIN groups G ON U.group_id = G.group_id WHERE U.user_id = ".$userId." " .
                "AND U.group_id = ".$groupId." AND G.created_at > (now() - INTERVAL 6 HOUR)")->getResultObject();
        echo $this->db->getLastQuery();
        print_r($results);exit;
        /*$user = $this->builder()->join('groups g', 'group_id = g.group_id')->where('user_id', $userId)->where('group_id', $groupId);
        print_r($user);
        echo $user['created_at'];exit;
        if($user != null) {
            return true;
        } else {
            return false;
        }*/
    }
}