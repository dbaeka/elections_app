<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use App\Utilities\LargeCSVReader;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $file = database_path('seeders/defaults/regions.csv');
        $csv_reader = new LargeCSVReader($file);

        $cur_time = now();

        foreach ($csv_reader->csvToArray() as $data) {
            // Preprocessing of the array.
            foreach ($data as $key => $entry) {
                // Laravel doesn't add timestamps on its own when inserting in chunks.
                $data[$key]['created_at'] = $cur_time;
                $data[$key]['updated_at'] = $cur_time;
            }
            Region::insert($data);
        }
    }
}
