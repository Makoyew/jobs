<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index()
    {
        $logEntries = Log::orderBy('created_at', 'desc')->get();


        $logEntries->transform(function ($logEntry) {
            $logEntry->formattedCreatedAt = Carbon::parse($logEntry->created_at)->format('F-d-Y');
            return $logEntry;
        });

        return view('Logs', ['logEntries' => $logEntries]);
    }

    public function clearAllLogs()
    {

        Log::truncate();

        return redirect('logs')->with('success', 'All logs have been cleared.');
    }

    public function destroy($id)
    {
        $logEntry = Log::find($id);

        if (!$logEntry) {
            return redirect('logs')->with('error', 'Log entry not found');
        }

        $logEntry->delete();

        return redirect('logs')->with('success', 'Log entry deleted successfully');
    }
}
