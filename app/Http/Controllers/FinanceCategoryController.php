<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();
        return view('finance.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:finance_categories',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string',
        ]);

        FinanceCategory::create($validated);

        return redirect()->route('finance.categories.index')
            ->with('success', 'Finance category created successfully.');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = FinanceCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:finance_categories,name,' . $id,
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('finance.categories.index')
            ->with('success', 'Finance category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = FinanceCategory::findOrFail($id);

        // Prevent deletion if transactions exist
        if ($category->transactions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated transactions.');
        }

        $category->delete();

        return redirect()->route('finance.categories.index')
            ->with('success', 'Finance category deleted successfully.');
    }
}
