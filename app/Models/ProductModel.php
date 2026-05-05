<?php
// app/Models/ProductModel.php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'category_id','name','description','barcode',
        'price','cost_price','stock','low_stock_alert',
        'unit','image','status'
    ];
    protected $useTimestamps = true;

    // All active products with category
    public function getAllWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories',
                           'categories.id = products.category_id', 'left')
                    ->where('products.status', 'active')
                    ->orderBy('products.name', 'ASC')
                    ->findAll();
    }

    // Search by name or barcode
    public function search(string $keyword)
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories',
                           'categories.id = products.category_id', 'left')
                    ->groupStart()
                        ->like('products.name',    $keyword)
                        ->orLike('products.barcode', $keyword)
                    ->groupEnd()
                    ->where('products.status', 'active')
                    ->where('products.stock >', 0)
                    ->findAll();
    }

    // Low stock items
    public function getLowStock()
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories',
                           'categories.id = products.category_id', 'left')
                    ->where('products.status', 'active')
                    ->where('products.stock <= products.low_stock_alert', null, false)
                    ->orderBy('products.stock', 'ASC')
                    ->findAll();
    }

    // Deduct stock
    public function deductStock(int $productId, int $qty)
    {
        $product = $this->find($productId);
        if ($product) {
            $newStock = max(0, $product['stock'] - $qty);
            return $this->update($productId, ['stock' => $newStock]);
        }
        return false;
    }

    // Total inventory value (cost)
    public function totalInventoryValue()
    {
        $r = $this->selectSum('stock * cost_price', 'total')
                  ->where('status', 'active')
                  ->first();
        return $r['total'] ?? 0;
    }

    // Count active products
    public function countActive()
    {
        return $this->where('status', 'active')->countAllResults();
    }
}