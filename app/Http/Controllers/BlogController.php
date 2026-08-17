<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;


class BlogController extends Controller
{
    //
    public function create()
{
    return view('layouts.blog.create');
}

public function index()
{
    $blogs = Blog::latest()->get(); // Get all blogs sorted by latest first
    return view('layouts.blog.index', compact('blogs'));
}


public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        'issue_type' => 'required|string|max:255',
        'issue_level' => 'required|in:Simple,Moderate,Severe',
        'treatment_time' => 'required|string|max:255',
        'aligner_count' => 'required|integer|min:1',
        'content' => 'required|string',
    ]);

    // Handle image upload
    $thumbnailName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
    $request->file('thumbnail')->move(public_path('uploads/blogs'), $thumbnailName);
    $thumbnailPath = 'uploads/blogs/' . $thumbnailName;


    // Create the blog
    Blog::create([
        'title' => $request->title,
        'thumbnail' => $thumbnailPath,
        'issue_type' => $request->issue_type,
        'issue_level' => $request->issue_level,
        'treatment_time' => $request->treatment_time,
        'aligner_count' => $request->aligner_count,
        'content' => $request->content,
    ]);

    return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
}
// Show the edit form
public function edit(Blog $blog)
{
    return view('layouts.blog.edit', compact('blog'));
}

// Handle blog update
public function update(Request $request, Blog $blog)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'issue_type' => 'required|string|max:255',
        'issue_level' => 'required|in:Simple,Moderate,Severe',
        'treatment_time' => 'required|string|max:255',
        'aligner_count' => 'required|integer|min:1',
        'content' => 'required|string',
    ]);

    // If a new thumbnail is uploaded, replace the old one
    if ($request->hasFile('thumbnail')) {
        // Delete old one
        Storage::disk('public')->delete($blog->thumbnail);
        $blog->thumbnail = $request->file('thumbnail')->store('blogs', 'public');
    }

    $blog->update([
        'title' => $request->title,
        'issue_type' => $request->issue_type,
        'issue_level' => $request->issue_level,
        'treatment_time' => $request->treatment_time,
        'aligner_count' => $request->aligner_count,
        'content' => $request->content,
        'thumbnail' => $blog->thumbnail,
    ]);

    return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
}

// Handle blog deletion
public function destroy(Blog $blog)
{
    Storage::disk('public')->delete($blog->thumbnail);
    $blog->delete();

    return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
}

public function show(Blog $blog)
{
    return view('layouts.blog.show', compact('blog'));
}

}
