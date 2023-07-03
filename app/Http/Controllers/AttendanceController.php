<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
class AttendanceController extends Controller
{
    public function start(Request $request)
    {
        $attendance = new Attendance;
        $attendance->ip_address = $request->ip();
        $attendance->email = $request->input('email');
        $attendance->time_start = now();
        $attendance->save();

        return response()->json(['message' => 'Attendance started successfully.']);
    }


    public function end(Request $request)
    {
        $attendance = Attendance::where('ip_address', $request->ip())
        ->where('email', $request->input('email'))
        ->whereNull('time_end')
        ->firstOrFail();

        $attendance->time_end = now();
        $attendance->save();

        // Calculate total hours worked
        $startTime = Carbon::parse($attendance->time_start);
        $endTime = Carbon::parse($attendance->time_end);
        $totalHours = $startTime->diffInHours($endTime);

        // Update attendance status based on total hours worked
        $attendance->total_hours = $totalHours;
        if ($totalHours < 3) {
            $attendance->status = 'Absent';
        } elseif ($totalHours >= 3 && $totalHours < 5) {
            $attendance->status = 'Half Day';
        } else {
            $attendance->status = 'Working Day Complete';
        }
        $attendance->save();

        return response()->json(['message' => 'Attendance ended successfully.']);
    }



    public function calculate(Request $request)
    {
        $ipAddress = $request->ip();

        $attendance = Attendance::where('ip_address', $request->ip())
        ->whereNotNull('time_start')
        ->whereNotNull('time_end')
        ->firstOrFail();

        $startTime = Carbon::parse($attendance->time_start);
        $endTime = Carbon::parse($attendance->time_end);

        $totalHours = $startTime->diffInHours($endTime);

        return response()->json(['total_time' => $totalHours]);
    }


public function checkWorkingHours(Request $request)
{
    $ipAddress = $request->ip();
    $todayDate = Carbon::now()->format('Y-m-d');

    $totalHours = Attendance::where('ip_address', $ipAddress)
    ->where('status', 'Working Day Complete')
    ->whereDate('time_start', '=', $todayDate)
    ->sum('total_hours');


    $workingHoursCompleted = $totalHours >= 7.5;

    return response()->json(['working_hours_completed' => $workingHoursCompleted]);
}


}
