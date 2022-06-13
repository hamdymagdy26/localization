<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            [
                "logo" => "test.jpg"
            ],
            [
                "logo" => "test2.jpg"
            ]
        ]);

        DB::table('project_translations')->insert([
            [
                "project_id" => 1,
                "name" => "project1",
                "locale" => "en"
            ],
            [
                "project_id" => 1,
                "name" => "مشروع1",
                "locale" => "ar"
            ],
            [
                "project_id" => 2,
                "name" => "project2",
                "locale" => "en"
            ],
            [
                "project_id" => 2,
                "name" => "مشروع2",
                "locale" => "ar"
            ]
        ]);
    }
}