<?php namespace App\Models;

use CodeIgniter\Model;

class GroupsModel extends Model {
    protected $table      = 'groups';
    protected $primaryKey = 'group_id';

    protected $allowedFields = ['name', 'password','group_id'];

    public function deleteOlders() {
        $this->db->query('DELETE FROM groups WHERE created_at < (now() - INTERVAL 6 HOUR)');
    }
}