<?php

namespace App\Models;

use CodeIgniter\Model;

class UtangModel extends Model
{
    protected $table         = 'utang_payments';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = false;

    protected $allowedFields = [
        'sale_id',
        'amount',
        'notes',
        'recorded_by',
    ];

    // Get all payments for a sale
    public function getBySale(int $saleId)
    {
        return $this->select('utang_payments.*, users.name as recorder_name')
                    ->join('users',
                           'users.id = utang_payments.recorded_by', 'left')
                    ->where('utang_payments.sale_id', $saleId)
                    ->orderBy('utang_payments.created_at', 'ASC')
                    ->findAll();
    }

    // Total paid for a sale
    public function totalPaidBySale(int $saleId)
    {
        $db = \Config\Database::connect();
        $row = $db->table($this->table)
                  ->selectSum('amount', 'total')
                  ->where('sale_id', $saleId)
                  ->get()
                  ->getRow();
        return $row->total ?? 0;
    }

    // Get all unpaid/partial utang
    public function getAllUtang()
    {
        return $this->db->query(
            "SELECT s.*,
                    u.name AS cashier_name,
                    COALESCE(SUM(up.amount), 0) AS paid_amount,
                    (s.total - COALESCE(SUM(up.amount), 0)) AS balance
             FROM sales s
             LEFT JOIN users u  ON u.id = s.user_id
             LEFT JOIN utang_payments up ON up.sale_id = s.id
             WHERE s.payment_method = 'utang'
             GROUP BY s.id
             ORDER BY s.created_at DESC"
        )->getResultArray();
    }
}