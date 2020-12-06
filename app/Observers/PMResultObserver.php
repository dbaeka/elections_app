<?php

namespace App\Observers;

use App\Models\PMResult;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging;

class PMResultObserver
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }


    /**
     * Handle the Result "created" event.
     *
     * @param \App\Models\PMResult $result
     * @return void
     */
    public function created(PMResult $result)
    {
        //
        // Reset previous approved

        $user_id = request()->user()->id;
        PMResult::where('user_id', $user_id)->where('is_latest', true)->update(['is_latest' => false]);
        $result->is_latest = true;
        $result->save();
        $deviceTokens = User::where('role', 'engine')->orWhere('role', 'display')->pluck('fcm_token')->all();
        $this->deliverMessage("created_result", $result, $deviceTokens);
    }

    /**
     * Handle the Result "updated" event.
     *
     * @param \App\Models\PMResult $result
     * @return void
     */
    public function updated(PMResult $result, $is_approve = null)
    {
        //
        $deviceTokens = $is_approve ?
            User::where('role', 'display')->orWhere('role', 'admin')->pluck('fcm_token')->all()
            :
            User::where('role', 'engine')->orWhere('role', 'admin')->pluck('fcm_token')->all();
        $message = $is_approve ? 'updated_is_approved' : 'updated_results';
        $this->deliverMessage($message, $result, $deviceTokens);
    }


    private function deliverMessage($message, $result, $deviceTokens)
    {
        $message = CloudMessage::new()->withData([
            'message' => $message,
//            'data' => $result
        ]);
        try {
            $sendReport = $this->messaging->sendMulticast($message, $deviceTokens);
//            echo 'Successful sends: ' . $sendReport->successes()->count() . PHP_EOL;
//            echo 'Failed sends: ' . $sendReport->failures()->count() . PHP_EOL;
            if ($sendReport->hasFailures()) {
//                foreach ($sendReport->failures()->getItems() as $failure) {
                $result["fcm_error_msg"] = "Some errors encountered during delivery"; // $failure->error()->getMessage();
//                }
            } else {
                $result["fcm_status_msg"] = "Successfully delivered notifications";
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

}
