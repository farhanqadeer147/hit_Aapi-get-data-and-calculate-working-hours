<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class APIController extends Controller
{
    public function calculateHours(Request $request)
    {
        // Create a new Guzzle HTTP client
        $client = new Client();

        // Send a GET request to the API endpoint
        $url = "https://champagne-bandicoot-hem.cyclic.app/api/data";
        $response = $client->get($url);

        // Check if the response status code is successful
        if ($response->getStatusCode() === 200) {
            // Retrieve the response body and parse it as JSON
            $data = json_decode($response->getBody(), true);

            // Check if the API response is valid and contains data
            if (is_array($data) && isset($data['data']) && is_array($data['data'])) {
                $employeeAttendance = $data['data'];

                $totalTimes = [];

                foreach ($employeeAttendance as $attendance) {
                    $email = $attendance['email'];
                    $totalTime = $attendance['total_time'];

                    if (!isset($totalTimes[$email])) {
                        $totalTimes[$email] = 0;
                    }

                    // Check if total_time is a valid time value
                    if ($totalTime !== null && preg_match('/^\d{2}:\d{2}$/', $totalTime)) {
                        // Convert total time to minutes (assuming the format is HH:MM)
                        $timeParts = explode(':', $totalTime);
                        $hours = intval($timeParts[0]);
                        $minutes = intval(ltrim($timeParts[1], '0'));
                        $totalMinutes = $hours * 60 + $minutes;

                        $totalTimes[$email] += $totalMinutes;
                    }
                }

                // Prepare the result array
                $results = [];
                foreach ($totalTimes as $email => $totalMinutes) {
                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;

                    $result = [
                        'email' => $email,
                        'total_working_hours' => $hours,
                        'total_working_minutes' => $minutes,
                    ];

                    $results[] = $result;
                }

                if (empty($results)) {
                    return response()->json(['message' => 'No records found']);
                } else {
                    // Return the results as a JSON response
                    return response()->json($results);
                }
            } else {
                return response()->json(['message' => 'Invalid API response']);
            }
        } else {
            return response()->json(['message' => 'Failed to retrieve data from the API']);
        }
    }
}
