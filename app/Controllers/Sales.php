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

        // ✅ Get raw post data
        $rawItems = $this->request->getPost('items');

        // ✅ Debug: log what we received
        log_message('debug', 'Sales::store - raw items: ' . $rawItems);

        if (empty($rawItems)) {
            return redirect()->to(base_url('sales/create'))
                             ->with('error', 'Cart is empty! Add items first.');
        }

        // ✅ Decode JSON
        $items = json_decode($rawItems, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->to(base_url('sales/create'))
                             ->with('error', 'Invalid cart data. Please try again.');
        }

        if (empty($items) || !is_array($items)) {
            return redirect()->to(base_url('sales/create'))
                             ->with('error', 'Cart is empty! Add items first.');
        }

        // ✅ Validate stock for each item
        foreach ($items as $item) {
            if (empty($item['product_id']) || empty($item['qty'])) {
                continue;
            }

            $product = $this->productModel->find($item['product_id']);

            if (!$product) {
                return redirect()->to(base_url('sales/create'))
                                 ->with('error', 'Product not found.');
            }

            if ($product['stock'] < $item['qty']) {
                return redirect()->to(base_url('sales/create'))
                                 ->with('error',
                                     'Not enough stock for: ' . $product['name'] .
                                     ' (Available: ' . $product['stock'] . ')');
            }
        }

        // ✅ Calculate totals
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += floatval($item['subtotal']);
        }

        $discount      = floatval($this->request->getPost('discount') ?? 0);
        $total         = max(0, $subtotal - $discount);
        $paymentMethod = $this->request->getPost('payment_method') ?? 'cash';
        $customerName  = $this->request->getPost('customer_name') ?: 'Walk-in Customer';
        $notes         = $this->request->getPost('notes') ?? '';

        $amountPaid = $paymentMethod === 'utang'
            ? 0
            : floatval($this->request->getPost('amount_paid') ?? 0);

        $changeAmount = max(0, $amountPaid - $total);
        $status       = $paymentMethod === 'utang' ? 'pending' : 'completed';

        // ✅ Start DB transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert sale record
            $saleId = $this->saleModel->insert([
                'invoice_no'     => $this->saleModel->generateInvoice(),
                'user_id'        => $this->session->get('user_id'),
                'customer_name'  => $customerName,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'amount_paid'    => $amountPaid,
                'change_amount'  => $changeAmount,
                'payment_method' => $paymentMethod,
                'status'         => $status,
                'notes'          => $notes,
            ]);

            if (!$saleId) {
                throw new \Exception('Failed to create sale record.');
            }

            // Insert each sale item & deduct stock
            foreach ($items as $item) {
                // ✅ Support both 'qty' and 'quantity' keys
                $qty = isset($item['qty']) ? intval($item['qty']) : intval($item['quantity'] ?? 0);
            
                $this->saleItemModel->insert([
                    'sale_id'    => $saleId,
                    'product_id' => intval($item['product_id']),
                    'quantity'   => $qty, 
                    'price'      => floatval($item['price']),
                    'subtotal'   => floatval($item['subtotal']),
                ]);
            
                // Always deduct stock regardless of payment method
                $this->productModel->deductStock(
                    intval($item['product_id']),
                    $qty
                );
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }

            return redirect()->to(base_url('sales/view/' . $saleId))
                             ->with('success', 'Sale completed! 🎉');


        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Sales::store error: ' . $e->getMessage());
            return redirect()->to(base_url('sales/create'))
                             ->with('error', 'Sale failed: ' . $e->getMessage());
        }
    }

    public function view(int $id)
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->getWithDetails($id);

        if (!$sale) {
            return redirect()->to(base_url('sales'))
                             ->with('error', 'Sale not found.');
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
            return redirect()->to(base_url('sales'))
                             ->with('error', 'Sale not found.');
        }

        return view('sales/invoice', [
            'title' => 'Invoice',
            'sale'  => $sale,
        ]);
    }

    public function void(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $sale = $this->saleModel->find($id);

        if ($sale && $sale['status'] === 'completed') {
            $items = $this->saleItemModel->getBySale($id);
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);
                if ($product) {
                    $this->productModel->update($item['product_id'], [
                        'stock' => $product['stock'] + $item['quantity'],
                    ]);
                }
            }
            $this->saleModel->update($id, ['status' => 'void']);
        }

        return redirect()->to(base_url('sales'))
                         ->with('success', 'Sale voided and stock restored.');
    }
}
