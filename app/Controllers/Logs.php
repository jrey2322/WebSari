<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;

class Logs extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new ActivityLogModel();
    }

    public function index()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Activity Log',
            'logs'  => $this->logModel->getLogs(200),
        ];

        return view('logs/index', $data);
    }
}
