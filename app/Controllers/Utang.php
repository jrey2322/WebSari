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
            // Force strict balance check to avoid floating point issues
            $balance = round((float)$u['balance'], 2);
            // Move to paid if balance is zero OR status is completed
            if ($balance <= 0 || $u['status'] === 'completed') {
                $paid[]   = $u;
            } else {
                $unpaid[] = $u;
            }
        }

        // Summary
        $totalDebt      = array_sum(array_column($unpaid, 'balance'));
        $totalCollected = array_sum(array_column($allUtang, 'paid_amount'));

        $data = [
            'title'          => 'Pending Payments',
            'unpaid'         => $unpaid,
            'paid'           => $paid,
            'totalDebt'      => $totalDebt,
            'totalCollected' => $totalCollected,
        ];

        return view('utang/index', $data);
    }

    // ── View single utang ─────────────────────────────────
    public function view(int $id)
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->getWithDetails($id);

        if (!$sale || $sale['payment_method'] !== 'utang') {
            return redirect()->to(base_url('utang'))
                             ->with('error', 'Pending record not found.');
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

        if (!$sale || $sale['payment_method'] !== 'utang') {
            return redirect()->to(base_url('utang'))
                             ->with('error', 'Sale not found or invalid payment method.');
        }

        if ($sale['status'] === 'completed') {
            return redirect()->to(base_url('utang'))
                             ->with('error', 'This sale is already fully paid.');
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

        // If fully paid, mark as completed
        if ($newBalance <= 0) {
            $this->saleModel->where('id', $id)->set([
                'status'      => 'completed',
                'amount_paid' => $newTotalPaid,
            ])->update();

            return redirect()->to(base_url('utang'))
                             ->with('success', '✅ Utang fully paid! Sale marked as completed.');
        }

        // Partial payment - update amount_paid
        $this->saleModel->where('id', $id)->set([
            'amount_paid' => $newTotalPaid,
            'status'      => 'pending',
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

        // Calculate remaining balance to record as a final payment
        $totalPaid = $this->utangModel->totalPaidBySale($id);
        $remaining = $sale['total'] - $totalPaid;

        if ($remaining > 0) {
            // Record the final payment automatically
            $this->utangModel->builder()->insert([
                'sale_id'     => $id,
                'amount'      => $remaining,
                'notes'       => 'Full payment (Manual Override)',
                'recorded_by' => $this->session->get('user_id'),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $this->saleModel->where('id', $id)->set([
            'status'      => 'completed',
            'amount_paid' => $sale['total'],
        ])->update();

        return redirect()->to(base_url('utang'))
                         ->with('success', '✅ Sale marked as fully paid and moved to history!');
    }
}