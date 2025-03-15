<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(EventInterface $event)
{
    parent::beforeFilter($event);

    // Allow CORS for Angular on localhost:4200
    $this->response = $this->response->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization');

    // Handle preflight OPTIONS request
    if ($this->request->is('options')) {
        $this->setResponse($this->response->withStatus(200));
        return $this->response;
    }

    // Explicitly apply response headers
    $this->setResponse($this->response);
}

}
