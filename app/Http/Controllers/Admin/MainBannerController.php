<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainBanner;
use Illuminate\Support\Facades\Storage;

class MainBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
    $mainBanners = MainBanner::all();
    return view('layouts.main_banners.index', compact('mainBanners'));
}

public function create() {
    return view('layouts.main_banners.create');
}

public function store(Request $request)
{
    $data = $request->validate([
        'page' => 'required|string',
        'title' => 'nullable|string',
        'image' => 'required|image|max:2048',
    ]);

    // Use same logic as in HomepageVideoController, but for public/uploads/vids
    $image = $request->file('image');
    $filename = uniqid() . '.' . $image->getClientOriginalExtension();
    $destinationPath = public_path('uploads/vids'); // same folder as videos
    $image->move($destinationPath, $filename);

    $data['image'] = 'uploads/vids/' . $filename; // Save relative public path (no "storage")

    MainBanner::create($data);

    return redirect()->route('main-banners.index')->with('success', 'Banner added');
}

public function edit(MainBanner $mainBanner) {
    return view('layouts.main_banners.edit', compact('mainBanner'));
}

public function update(Request $request, MainBanner $mainBanner) {
    $data = $request->validate([
        'page' => 'required|string',

        'title' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $oldFile = str_replace('/storage', 'public', $mainBanner->image);
        Storage::delete($oldFile);

        $path = $request->file('image')->store('public/vids');
        $data['image'] = Storage::url($path);
    }

    $mainBanner->update($data);
    return redirect()->route('main-banners.index')->with('success', 'Banner updated');
}


public function destroy(MainBanner $mainBanner) {
    $file = str_replace('/storage', 'public', $mainBanner->image);
    Storage::delete($file);
    $mainBanner->delete();

    return redirect()->route('main-banners.index')->with('success', 'Banner deleted');
}

}
