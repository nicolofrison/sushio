<?php namespace App\Models;

use CodeIgniter\Model;

class OrdersModel extends Model {
    protected $table      = 'orders';
    protected $primaryKey = 'order_id';

    protected $allowedFields = ['user_id', 'code','amount','completed','editable'];
}