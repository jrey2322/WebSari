<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['url', 'form'];
    protected $session;

    public function initController(
        RequestInterface  $request,
        ResponseInterface $response,
        LoggerInterface   $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
    }

    protected function checkSession()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to(base_url('login'))
                             ->with('error', 'Please login to continue.');
        }
        return null;
    }

    protected function checkOwner()
    {
        $check = $this->checkSession();
        if ($check) return $check;

        if ($this->session->get('user_role') !== 'owner') {
            return redirect()->to(base_url('dashboard'))
                             ->with('error', 'Access denied. Owner only.');
        }
        return null;
    }
}