<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();
        return view('layouts.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('layouts.articles.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
    ]);

    $data = $request->only('title', 'content');

    if ($request->hasFile('thumbnail')) {
        $thumbnailName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
        $request->file('thumbnail')->move(public_path('uploads/uploads/blogs'), $thumbnailName);
        $data['thumbnail'] = 'uploads/uploads/blogs/' . $thumbnailName;
    }

    Article::create($data);

    return redirect()->route('admin.articles.index')->with('success', 'Article added!');
}


    public function edit(Article $article)
    {
        return view('layouts.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
    ]);

    $data = $request->only('title', 'content');

    if ($request->hasFile('thumbnail')) {
        // Delete old image if it exists
        if ($article->thumbnail && file_exists(public_path($article->thumbnail))) {
            unlink(public_path($article->thumbnail));
        }

        $thumbnailName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
        $request->file('thumbnail')->move(public_path('uploads/uploads/blogs'), $thumbnailName);
        $data['thumbnail'] = 'uploads/uploads/blogs/' . $thumbnailName;
    }

    $article->update($data);

    return redirect()->route('admin.articles.index')->with('success', 'Article updated!');
}


    public function destroy(Article $article)
    {
        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted!');
    }

public function showOnHome()
{
    $articles = Article::latest()->take(6)->get();
    return view('medicals/index', compact('article'));
}
public function show($id)
{
    $article = Article::findOrFail($id);
    return view('layouts.articles.show', compact('article'));
}

public function ind()
{
    $articles = Article::latest()->get(); // or paginate if needed
    return view('layouts.articles.allarticles', compact('articles'));
}


}
