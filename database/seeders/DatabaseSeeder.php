<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * This is the main entry point that calls dedicated seeders
     * in the correct order to satisfy foreign key constraints.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PartnerSeeder::class,
            BoxTemplateSeeder::class,
            ShopSessionSeeder::class,
            BoxOrderSeeder::class,
        ]);
    }
}
