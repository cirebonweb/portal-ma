<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\SessionInterface;
use CodeIgniter\Validation\ValidationInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected SessionInterface $session;
    protected $request;
    protected ValidationInterface $validation;
    protected ConnectionInterface $db;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->helpers = ['asset', 'url'];

        parent::initController($request, $response, $logger);

        $this->session = service('session');
        $this->request = service('request');
        $this->validation = service('validation');
    }

    /**
     * Penjaga Akses AJAX Global
     */
    protected function ajax(): ?ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->json(false, 'Akses dilarang', null, 403);
        return null;
    }

    /**
     * Helper Response JSON Global
     */
    protected function json(bool $success, mixed $messages = null, mixed $data = null, int $code = 200): ResponseInterface
    {
        return $this->response->setStatusCode($code)->setJSON([
            'success'  => $success,
            'messages' => $messages,
            'data'     => $data
        ]);
    }
}
