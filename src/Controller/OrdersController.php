<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \Cake\Datasource\RepositoryInterface|null Authorization
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrdersController extends AppController
{
    /**
     * Index method
     */
    public function index()
    {
        $this->Authorization->authorize($this->Orders, 'index');
        $orders = $this->paginate($this->Orders);

        $this->set(compact('orders'));
        $this->set('_serialize', 'orders');

    }

    public function myOrders(){
        $this->Authorization->skipAuthorization();
        $orders = $this->paginate($this->Orders->getUserOrders($this->Authentication->getIdentity()));

        $this->set(compact('orders'));
        $this->set('_serialize', 'orders');
    }

    /**
     * View method
     *
     * @param string|null $id Order id.
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $order = $this->Orders->getById($id);
        $this->Authorization->authorize($order, 'view');

        $this->set(compact('order'));
        $this->set('_serialize', 'order');
    }

}
