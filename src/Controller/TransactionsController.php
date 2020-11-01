<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @property \Cake\Datasource\RepositoryInterface|null Authorization
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->authorize($this->Transactions, 'index');
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions'));
        $this->set('_serialize', 'transactions');
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->getById($id);
        $this->Authorization->authorize($transaction, 'view');

        $this->set(compact('transaction'));
        $this->set('_serialize', 'transaction');
    }

    public function myTransactions()
    {
        $this->Authorization->skipAuthorization();
        $transactions = $this->paginate($this->Transactions->getTransactionsByUserId($this->Authentication->getIdentity()->id,
        $this->request));

        $this->set(compact('transactions'));
        $this->set('_serialize', 'transactions');
    }

}
