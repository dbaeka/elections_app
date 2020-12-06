<?php

namespace Database\Seeders;


use App\Models\PMCandidate;
use App\Utilities\LargeCSVReader;
use Illuminate\Database\Seeder;

class PMCandidatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $file = database_path('seeders/defaults/pm_candidates.csv');
        $csv_reader = new LargeCSVReader($file,",");

        $cur_time = now();

        foreach ($csv_reader->csvToArray() as $data) {
            // Preprocessing of the array.
            foreach ($data as $key => $entry) {
                // Laravel doesn't add timestamps on its own when inserting in chunks.
                $data[$key]['created_at'] = $cur_time;
                $data[$key]['updated_at'] = $cur_time;
            }
            PMCandidate::insert($data);
        }
    }
}
