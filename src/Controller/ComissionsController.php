<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Comissions Controller
 *
 * @property \App\Model\Table\ComissionsTable $Comissions
 * @method \App\Model\Entity\Comission[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComissionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Transactions'],
        ];
        $comissions = $this->paginate($this->Comissions);

        $this->set(compact('comissions'));
    }

    /**
     * View method
     *
     * @param string|null $id Comission id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comission = $this->Comissions->get($id, [
            'contain' => ['Transactions'],
        ]);

        $this->set(compact('comission'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $comission = $this->Comissions->newEmptyEntity();
        if ($this->request->is('post')) {
            $comission = $this->Comissions->patchEntity($comission, $this->request->getData());
            if ($this->Comissions->save($comission)) {
                $this->Flash->success(__('The comission has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The comission could not be saved. Please, try again.'));
        }
        $transactions = $this->Comissions->Transactions->find('list', ['limit' => 200]);
        $this->set(compact('comission', 'transactions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Comission id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comission = $this->Comissions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comission = $this->Comissions->patchEntity($comission, $this->request->getData());
            if ($this->Comissions->save($comission)) {
                $this->Flash->success(__('The comission has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The comission could not be saved. Please, try again.'));
        }
        $transactions = $this->Comissions->Transactions->find('list', ['limit' => 200]);
        $this->set(compact('comission', 'transactions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Comission id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comission = $this->Comissions->get($id);
        if ($this->Comissions->delete($comission)) {
            $this->Flash->success(__('The comission has been deleted.'));
        } else {
            $this->Flash->error(__('The comission could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
