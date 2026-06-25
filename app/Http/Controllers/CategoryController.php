<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm thành công.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.form', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->products()->detach();
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa.');
    }
}
