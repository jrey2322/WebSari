<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }


    public function index()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $data = [
            'title'      => 'Products',
            'products'   => $this->productModel->getAllWithCategory(),
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('products/index', $data);
    }

    public function create()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        // Staff cannot add products - only owner
        if ($this->session->get('user_role') === 'staff') {
            return redirect()->to('/products')
                             ->with('error', 'Only the owner can add products.');
        }

        $data = [
            'title'      => 'Add Product',
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('products/create', $data);
    }

    public function store()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $rules = [
            'name'       => 'required|min_length[2]',
            'price'      => 'required|numeric',
            'cost_price' => 'required|numeric',
            'stock'      => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $imageName = null;
        $image     = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads/products', $imageName);
        }

        $this->productModel->insert([
            'category_id'     => $this->request->getPost('category_id') ?: null,
            'name'            => $this->request->getPost('name'),
            'description'     => $this->request->getPost('description'),
            'barcode'         => $this->request->getPost('barcode'),
            'price'           => $this->request->getPost('price'),
            'cost_price'      => $this->request->getPost('cost_price'),
            'stock'           => $this->request->getPost('stock'),
            'low_stock_alert' => $this->request->getPost('low_stock_alert') ?: 5,
            'unit'            => $this->request->getPost('unit') ?: 'pcs',
            'image'           => $imageName,
            'status'          => 'active',
        ]);

        return redirect()->to('/products')
                         ->with('success', 'Product added successfully!');
    }

    public function edit(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')
                             ->with('error', 'Product not found.');
        }

        $data = [
            'title'      => 'Edit Product',
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('products/edit', $data);
    }

    public function update(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $rules = [
            'name'       => 'required|min_length[2]',
            'price'      => 'required|numeric',
            'cost_price' => 'required|numeric',
            'stock'      => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $product   = $this->productModel->find($id);
        $imageName = $product['image'];
        $image     = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Remove old image
            if ($imageName &&
                file_exists(ROOTPATH . 'public/uploads/products/' . $imageName)) {
                unlink(ROOTPATH . 'public/uploads/products/' . $imageName);
            }
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads/products', $imageName);
        }

        $this->productModel->update($id, [
            'category_id'     => $this->request->getPost('category_id') ?: null,
            'name'            => $this->request->getPost('name'),
            'description'     => $this->request->getPost('description'),
            'barcode'         => $this->request->getPost('barcode'),
            'price'           => $this->request->getPost('price'),
            'cost_price'      => $this->request->getPost('cost_price'),
            'stock'           => $this->request->getPost('stock'),
            'low_stock_alert' => $this->request->getPost('low_stock_alert') ?: 5,
            'unit'            => $this->request->getPost('unit') ?: 'pcs',
            'image'           => $imageName,
            'status'          => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to('/products')
                         ->with('success', 'Product updated successfully!');
    }

    public function delete(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $product = $this->productModel->find($id);
        if ($product) {
            if ($product['image'] &&
                file_exists(ROOTPATH . 'public/uploads/products/' . $product['image'])) {
                unlink(ROOTPATH . 'public/uploads/products/' . $product['image']);
            }
            $this->productModel->update($id, ['status' => 'inactive']);
        }

        return redirect()->to('/products')
                         ->with('success', 'Product removed.');
    }

    public function restock()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $id  = $this->request->getPost('product_id');
        $qty = intval($this->request->getPost('quantity'));

        if (!$id || $qty <= 0) {
            return redirect()->back()->with('error', 'Invalid restock quantity.');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $newStock = $product['stock'] + $qty;
        $this->productModel->update($id, ['stock' => $newStock]);

        return redirect()->to('/products')

                         ->with('success', "✅ Added {$qty} {$product['unit']} to " . esc($product['name']));
    }

    public function lowStock()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $data = [
            'title'    => 'Low Stock Alert',
            'products' => $this->productModel->getLowStock(),
        ];
        return view('products/low_stock', $data);
    }

    public function search()
    {
        $redirect = $this->checkSession();
        if ($redirect) return $redirect;

        $keyword  = $this->request->getGet('q') ?? '';
        $products = $this->productModel->search($keyword);
        return $this->response->setJSON($products);
    }
}