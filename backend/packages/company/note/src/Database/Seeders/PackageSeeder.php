<?php

namespace Company\Note\Database\Seeders;

use Illuminate\Database\Seeder;
use Company\Note\Models\Category;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->categories();
    }

    private function categories()
    {
        $categories = [
            ['name' => 'Business', 'ordering' => 0],
            ['name' => 'Education', 'ordering' => 1],
            ['name' => 'Entertainment', 'ordering' => 2],
            ['name' => 'Family & Home', 'ordering' => 3],
            ['name' => 'Health', 'ordering' => 4],
            ['name' => 'Recreation', 'ordering' => 5],
            ['name' => 'Shopping', 'ordering' => 6],
            ['name' => 'Society', 'ordering' => 7],
            ['name' => 'Sports', 'ordering' => 8],
            ['name' => 'Technology', 'ordering' => 9],
        ];

        Category::insert($categories);
    }
}
