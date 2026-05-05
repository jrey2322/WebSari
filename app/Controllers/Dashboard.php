<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $productModel  = new ProductModel();
        $saleModel     = new SaleModel();
        $categoryModel = new CategoryModel();
        $userModel     = new UserModel();

        // Build chart data
        $monthly      = $saleModel->monthlyRevenue();
        $months       = ['Jan','Feb','Mar','Apr','May','Jun',
                         'Jul','Aug','Sep','Oct','Nov','Dec'];
        $chartLabels  = $months;
        $chartData    = array_fill(0, 12, 0);

        foreach ($monthly as $m) {
            $chartData[(int)$m['month'] - 1] = (float)$m['revenue'];
        }

        $data = [
            'title'          => 'Dashboard',
            'totalProducts'  => $productModel->countActive(),
            'totalCategories'=> count($categoryModel->findAll()),
            'todayCount'     => count($saleModel->todaySales()),
            'todayRevenue'   => $saleModel->todayRevenue(),
            'inventoryValue' => $productModel->totalInventoryValue(),
            'lowStockItems'  => $productModel->getLowStock(),
            'recentSales'    => $saleModel->getAllWithCashier(),
            'totalStaff'     => count($userModel->getActiveUsers()),
            'chartLabels'    => json_encode($chartLabels),
            'chartData'      => json_encode($chartData),
        ];

        return view('dashboard/index', $data);
    }
}