<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function publicIndex(Request $request)
    {
        $allCategories = Category::orderBy('name')->get();

        $imageConfig = [
            'CPU'           => 'https://nguyencongpc.vn/media/product/250-25342-14700k.png',
            'Mainboard'     => 'https://nguyencongpc.vn/media/product/250-27886-mainboard-msi-b760m-gaming-wifi-ddr5-5.jpg',
            'PC Gaming'     => 'https://ttgshop.vn/media/product/250_1071871333_13110_dsc00342_copy_e15810bfa2c74f2ea64d272cd24e9da0.jpg',
            'PC Workstation' => 'https://ttgshop.vn/media/product/250_1071570206_pc_ttg_designer_3d_render_edit_video_i7_14700f_rtx_5060_ti_8gb_all_new_bao_hanh_36_thang1__1_.jpg',
            'RAM'           => 'https://nguyencongpc.vn/media/product/250-27096-ram-lexar-ares-rgb-32gb-2-16gb-ddr5-6000mhz-1.jpg',
            'SSD'           => 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/product/1/t/1tb_1.png',
        ];

        $categoryImageMap = [];
        foreach ($allCategories as $cat) {
            if (isset($imageConfig[$cat->name])) {
                $categoryImageMap[$cat->id] = $imageConfig[$cat->name];
            }
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $products = Product::with('categories')
                ->where('is_active', true)
                ->where(function ($builder) use ($keyword) {
                    $builder->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                })
                ->orderByDesc('created_at')
                ->paginate(12)
                ->withQueryString();

            return view('products.index', compact('products', 'allCategories', 'categoryImageMap'));
        }

        if ($request->filled('category')) {
            $cat = Category::findOrFail($request->input('category'));
            $products = $cat->products()
                ->where('is_active', true)
                ->orderByDesc('created_at')
                ->paginate(12)
                ->withQueryString();

            return view('products.index', compact('products', 'allCategories', 'cat', 'categoryImageMap'));
        }

        $categoryGroups = Category::with(['products' => function ($query) {
            $query->where('is_active', true)->orderByDesc('created_at');
        }])->orderBy('name')->get();

        return view('products.index', compact('categoryGroups', 'allCategories', 'categoryImageMap'));
    }

    public function index(Request $request)
    {
        $query = Product::with('categories')->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($builder) use ($request) {
                $builder->where('categories.id', $request->input('category'));
            });
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.store', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $request->hasFile('image') ? $request->file('image')->store('products', 'public') : null;

        $product = Product::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'old_price' => $data['old_price'] ?? null,
            'stock' => $data['stock'],
            'warranty' => $data['warranty'],
            'image' => $data['image'],
            'is_active' => true,
        ]);

        $product->categories()->sync($data['categories']);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
    }

    public function edit($id)
    {
        $product = Product::with('categories')->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            $data['image'] = $product->image;
        }

        $product->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'old_price' => $data['old_price'] ?? null,
            'stock' => $data['stock'],
            'warranty' => $data['warranty'],
            'image' => $data['image'],
        ]);

        $product->categories()->sync($data['categories']);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->categories()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa.');
    }

    public function suggestions(Request $request)
    {
        $keyword = $request->input('q');

        if (!$keyword || strlen(trim($keyword)) < 1) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where('name', 'like', '%' . $keyword . '%')
            ->limit(8)
            ->get(['id', 'name', 'price', 'image']);

        $products->transform(function ($product) {
            $product->image_url = $product->image ? Storage::url($product->image) : null;
            return $product;
        });

        return response()->json($products);
    }
}
