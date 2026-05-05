<?php
// app/Models/SaleModel.php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table         = 'sales';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'invoice_no', 'user_id', 'customer_name',
        'subtotal', 'discount', 'total',
        'amount_paid', 'change_amount',
        'payment_method', 'status', 'notes',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function generateInvoice()
    {
        $prefix = 'WS-' . date('Ymd') . '-';
        $last   = $this->like('invoice_no', $prefix)
                       ->orderBy('id', 'DESC')
                       ->first();
        $num = $last
            ? (int) substr($last['invoice_no'], -4) + 1
            : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function getAllWithCashier()
    {
        return $this->select('sales.*, users.name as cashier_name')
                    ->join('users', 'users.id = sales.user_id', 'left')
                    ->orderBy('sales.created_at', 'DESC')
                    ->findAll();
    }

    public function getWithDetails(int $id)
    {
        $sale = $this->select('sales.*, users.name as cashier_name')
                     ->join('users', 'users.id = sales.user_id', 'left')
                     ->find($id);

        if ($sale) {
            $itemModel     = new SaleItemModel();
            $sale['items'] = $itemModel->getBySale($id);
        }
        return $sale;
    }

    public function todaySales()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))
                    ->where('status', 'completed')
                    ->findAll();
    }

    public function todayRevenue()
    {
        $r = $this->selectSum('total', 'revenue')
                  ->where('DATE(created_at)', date('Y-m-d'))
                  ->where('status', 'completed')
                  ->first();
        return $r['revenue'] ?? 0;
    }

    public function monthlyRevenue()
    {
        return $this->select('MONTH(created_at) as month, SUM(total) as revenue')
                    ->where('YEAR(created_at)', date('Y'))
                    ->where('status', 'completed')
                    ->groupBy('MONTH(created_at)')
                    ->orderBy('month', 'ASC')
                    ->findAll();
    }

    public function revenueByRange(string $from, string $to)
    {
        $r = $this->selectSum('total', 'revenue')
                  ->where('DATE(created_at) >=', $from)
                  ->where('DATE(created_at) <=', $to)
                  ->where('status', 'completed')
                  ->first();
        return $r['revenue'] ?? 0;
    }
}