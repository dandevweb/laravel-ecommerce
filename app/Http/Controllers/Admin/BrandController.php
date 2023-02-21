<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Contracts\Support\Renderable;

class BrandController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Brand::class, 'brand');
    }

    public function index(Request $request): Renderable
    {
        $brands = QueryBuilder::for(Brand::class)
            ->allowedFilters([AllowedFilter::scope('search', 'whereScout')])
            ->paginate()
            ->appends($request->query());

        return view('admin.brands.index', [
            'brands' => $brands,
        ]);
    }

    public function create(): Renderable
    {
        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        Brand::create($request->validated());

        return to_route('admin.brands.index')->with(
            'success',
            'Brand was successfully created',
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    public function edit(Brand $brand): Renderable
    {
        return view('admin.brands.edit', [
            'brand' => $brand,
        ]);
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $brand->update($request->validated());

        return to_route('admin.brands.index')->with(
            'success',
            'Brand was successfully updated',
        );
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return to_route('admin.brands.index')->with(
            'success',
            'Brand was successfully deleted',
        );
    }
}
