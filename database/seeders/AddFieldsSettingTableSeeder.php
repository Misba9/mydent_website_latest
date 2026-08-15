<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AddFieldsSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'terms_conditions'],
            ['value' => '<p>Terms and conditions will be updated soon.</p>']
        );

        Setting::firstOrCreate(
            ['key' => 'privacy_policy'],
            ['value' => '<p>Privacy policy will be updated soon.</p>']
        );
    }
}
