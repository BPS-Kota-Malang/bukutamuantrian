<?php

namespace App\Services;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use WPPConnectTeam\Wppconnect\Facades\Wppconnect;

class QueueService
{
    public function getLastQueue()
    {
        // Get today's date
        $today = Carbon::today();

        // Get the last queue for today, ordered by number descending
        $lastQueue = Queue::whereDate('date', $today)
                    ->orderBy('number', 'desc')
                    ->first();

        return $lastQueue ? $lastQueue->number + 1 : 1;// returns the last queue of today
    }

    public function sendQueue($customerPhoneNumber, $queueNumber)
    {
        try {
            $wpp = new Wppconnect([
                'base_url' => 'https://your-wppconnect-server-url',
                'token' => 'your-api-token-here',
            ]);

            $message = "Hello, your queue number is {$queueNumber}. Please be ready.";

            $response = $wpp->sendText([
                'phone' => $customerPhoneNumber,
                'message' => $message,
            ]);

            if ($response['status'] === 'success') {
                return 'WhatsApp message sent successfully.';
            } else {
                return 'Failed to send WhatsApp message: ' . $response['message'];
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp message: ' . $e->getMessage());
            return 'Error sending WhatsApp message: ' . $e->getMessage();
        }
    }

}

