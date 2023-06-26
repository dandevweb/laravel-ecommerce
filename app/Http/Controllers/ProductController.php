<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([AllowedFilter::scope('search', 'whereScout')])
            ->paginate()
            ->appends($request->query());

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): Renderable
    {
        return view('admin.products.create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create(
            $request
                ->safe()
                ->collect()
                ->filter(fn ($value) => !is_null($value))
                ->except(['images'])
                ->all(),
        );

        collect($request->validated('images'))->each(function ($image) use (
            $product,
        ) {
            $product->attachMedia(new File(storage_path('app/' . $image)));
            Storage::delete($image);
        });

        return to_route('admin.products.index')->with(
            'success',
            'Product was successfully created',
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update(
            $request
                ->safe()
                ->collect()
                ->filter(fn ($value) => !is_null($value))
                ->except(['images'])
                ->all(),
        );

        collect($request->validated('images'))->each(function ($image) use (
            $product,
        ) {
            $product->attachMedia(new File(storage_path('app/' . $image)));
            Storage::delete($image);
        });

        return to_route('admin.products.index')->with(
            'success',
            'Product was successfully updated',
        );
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return to_route('admin.products.index')->with(
            'success',
            'Product was successfully deleted',
        );
    }
}
