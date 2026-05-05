<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $data = [
            'title'      => 'Categories',
            'categories' => $this->categoryModel->withProductCount(),
        ];
        return view('categories/index', $data);
    }

    public function store()
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $rules = ['name' => 'required|min_length[2]|max_length[100]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/categories')->with('success', 'Category added!');
    }

    public function update(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $this->categoryModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/categories')->with('success', 'Category updated!');
    }

    public function delete(int $id)
    {
        $redirect = $this->checkOwner();
        if ($redirect) return $redirect;

        $this->categoryModel->delete($id);
        return redirect()->to('/categories')->with('success', 'Category deleted.');
    }
}