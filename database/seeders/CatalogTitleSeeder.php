<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CatalogTitle;

class CatalogTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = ['C1', 'C2', 'C3'];
        foreach ($titles as $title) {
            CatalogTitle::insert([
                'title'=>$title,
                'created_at'=> now(),
                'updated_at'=> now()
            ]);
        }
    }
}
