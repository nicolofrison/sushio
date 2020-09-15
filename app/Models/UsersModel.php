<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UsersModel extends Model {
    protected $table      = 'users';
    protected $primaryKey = 'user_id';

    protected $allowedFields = ['name', 'surname','username','group_id'];

    public function isValidUser($userId, $groupId) {
        $results = $this->db->query("SELECT * FROM users U JOIN groups G ON U.group_id = G.group_id WHERE U.user_id = ".$userId." " .
                "AND U.group_id = ".$groupId." AND G.created_at > (now() - INTERVAL 6 HOUR)")->getResultObject();
        if(count($results) > 0) {
            return true;
        } else {
            return false;
        }
    }
}