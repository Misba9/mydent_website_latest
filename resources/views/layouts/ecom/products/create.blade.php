@extends('layouts.app')
@section('title')
    {{ isset($product) ? 'Edit Product' : 'Add Product' }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">Back</a>
        </div>

        <div class="card shadow-sm border-0 rounded-15">
            <div class="card-body p-6">
                @if ($errors->any())
                    <div class="alert alert-danger mb-5">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label required">Product Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name ?? '') }}" required placeholder="Enter product name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6">
                            <label class="form-label required">Category</label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                                   value="{{ old('category', $product->category ?? '') }}" required placeholder="e.g. Aligners, Whitening">
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Price --}}
                        <div class="col-md-4">
                            <label class="form-label required">Price (₹)</label>
                            <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price ?? '') }}" required placeholder="0.00">
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Discount Price --}}
                        <div class="col-md-4">
                            <label class="form-label">Discount Price (₹)</label>
                            <input type="number" name="discount_price" step="0.01" class="form-control @error('discount_price') is-invalid @enderror"
                                   value="{{ old('discount_price', $product->discount_price ?? '') }}" placeholder="Optional">
                            @error('discount_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-4">
                            <label class="form-label required">Quantity</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', $product->quantity ?? '') }}" required min="0">
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label required">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Product description...">{{ old('description', $product->description ?? '') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Thumbnail --}}
                        <div class="col-md-6">
                            <label class="form-label">Thumbnail Image</label>
                            @if(isset($product) && $product->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset($product->thumbnail) }}" width="80" class="img-thumbnail" alt="thumbnail">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>

                        {{-- Category Thumbnail --}}
                        <div class="col-md-6">
                            <label class="form-label">Category Thumbnail</label>
                            @if(isset($product) && $product->category_thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset($product->category_thumbnail) }}" width="80" class="img-thumbnail" alt="cat-thumb">
                                </div>
                            @endif
                            <input type="file" name="category_thumbnail" class="form-control" accept="image/*">
                        </div>

                        {{-- Extra Images --}}
                        <div class="col-12">
                            <label class="form-label">Additional Images</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images</small>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($product) ? 'Update Product' : 'Save Product' }}
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
