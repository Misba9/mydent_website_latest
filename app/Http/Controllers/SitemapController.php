<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Core Static Pages
        $urls[] = ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'];
        $urls[] = ['loc' => url('/about-us'), 'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => url('/aligners'), 'priority' => '0.9', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => url('/medical-appointment'), 'priority' => '0.8', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => url('/products'), 'priority' => '0.9', 'changefreq' => 'daily'];
        $urls[] = ['loc' => url('/blogs'), 'priority' => '0.8', 'changefreq' => 'daily'];
        $urls[] = ['loc' => url('/contact'), 'priority' => '0.5', 'changefreq' => 'monthly'];

        // Dynamic Blogs
        $blogs = Blog::all();
        foreach ($blogs as $blog) {
            $urls[] = [
                'loc' => route('blogs.show', $blog->id),
                'lastmod' => $blog->updated_at ? $blog->updated_at->toAtomString() : now()->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ];
        }

        // Dynamic Products
        $products = Product::all();
        foreach ($products as $product) {
            $urls[] = [
                'loc' => route('front.product.show', $product->id),
                'lastmod' => $product->updated_at ? $product->updated_at->toAtomString() : now()->toAtomString(),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ];
        }

        $content = view('seo.sitemap', compact('urls'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
