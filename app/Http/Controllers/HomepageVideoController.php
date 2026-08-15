<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageVideo;
use Illuminate\Support\Facades\Storage;

class HomepageVideoController extends Controller
{
    public function index()
    {
        $videos = HomepageVideo::orderBy('section')->orderBy('order')->get();
        return view('layouts.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('layouts.videos.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'section' => 'required|in:section-1,section-2,section-3,section-4,section-5,characteristics',
        'order' => 'nullable|integer',
        'video' => 'nullable|mimes:mp4,webm,ogg|max:51200',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Enforce at least one file based on section
    if ($request->section === 'characteristics' && !$request->hasFile('image')) {
        return back()->withErrors(['image' => 'Image is required for Our Characteristics.'])->withInput();
    }

    if ($request->section !== 'characteristics' && !$request->hasFile('video')) {
        return back()->withErrors(['video' => 'Video is required for this section.'])->withInput();
    }

    $videoPath = null;
    $imagePath = null;

    if ($request->hasFile('video')) {
        $videoPath = Storage::url($request->file('video')->store('public/vids'));
    }

    if ($request->hasFile('image')) {
        $imagePath = Storage::url($request->file('image')->store('public/images'));
    }

    HomepageVideo::create([
        'title' => $request->title,
        'section' => $request->section,
        'order' => $request->order,
        'video_path' => $videoPath,
        'image_path' => $imagePath,
    ]);

    return redirect()->route('homepage-videos.index')->with('success', 'Uploaded successfully.');
}



    public function destroy($id)
    {
        $video = HomepageVideo::findOrFail($id);
        $file = str_replace('/storage', 'public', $video->video_path); // convert back to storage path
        Storage::delete($file);
        $video->delete();

        return redirect()->route('homepage-videos.index')->with('success', 'Video deleted.');
    }
}

