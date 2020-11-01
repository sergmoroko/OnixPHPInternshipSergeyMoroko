<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Exception\BadRequestException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;

/**
 * Listings Controller
 *
 * @property \App\Model\Table\ListingsTable $Listings
 * @property \Cake\Datasource\RepositoryInterface|null Authorization
 * @method \App\Model\Entity\Listing[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ListingsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->addUnauthenticatedActions(['index', 'view']);

        $this->loadComponent('Authorization.Authorization');
        $this->loadComponent('Search.Search', ['actions' => ['index']]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();

        $queryParams = $this->request->getQueryParams();
        $searchDeletedCondition = ['status IS NOT' => 'deleted'];
        if (isset($queryParams['status']) && $queryParams['status'] == 'deleted') {
            $searchDeletedCondition = ['status' => 'deleted'];
        }

        $query = $this->Listings
            ->find('search', ['search' => $queryParams])
            ->contain(['Categories'])
            ->where($searchDeletedCondition);
        $listings = $this->paginate($query);

        $this->set(compact('listings'));
        $this->set('_serialize', 'listings');
    }

    /**
     * View method
     *
     * @param string|null $id Listing id.
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();

        $listing = $this->Listings->get($id, [
            'contain' => ['Categories', 'Users'],
        ]);

        $this->set(compact('listing'));
        $this->set('_serialize', 'listing');
    }

    /**
     * Add method
     *
     * @throws BadRequestException When record cannot be saved.
     */
    public function add()
    {
        $this->Authorization->skipAuthorization();

        $listing = $this->Listings->newEmptyEntity();

        $listing = $this->Listings->patchEntity($listing, $this->request->getData());
        $listing->seller_id = $this->Authentication->getIdentity()->id;

        if (!$this->Listings->save($listing)) {
            throw new BadRequestException($listing->getErrors());
        }

        $this->set(compact('listing'));
        $this->set('_serialize', 'listing');
    }

    /**
     * Edit method
     *
     * @param string|null $id Listing id.
     * @throws RecordNotFoundException When record not found.
     * @throws BadRequestException When record cannot be saved.
     */
    public function edit($id = null)
    {
        $listing = $this->Listings->get($id);

        $this->Authorization->authorize($listing, 'update');
        $listing = $this->Listings->patchEntity($listing, $this->request->getData());

        if (!$this->Listings->save($listing)) {
            throw new BadRequestException($listing->getErrors());
        }

        $this->set(compact('listing'));
        $this->set('_serialize', 'listing');
    }

    /**
     * Delete method
     *
     * @param string|null $id Listing id.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $listing = $this->Listings->get($id);
        $this->Authorization->authorize($listing, 'delete');

        $listing = $this->Listings->deleteListing($id);

        $this->set(compact('listing'));
        $this->set('_serialize', ['listing']);
    }

    /**
     * Buy method
     *
     * @param string|null $id Listing id.
     * @throws RecordNotFoundException When record not found.
     * @throws ForbiddenException When product is deleted/sold or belongs current user.
     * @throws BadRequestException When user don't have enough money to buy this product.
     */
    public function buy($id = null)
    {
        $this->Authorization->skipAuthorization();
        $order = $this->Listings->buy($this->Authentication->getIdentity(), $id);

        $this->set(compact('order'));
        $this->set('_serialize', 'order');
    }

    public function myListings()
    {
        $this->Authorization->skipAuthorization();
        $listings = $this->Listings->getListingsByUserId($this->Authentication->getIdentity()->id);

        $this->set(compact('listings'));
        $this->set('_serialize', 'listings');
    }

}
