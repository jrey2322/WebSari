<?php
// app/Models/SaleItemModel.php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table         = 'sale_items';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'sale_id','product_id','quantity','price','subtotal'
    ];
    protected $useTimestamps = false;

    public function getBySale(int $saleId)
    {
        return $this->select('sale_items.*, products.name as product_name,
                              products.unit, products.barcode')
                    ->join('products',
                           'products.id = sale_items.product_id', 'left')
                    ->where('sale_items.sale_id', $saleId)
                    ->findAll();
    }
}