<?php

namespace App\Http\Controllers;

use App\Services\AuthLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogListController extends Controller
{
    public function index()
    {
        // Obtener logs del usuario autenticado únicamente
        $user = Auth::user();
        $logs = AuthLogService::getUserFormattedLogs($user->id, 100);

        return view('logs', compact('logs'));
    }
}
