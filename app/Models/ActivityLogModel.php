<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table         = 'activity_logs';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'user_id',
        'activity',
        'module',
        'details',
        'ip_address',
        'user_agent',
    ];

    /**
     * Add an activity log entry
     */
    public function log($activity, $module, $details = null)
    {
        $session = session();
        $request = \Config\Services::request();

        return $this->insert([
            'user_id'    => $session->get('user_id') ?: 0,
            'activity'   => $activity,
            'module'     => $module,
            'details'    => $details,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
        ]);
    }

    /**
     * Get logs with user information
     */
    public function getLogs($limit = 100)
    {
        return $this->select('activity_logs.*, users.name as user_name')
                    ->join('users', 'users.id = activity_logs.user_id', 'left')
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->findAll($limit);
    }
}
