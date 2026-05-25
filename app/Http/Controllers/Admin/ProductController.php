<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        $product = Product::create($data);
        if (!empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Tạo sản phẩm thành công.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('categories');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);

        $product->update($data);
        $product->categories()->sync($data['category_ids'] ?? []);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
            'is_builder' => 'sometimes|boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
        ]);
    }
}
