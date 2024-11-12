<?php

namespace App\Services;

use App\Models\Queue;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use WPPConnectTeam\Wppconnect\Facades\Wppconnect;

class QueueService
{
    public function getLastQueue()
    {
        // Retrieve the service and its prefix code
        // $service = Service::find($service_id);
        // $prefix = $service->code ?? ''; // Use the code column for prefix, default to empty string if null

        // Get today's date
        $today = Carbon::today();

        // Get the last queue for today, specific to this service, ordered by number descending
        $lastQueue = Queue::whereDate('date', $today)
                    // ->where('service_id', $service_id)
                    ->orderBy('number', 'desc')
                    ->first();

        // Determine the next queue number
        // $nextQueueNumber = $lastQueue ? $lastQueue->number + 1 : 1;
        return $lastQueue ? $lastQueue->number + 1 : 1;// returns the last queue of today

        // Return the prefixed queue number
        // return $prefix . $nextQueueNumber;
    }

}

