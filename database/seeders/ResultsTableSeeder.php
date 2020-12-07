<?php

namespace Database\Seeders;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Models\Result;
use App\Models\Station;
use App\Utilities\LargeCSVReader;
use Illuminate\Database\Seeder;
use Kreait\Firebase\Messaging;

class ResultsTableSeeder extends Seeder
{

    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $file = database_path('seeders/defaults/results.csv');
        $csv_reader = new LargeCSVReader($file, ",");
        $cur_time = now();

        foreach ($csv_reader->csvToArray() as $data) {
            // Preprocessing of the array.
            foreach ($data as $key => $entry) {
                // Laravel doesn't add timestamps on its own when inserting in chunks.
                $records = [
                    "1" => intval($data[$key]['npp']),
                    "2" => intval($data[$key]['ndc']),
                ];
                unset($data[$key]['npp']);
                unset($data[$key]['ndc']);
                $others = $data[$key]['others'];
                if (empty($others)){
                    $data[$key]['others'] = null;
                }
                $data[$key]['records'] = json_encode($records);
                $data[$key]['created_at'] = $cur_time;
                $data[$key]['updated_at'] = $cur_time;
                $data[$key]['is_latest'] = true;
                $data[$key]['media_checked'] = false;
                $data[$key]['is_approved'] = false;
//                $station_key = array_keys($data[$key])[0];
                $station_code = $data[$key]['station_code'];
//                $data[$key]['station_code'] = $station_code;
//                unset($data[$key][$station_key]);
                $constituency_id = Station::where('code', $station_code)->value('constituency_id');
                $data[$key]['constituency_id'] = $constituency_id;
                Result::where('station_code', $station_code)->where('is_latest', true)->update(['is_latest' => false]);
            }
            Result::insert($data);
        }
        // Push event notification to firebase
        $deviceTokens = User::where('role', 'engine')->orWhere('role', 'display')->pluck('fcm_token')->all();
        $this->deliverMessage("added_results", $deviceTokens);
    }

    private function deliverMessage($message, $deviceTokens)
    {
        $message = CloudMessage::new()->withData([
            'message' => $message,
//            'data' => $result
        ]);
        try {
            $sendReport = $this->messaging->sendMulticast($message, $deviceTokens);
//            echo 'Successful sends: ' . $sendReport->successes()->count() . PHP_EOL;
//            echo 'Failed sends: ' . $sendReport->failures()->count() . PHP_EOL;
//            if ($sendReport->hasFailures()) {
////                foreach ($sendReport->failures()->getItems() as $failure) {
//                $result["fcm_error_msg"] = "Some errors encountered during delivery"; // $failure->error()->getMessage();
////                }
//            } else {
//                $result["fcm_status_msg"] = "Successfully delivered notifications";
//            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
