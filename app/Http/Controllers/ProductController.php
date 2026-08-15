<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $products = Product::latest()->get();
    
    return view('layouts.ecom.products.index', compact('products'));
}

public function create()
{
    return view('layouts.ecom.products.create');
}

public function store(Request $request)
{
    
    $data = $request->validate([
        'name' => 'required',
        'description' => 'required',
        'category' => 'required',
        'price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'quantity' => 'required|integer',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20488',
        'category_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20488',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:20488'
    ]);
    
    $basePath = public_path('assets/front/images/product');
    if (!File::exists($basePath)) {
        File::makeDirectory($basePath, 0755, true);
    }

    if ($request->hasFile('thumbnail')) {
        $thumb = $request->file('thumbnail');
        $thumbName = time() . '_thumb_' . $thumb->getClientOriginalName();
        $thumb->move($basePath, $thumbName);
        $data['thumbnail'] = 'assets/front/images/product/' . $thumbName;
    }

    if ($request->hasFile('category_thumbnail')) {
        $catThumb = $request->file('category_thumbnail');
        $catThumbName = time() . '_cat_' . $catThumb->getClientOriginalName();
        $catThumb->move($basePath, $catThumbName);
        $data['category_thumbnail'] = 'assets/front/images/product/' . $catThumbName;
    }

    $images = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $imgName = time() . '_' . $img->getClientOriginalName();
            $img->move($basePath, $imgName);
            $images[] = 'assets/front/images/product/' . $imgName;
        }
    }

    $data['images'] = $images;

    Product::create($data);
    return redirect()->route('products.index') // Or wherever you want to redirect
    ->with('success', 'Product added successfully.');}

public function edit(Product $product)
{
    return view('layouts.ecom.products.create', compact('product'));
}

public function update(Request $request, Product $product)
{
    $data = $request->validate([
        'name' => 'required',
        'description' => 'nullable',
        'category' => 'nullable',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'image' => 'nullable|image',
    ]);

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);
    return redirect()->route('products.index')->with('success', 'Product updated!');
}

public function destroy(Product $product)
{
    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }
    $product->delete();
    return redirect()->back()->with('success', 'Product deleted!');
}

// ProductController.php
public function show($id)
{
    $product = Product::findOrFail($id);
    return view('fronts.product.show', compact('product'));
}


}
