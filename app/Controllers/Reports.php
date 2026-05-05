<?php


namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;

class Reports extends BaseController
{
    public function index()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;
        return redirect()->to('/reports/sales');
    }

    public function sales()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $saleModel = new SaleModel();
        $from = $this->request->getGet('from') ?? date('Y-m-01');
        $to   = $this->request->getGet('to')   ?? date('Y-m-d');

        $sales = $saleModel->select('sales.*, users.name as cashier_name')
                           ->join('users', 'users.id = sales.user_id', 'left')
                           ->where('DATE(sales.created_at) >=', $from)
                           ->where('DATE(sales.created_at) <=', $to)
                           ->orderBy('sales.created_at', 'DESC')
                           ->findAll();

        $completed = array_filter($sales, fn($s) => $s['status'] === 'completed');
        $totalRev  = array_sum(array_column(iterator_to_array(
                         new \ArrayIterator($completed)), 'total'));

        $data = [
            'title'    => 'Sales Report',
            'sales'    => $sales,
            'totalRev' => $totalRev,
            'from'     => $from,
            'to'       => $to,
        ];
        return view('reports/sales', $data);
    }

    public function inventory()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $productModel = new ProductModel();

        $data = [
            'title'    => 'Inventory Report',
            'products' => $productModel->getAllWithCategory(),
            'totalVal' => $productModel->totalInventoryValue(),
        ];
        return view('reports/inventory', $data);
    }
}