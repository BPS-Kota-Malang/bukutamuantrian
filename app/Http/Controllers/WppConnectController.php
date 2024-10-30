<?php

namespace App\Http\Controllers;

use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WppConnectController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function checkConnectionState()
    {
        return $this->whatsappService->checkConnectionState();
    }

    public function createSession()
    {
        try {
            $result = $this->whatsappService->createSession();

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error creating session: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
