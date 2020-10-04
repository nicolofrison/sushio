<?php namespace App\Models;

use CodeIgniter\Model;

class OrdersModel extends Model {
    protected $table      = 'orders';
    protected $primaryKey = 'order_id';

    protected $allowedFields = ['user_id', 'code','amount','checked','confirmed'];

    public function setOrdersConfirmed($userIds, $confirmed) {
        $this->whereIn('user_id', $userIds)
            ->where('confirmed', 0)
            ->set('confirmed', $confirmed)
            ->update();
    }
}