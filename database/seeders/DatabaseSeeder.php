<?php

namespace Database\Seeders;

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
//        \App\Models\User::factory(1)->create();
        $this->call([
            RegionsTableSeeder::class,
            DistrictsTableSeeder::class,
            ConstituenciesTableSeeder::class,
            PartiesTableSeeder::class,
            CandidatesTableSeeder::class,
            StationsTableSeeder::class,

        ]);
    }
}
