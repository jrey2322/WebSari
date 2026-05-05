<?php


namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $session;

    public function initController(
        RequestInterface  $request,
        ResponseInterface $response,
        LoggerInterface   $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
    }

    // ✅ Check if user is logged in
    protected function checkSession()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login')
                             ->with('error', 'Please login to continue.');
        }
        return null;
    }

    // ✅ Check if user is Owner
    protected function checkOwner()
    {
        $check = $this->checkSession();
        if ($check) return $check;

        if ($this->session->get('user_role') !== 'owner') {
            return redirect()->to('/dashboard')
                             ->with('error', 'Access denied. Owner only.');
        }
        return null;
    }
}