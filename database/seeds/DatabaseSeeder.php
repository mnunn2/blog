<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(ArticlesTableSeeder::class);
        $this->call('CategoriesTableSeeder');
        $this->command->info("Categories table seeded :)");
    }
}
