<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 * @property \Cake\Datasource\RepositoryInterface|null Authentication
 * @property \Cake\Datasource\RepositoryInterface|null Authorization
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->addUnauthenticatedActions(['index', 'view']);
        $this->loadComponent('Authorization.Authorization');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $this->paginate = [
            'contain' => ['ParentCategories'],
        ];
        $categories = $this->paginate($this->Categories);

        $this->set(compact('categories'));
        $this->set('_serialize', 'categories');
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();
        $category = $this->Categories->get($id, [
            'contain' => ['ParentCategories', 'ChildCategories'],
        ]);

        $this->set(compact('category'));
        $this->set('_serialize', 'category');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        $category = $this->Categories->patchEntity($category, $this->request->getData());

        if (!$this->Authorization->can($category, 'create')) {
            throw new ForbiddenException('You don\'t have permission for this action.');
        }
        if (!$this->Categories->save($category)) {
            throw new BadRequestException($category->getErrors());
        }

        $this->set(compact('category'));
        $this->set('_serialize', 'category');
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => [],
        ]);

            $category = $this->Categories->patchEntity($category, $this->request->getData());

        if (!$this->Authorization->can($category, 'update')) {
            throw new ForbiddenException('You don\'t have permission for this action.');
        }
            if (!$this->Categories->save($category)) {
                throw new BadRequestException($category->getErrors());
            }

        $this->set(compact('category'));
        $this->set('_serialize', 'category');
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $category = $this->Categories->get($id);
        if (!$this->Authorization->can($category, 'delete')) {
            throw new ForbiddenException('You don\'t have permission for this action.');
        }
        if (!$this->Categories->delete($category)) {
            throw new BadRequestException($category->getErrors());
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
