<?php namespace App\Controllers;

class Orders extends BaseController
{

    public function index()
	{
	    //print_r($this->session);exit;
        if (!$this->session->has('user_id') || !$this->session->has('group_id')
            || !$this->userModel->isValidUser($this->session->get('user_id'), $this->session->get('group_id'))) {
            return redirect()->to(base_url('Home'));
        } else {
            return view('Orders');
        }
	}

	public function isValidUser($userId, $groupId) {
        echo $this->userModel->isValidUser($userId, $groupId);
    }

	public function getJson($type = 1) {
	    // type: 1 - own, 2 - all, 3 - all grouped by codes
        switch ($type) {
            case 2:
                $ordersList = $this->ordersViewModel->where('group_id', $this->session->get('group_id'))->findAll();
                break;
            case 3:
                $ordersList = $this->ordersViewModel->findAllByGroupIdGroupByCode($this->session->get('group_id'));
                break;
            default:
                $ordersList = $this->ordersViewModel->findAllByGroupIdAndUserId($this->session->get('group_id'), $this->session->get('user_id'));
        }

        header('Content-Type: application/json');
        echo json_encode(array('success'=>true,'data' => $ordersList));
        exit;
    }

    public function createOrder() {
        $code = $this->request->getPost()['code'];
        $amount = $this->request->getPost()['amount'];

        if (!is_numeric($amount)) {
            header('Content-Type: application/json');
            echo json_encode(array('success'=>false,'data'=>'The number inserted is not valid'));
            exit;
        }

        $existingOrder = $this->orderModel->where('user_id', $this->session->get('user_id'))->where('code', $code)->first();
        if (isset($existingOrder) && $existingOrder !== null) {
            $orderId = $this->orderModel->update($existingOrder['order_id'],array('code'=>$code,'amount'=>$existingOrder['amount']+$amount,'user_id'=>$this->session->get('user_id')));
        } else {
            $orderId = $this->orderModel->insert(array('code'=>$code,'amount'=>$amount,'user_id'=>$this->session->get('user_id')));
        }

        header('Content-Type: application/json');
        if ($orderId > 0) {
            echo json_encode(array('success'=>true,'data'=>$orderId));
        } else {
            echo json_encode(array('success'=>false,'data'=>$this->orderModel->errors()));
        }
        exit;
    }

    public function updateOrder() {
	    $orderId = $this->request->getPost()['order_id'];
        $amount = $this->request->getPost()['amount'];

        $existingOrder = $this->orderModel->where('order_id', $orderId)->first();
        if (!isset($existingOrder) || $existingOrder === null) {
            header('Content-Type: application/json');
            echo json_encode(array('success'=>false,'data'=>'The order with the given id doesn\'t exist'));
            exit;
        }

        $orderId = $this->orderModel->update($existingOrder['order_id'],array('amount'=>$amount));

        header('Content-Type: application/json');
        echo json_encode(array('success'=>true,'data'=>$orderId));
        exit;
    }

    public function deleteOrder() {
        $orderId = $this->request->getPost()['order_id'];

        $existingOrder = $this->orderModel->where('order_id', $orderId)->first();
        if (isset($existingOrder) && $existingOrder !== null) {
            $this->orderModel->delete($orderId);
        }

        header('Content-Type: application/json');
        echo json_encode(array('success'=>true,'data'=>''));
        exit;
    }
}
