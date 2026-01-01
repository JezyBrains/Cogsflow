<?php

namespace App\Controllers;

use App\Models\ExpenseCategoryModel;

class ExpenseCategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new ExpenseCategoryModel();
        helper(['currency']);
    }

    /**
     * Display list of expense categories
     */
    public function index()
    {
        $data = [
            'categories' => $this->categoryModel->getCategoryStats(),
            'title' => 'Expense Categories'
        ];

        return view('expenses/categories/index', $data);
    }

    /**
     * Display form to create new category
     */
    public function create()
    {
        return view('expenses/categories/create');
    }

    /**
     * Store new category
     */
    public function store()
    {
        $rules = [
            'name' => 'required|max_length[100]|is_unique[expense_categories.name]',
            'description' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'is_active' => 1,
        ];

        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/expenses/categories')->with('success', 'Category created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create category. Please try again.');
    }

    /**
     * Display form to edit category
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/expenses/categories')->with('error', 'Category not found.');
        }

        $data = [
            'category' => $category,
            'title' => 'Edit Category'
        ];

        return view('expenses/categories/edit', $data);
    }

    /**
     * Update category
     */
    public function update($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/expenses/categories')->with('error', 'Category not found.');
        }

        $rules = [
            'name' => "required|max_length[100]|is_unique[expense_categories.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/expenses/categories')->with('success', 'Category updated successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update category. Please try again.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        if ($this->categoryModel->toggleStatus($id)) {
            return redirect()->to('/expenses/categories')->with('success', 'Category status updated successfully!');
        }

        return redirect()->to('/expenses/categories')->with('error', 'Failed to update category status.');
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        // Check if category can be deleted
        if (!$this->categoryModel->canDelete($id)) {
            return redirect()->to('/expenses/categories')
                           ->with('error', 'Cannot delete category. It has associated expenses.');
        }

        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/expenses/categories')->with('success', 'Category deleted successfully!');
        }

        return redirect()->to('/expenses/categories')->with('error', 'Failed to delete category.');
    }

    /**
     * Get active categories (AJAX)
     */
    public function getActive()
    {
        $categories = $this->categoryModel->getActiveCategories();
        return $this->response->setJSON($categories);
    }
}
