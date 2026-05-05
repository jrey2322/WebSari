<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'name',
        'description',
    ];

    // Get categories with product count
    public function withProductCount()
    {
        return $this->select('categories.*, COUNT(products.id) as product_count')
                    ->join(
                        'products',
                        'products.category_id = categories.id
                         AND products.status = "active"',
                        'left'
                    )
                    ->groupBy('categories.id')
                    ->orderBy('categories.name', 'ASC')
                    ->findAll();
    }
}