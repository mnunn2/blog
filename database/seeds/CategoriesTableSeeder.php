<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        DB::table('categories')->insert([
            ['title' => 'shop', 'description' => 'this is a shop', 'parentId' => 0, 'lft' => 1, 'rgt' => 2, 'depth' => 0, 'rootId' => 5, 'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s') ]
        ]);
    }
}
