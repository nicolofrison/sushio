<?php namespace App\Models;

use CodeIgniter\Model;

class OrdersViewModel extends Model {
    protected $table      = 'orders_view';
    protected $primaryKey = 'order_id';

    public function findAllByGroupIdAndUserId($groupId, $userId) {
        return $this->where('group_id', $groupId)->where('user_id', $userId)->findAll();
    }

    public function findAllByGroupIdGroupByCodeByConfirmed($groupId, $confirmed = null) {
        if ($confirmed === null) {
            return $this->select('code, SUM(amount) AS amount, GROUP_CONCAT(username SEPARATOR \', \') AS username, confirmed')->groupBy(array('code','confirmed'))->where('group_id', $groupId)->findAll();
        } else if ($confirmed) {
            return $this->select('code, SUM(amount) AS amount, GROUP_CONCAT(username SEPARATOR \', \') AS username, confirmed')->groupBy(array('code','confirmed'))->where('group_id', $groupId)->where('confirmed >', 0)->findAll();
        } else {
            return $this->select('code, SUM(amount) AS amount, GROUP_CONCAT(username SEPARATOR \', \') AS username, confirmed')->groupBy(array('code','confirmed'))->where('group_id', $groupId)->where('confirmed', 0)->findAll();
        }
    }
}