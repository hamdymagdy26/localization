<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LocalizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('localizations')->insert([
            [
                'title' => 'العربية',
                'code' => 'ar',
                'active' => true,
                'default' => 1
            ],
            [
                'title' => 'English',
                'code' => 'en',
                'active' => true,
                'default' => 0
            ]
        ]);
    }
}