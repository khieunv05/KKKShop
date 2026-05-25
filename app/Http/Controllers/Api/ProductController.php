<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'components'])->paginate(12);
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['categories', 'components']);
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);

        if (!empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        return (new ProductResource($product->load(['categories', 'components'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $product->update($data);

        if (array_key_exists('category_ids', $data)) {
            $product->categories()->sync($data['category_ids'] ?? []);
        }

        return new ProductResource($product->load(['categories', 'components']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
