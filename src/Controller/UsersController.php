<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\UnauthorizedException;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Cake\Datasource\RepositoryInterface|null Authentication
 * @property \Cake\Datasource\RepositoryInterface|null Authorization
 * @property \Cake\Datasource\RepositoryInterface|null Roles
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @var \Cake\Datasource\RepositoryInterface|null
     */

    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
        $this->loadComponent('Authorization.Authorization');

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->authorize($this->Users, 'index');

        $transactions = $this->paginate($this->Users);
        $this->set(compact('query'));
        $this->set('_serialize', 'query');
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->getUserById($id);

        if ($this->Authorization->can($user, 'view')) {
            $this->set(compact('user'));
            $this->set('_serialize', 'user');
        } else {
            throw new ForbiddenException('You don\'t have permission to view this profile.');
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->Authorization->skipAuthorization();
        $user = $this->Users->newEmptyEntity();
        $user->setAccess('email', true);

        $user = $this->Users->patchEntity($user, $this->request->getData());
        $user->password = $this->request->getData('password');

        if (!$this->Users->save($user)) {
            throw new BadRequestException($user->getErrors());
        }

        $rolesModel = $this->loadModel('Roles');
        $rolesModel->createRole($user, 'user');


        $this->set(compact('user'));
        $this->set('_serialize', 'user');
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->getUserById($id);

        if ($this->Authorization->can($user, 'update')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!$this->Users->save($user)) {
                throw new BadRequestException($user->getErrors(), 'Cannot save this user.');
            }
        } else {
            throw new ForbiddenException('You don\'t have permission for this action.');
        }

        $this->set(compact('user'));
        $this->set('_serialize', 'user');

    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \App\Http\Exception\BadRequestException When record cannot be deleted.
     * @throws \Cake\Http\Exception\ForbiddenException When user don't have enough permissions.
     */
    public function delete($id = null)
    {
        $user = $this->Users->getUserById($id);

        if ($this->Authorization->can($user, 'delete')) {

            if (!$this->Users->delete($user)) {
                throw new BadRequestException([], 'Can\'t delete this user.');
            }
            $response = 'User deleted.';
        } else {
            throw new ForbiddenException('You don\'t have permission to delete this user.');
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }


    public function login()
    {
        $this->Authorization->skipAuthorization();

        $result = $this->Authentication->getResult();

        // Incorrect credentials
        if (!$result->isValid()) {
            throw new UnauthorizedException('Invalid email or password.');
        }

        $user = $result->getData();

        // Deleted account
        if ($user->deleted) {
            throw new BadRequestException([], 'This account is deleted.');
        }

        // Successful login attempt
        $privateKey = file_get_contents(CONFIG . '/jwt.key');
        $payload = [
            'iss' => 'onix.internship',
            'sub' => $user->id,
            'exp' => time() + 50000,
        ];
        $json = [
            'access_token' => JWT::encode($payload, $privateKey, 'RS256'),
        ];
        $this->set(compact('json'));
        $this->viewBuilder()->setOption('serialize', 'json');

    }

    public function myAccount()
    {
        $id = $this->Authentication->getIdentity()->id;
        switch ($this->request->getMethod()) {
            case 'GET':
                $this->view($id);
                break;
            case 'PATCH':
                $this->edit($id);
                break;
            case 'DELETE':
                $this->delete($id);
                break;
        }
    }

    public function deposit()
    {
        $id = $this->Authentication->getIdentity()->id;
        $this->Authorization->skipAuthorization();

        $user = $this->Users->updateBalance($id, $this->request->getData('amount'));
        $this->set(compact('user'));
        $this->set('_serialize', 'user');
    }

    public function setRole($id = null)
    {
        $user = $this->Users->get($id, ['contain' => ['Roles']]);

        if ($this->Authorization->can($user, 'setRole')) {

            $rolesModel = $this->loadModel('Roles');
            $role = $this->request->getData('role');

            if ($this->request->getMethod() == 'DELETE') {
                $rolesModel->deleteRole($user, $role);
            } else {
                $rolesModel->createRole($user, $role);
            }

        } else {
            throw new ForbiddenException('You don\'t have permission for this action.');
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

}
