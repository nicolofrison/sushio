<?php namespace App\Models;

use CodeIgniter\Model;

class OrdersViewModel extends Model {
    protected $table      = 'orders_view';
    protected $primaryKey = 'order_id';

    public function findAllByGroupIdAndUserId($groupId, $userId) {
        return $this->where('group_id', $groupId)->where('user_id', $userId)->findAll();
    }

    public function findAllByGroupIdGroupByCode($groupId) {
        return $this->select('code, SUM(amount) AS amount, GROUP_CONCAT(nickname SEPARATOR \', \') AS nickname')->groupBy('code')->where('group_id', $groupId)->findAll();
    }
}