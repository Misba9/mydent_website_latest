<?php

namespace Database\Seeders;

use App\Models\HomepageVideo;
use App\Models\MainBanner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class WebsiteBrandImagesSeeder extends Seeder
{
    /**
     * Seed brand/content images into admin-managed Homepage Videos & Main Banners.
     */
    public function run(): void
    {
        $uploadsImages = public_path('uploads/images');
        $uploadsVids = public_path('uploads/vids');
        File::ensureDirectoryExists($uploadsImages);
        File::ensureDirectoryExists($uploadsVids);

        $sourceFiles = [
            'invisible.jpeg',
            'mydent-care.jpeg',
            'Supervision.jpeg',
            'smile-with-aligners.jpg',
            '3d-dental-scan.jpg',
            'confident-smile.jpg',
            'safe-aligner-material.jpg',
            'fda-quality-aligners.jpg',
            'Advance-technology.png',
            'Pay-after-approval.png',
            'Metal-braces.png',
            'Self-ligation-braces.jpeg',
        ];

        foreach ($sourceFiles as $file) {
            $from = public_path('images/' . $file);
            if (! File::exists($from)) {
                continue;
            }
            File::copy($from, $uploadsImages . '/' . $file);
            File::copy($from, $uploadsVids . '/' . $file);
        }

        $characteristicImages = [
            ['title' => 'Invisible Clear Aligners', 'file' => 'invisible.jpeg', 'order' => 1],
            ['title' => 'Aligner Care Kit', 'file' => 'mydent-care.jpeg', 'order' => 2],
            ['title' => 'Expert Supervision', 'file' => 'Supervision.jpeg', 'order' => 3],
            ['title' => 'Smile with Aligners', 'file' => 'smile-with-aligners.jpg', 'order' => 4],
            ['title' => '3D Dental Scan', 'file' => '3d-dental-scan.jpg', 'order' => 5],
            ['title' => 'Confident Smile', 'file' => 'confident-smile.jpg', 'order' => 6],
            ['title' => 'Safe Aligner Materials', 'file' => 'safe-aligner-material.jpg', 'order' => 7],
            ['title' => 'FDA Quality Aligners', 'file' => 'fda-quality-aligners.jpg', 'order' => 8],
            ['title' => 'Advanced Technology', 'file' => 'Advance-technology.png', 'order' => 9],
            ['title' => 'Pay After Approval', 'file' => 'Pay-after-approval.png', 'order' => 10],
        ];

        foreach ($characteristicImages as $item) {
            HomepageVideo::updateOrCreate(
                [
                    'section' => 'characteristics',
                    'title' => $item['title'],
                ],
                [
                    'order' => $item['order'],
                    'image_path' => 'uploads/images/' . $item['file'],
                    'video_path' => null,
                ]
            );
        }

        // Remove legacy characteristics that still point at missing logo placeholders
        HomepageVideo::where('section', 'characteristics')
            ->where(function ($q) {
                $q->whereNull('image_path')
                    ->orWhere('image_path', 'like', '%logo.png');
            })
            ->delete();

        // Page keys must match front Blade MainBanner queries
        $banners = [
            ['page' => 'why-mydent', 'title' => 'Why mydent', 'image' => 'uploads/vids/invisible.jpeg'],
            ['page' => 'smileverse', 'title' => 'Types of Braces & Aligners', 'image' => 'uploads/vids/mydent-care.jpeg'],
            ['page' => 'alligners', 'title' => 'Clear Aligners', 'image' => 'uploads/vids/invisible.jpeg'],
            ['page' => 'centre', 'title' => 'Visit Our Centre', 'image' => 'uploads/vids/Supervision.jpeg'],
            ['page' => 'products', 'title' => 'Aligner Care Products', 'image' => 'uploads/vids/mydent-care.jpeg'],
            ['page' => 'why-mydent', 'title' => 'Expert Supervision', 'image' => 'uploads/vids/Supervision.jpeg'],
            ['page' => 'smileverse', 'title' => 'Confident Smile Results', 'image' => 'uploads/vids/confident-smile.jpg'],
            ['page' => 'alligners', 'title' => '3D Dental Scan', 'image' => 'uploads/vids/3d-dental-scan.jpg'],
        ];

        foreach ($banners as $banner) {
            MainBanner::updateOrCreate(
                [
                    'page' => $banner['page'],
                    'title' => $banner['title'],
                ],
                [
                    'image' => $banner['image'],
                ]
            );
        }
    }
}
