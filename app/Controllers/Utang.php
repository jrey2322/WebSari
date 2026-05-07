<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\UtangModel;
use App\Models\ProductModel;

class Utang extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $utangModel;
    protected $productModel;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->utangModel    = new UtangModel();
        $this->productModel  = new ProductModel();
    }

    // ── Utang List ────────────────────────────────────────
    public function index()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $allUtang = $this->utangModel->getAllUtang();

        // Separate unpaid and fully paid
        $unpaid = [];
        $paid   = [];

        foreach ($allUtang as $u) {
            if ((float)$u['balance'] <= 0) {
                $paid[]   = $u;
            } else {
                $unpaid[] = $u;
            }
        }

        // Summary
        $totalDebt = array_sum(array_column($unpaid, 'balance'));

        $data = [
            'title'     => 'Utang Tracker',
            'unpaid'    => $unpaid,
            'paid'      => $paid,
            'totalDebt' => $totalDebt,
        ];

        return view('utang/index', $data);
    }

    // ── View single utang ─────────────────────────────────
    public function view(int $id)
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->getWithDetails($id);

        if (!$sale || $sale['status'] !== 'utang') {
            return redirect()->to(base_url('utang'))
                             ->with('error', 'Utang record not found.');
        }

        $payments  = $this->utangModel->getBySale($id);
        $totalPaid = $this->utangModel->totalPaidBySale($id);
        $balance   = $sale['total'] - $totalPaid;

        $data = [
            'title'     => 'Utang - ' . $sale['customer_name'],
            'sale'      => $sale,
            'payments'  => $payments,
            'totalPaid' => $totalPaid,
            'balance'   => $balance,
        ];

        return view('utang/view', $data);
    }

    // ── Record a payment ──────────────────────────────────
    public function pay(int $id)
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->find($id);

        if (!$sale || $sale['status'] !== 'utang') {
            return redirect()->to(base_url('utang'))
                             ->with('error', 'Sale not found.');
        }

        $amount = floatval($this->request->getPost('amount'));
        $notes  = $this->request->getPost('notes') ?? '';

        if ($amount <= 0) {
            return redirect()->to(base_url('utang/view/' . $id))
                             ->with('error', 'Amount must be greater than 0.');
        }

        // Get current balance
        $totalPaid = $this->utangModel->totalPaidBySale($id);
        $balance   = $sale['total'] - $totalPaid;

        if ($amount > $balance) {
            $amount = $balance; // Cap at balance
        }

        // Record payment
        $this->utangModel->builder()->insert([
            'sale_id'     => $id,
            'amount'      => $amount,
            'notes'       => $notes,
            'recorded_by' => $this->session->get('user_id'),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        // Recalculate
        $newTotalPaid = $this->utangModel->totalPaidBySale($id);
        $newBalance   = $sale['total'] - $newTotalPaid;

        // If fully paid, deduct stock and mark as completed
        if ($newBalance <= 0) {
            // Deduct stock now (was not deducted during utang sale)
            $items = $this->saleItemModel->getBySale($id);
            foreach ($items as $item) {
                $this->productModel->deductStock(
                    $item['product_id'],
                    $item['quantity']
                );
            }

            // Mark sale as completed
            $this->saleModel->where('id', $id)->set([
                'status'      => 'completed',
                'amount_paid' => $newTotalPaid,
            ])->update();

            return redirect()->to(base_url('utang'))
                             ->with('success',
                                 '✅ Utang fully paid! Sale marked as completed.');
        }

        // Partial payment - update amount_paid
        $this->saleModel->where('id', $id)->set([
            'amount_paid' => $newTotalPaid,
        ])->update();

        return redirect()->to(base_url('utang/view/' . $id))
                         ->with('success',
                             '💰 Payment of ₱' . number_format($amount, 2) .
                             ' recorded! Remaining balance: ₱' .
                             number_format($newBalance, 2));
    }

    // ── Mark as fully paid (manual override) ─────────────
    public function markPaid(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->find($id);

        if (!$sale) {
            return redirect()->to(base_url('utang'))
                             ->with('error', 'Sale not found.');
        }

        // Deduct stock
        $items = $this->saleItemModel->getBySale($id);
        foreach ($items as $item) {
            $this->productModel->deductStock(
                $item['product_id'],
                $item['quantity']
            );
        }

        $this->saleModel->where('id', $id)->set([
            'status'      => 'completed',
            'amount_paid' => $sale['total'],
        ])->update();

        return redirect()->to(base_url('utang'))
                         ->with('success', '✅ Utang marked as fully paid!');
    }
}