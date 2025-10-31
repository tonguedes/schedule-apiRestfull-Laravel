<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $service) {}

    public function index(Request $request)
    {
        $notifications = $this->service->listByUser($request->user()->id);
        return response()->json($notifications);
    }
}
