<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;

class Sales extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $productModel;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->productModel  = new ProductModel();
    }

    public function index()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Sales History',
            'sales' => $this->saleModel->getAllWithCashier(),
        ];
        return view('sales/index', $data);
    }

    public function create()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $data = [
            'title'    => 'New Sale (POS)',
            'products' => $this->productModel->getAllWithCategory(),
        ];
        return view('sales/create', $data);
    }

    public function store()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $items = json_decode($this->request->getPost('items'), true);

        if (empty($items)) {
            return redirect()->back()
                             ->with('error', 'Cart is empty! Add items first.');
        }

        // Stock validation
        foreach ($items as $item) {
            $product = $this->productModel->find($item['product_id']);
            if (!$product) {
                return redirect()->back()
                                 ->with('error', 'Product not found.');
            }
            if ($product['stock'] < $item['quantity']) {
                return redirect()->back()
                                 ->with('error',
                                     'Not enough stock for: ' . $product['name'] .
                                     ' (Available: ' . $product['stock'] . ')');
            }
        }

        $subtotal      = (float) array_sum(array_column($items, 'subtotal'));
        $discount      = (float) ($this->request->getPost('discount') ?? 0);
        $total         = max(0, $subtotal - $discount);
        $paymentMethod = $this->request->getPost('payment_method') ?? 'cash';
        $paid          = $paymentMethod === 'utang'
                            ? 0
                            : (float) ($this->request->getPost('amount_paid') ?? 0);
        $change        = max(0, $paid - $total);
        $status        = $paymentMethod === 'utang' ? 'utang' : 'completed';

        $db = \Config\Database::connect();
        $db->transStart();

        $saleId = $this->saleModel->insert([
            'invoice_no'    => $this->saleModel->generateInvoice(),
            'user_id'       => $this->session->get('user_id'),
            'customer_name' => $this->request->getPost('customer_name') ?: 'Walk-in',
            'subtotal'      => $subtotal,
            'discount'      => $discount,
            'total'         => $total,
            'amount_paid'   => $paid,
            'change_amount' => $change,
            'payment_method'=> $paymentMethod,
            'status'        => $status,
            'notes'         => $this->request->getPost('notes'),
        ]);

        foreach ($items as $item) {
            $this->saleItemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);
            // Don't deduct stock for utang until paid
            if ($status !== 'utang') {
                $this->productModel->deductStock($item['product_id'], $item['quantity']);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                             ->with('error', 'Transaction failed. Please try again.');
        }

        return redirect()->to('/sales/view/' . $saleId)
                         ->with('success', 'Sale recorded successfully! 🎉');
    }

    public function view(int $id)
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->getWithDetails($id);
        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale not found.');
        }

        $data = [
            'title' => 'Sale Details',
            'sale'  => $sale,
        ];
        return view('sales/view', $data);
    }

    public function invoice(int $id)
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->getWithDetails($id);
        if (!$sale) {
            return redirect()->to('/sales')->with('error', 'Sale not found.');
        }

        return view('sales/invoice', ['title' => 'Invoice', 'sale' => $sale]);
    }

    public function void(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->find($id);
        if ($sale && $sale['status'] === 'completed') {
            // Restore stock
            $items = $this->saleItemModel->getBySale($id);
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);
                if ($product) {
                    $this->productModel->update($item['product_id'], [
                        'stock' => $product['stock'] + $item['quantity']
                    ]);
                }
            }
            $this->saleModel->update($id, ['status' => 'void']);
        }

        return redirect()->to('/sales')
                         ->with('success', 'Sale voided and stock restored.');
    }
}