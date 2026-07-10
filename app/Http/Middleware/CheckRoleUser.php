<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Get access token from cookie
        $encodedData = $_COOKIE['access_token'] ?? null;
        $cob = $request->segment(1);
        $stat = $request->segment(2);
        $reqId = $request->route('reqId') ?? null;

        if (!$encodedData) {
            return redirect('/');
        }

        // Call the helper function to decrypt the token
        $decryptedData = decryptToken($encodedData);

        // Decode the JSON response
        $decryptedDataArray = json_decode($decryptedData, true);

        // Check if 'verified' field exists and is true
        if (isset($decryptedDataArray['verified']) && $decryptedDataArray['verified'] === true) {

            // Extract and decode the 'data' field
            $dataField = $decryptedDataArray['data'] ?? null;
            if ($dataField) {
                $dataArray = json_decode($dataField, true);
                // Extract the user_info from the decoded data
                $userInfo = $dataArray['user_info'][1] ?? null;

                if ($userInfo && isset($userInfo['role'])) {
                    $roles = $userInfo['role']; // Array of roles
                    // Check if the provided role is in the array of roles
                    if (!in_array($role, $roles)) {
                        return redirect($cob.'/'.$stat.'/detail');
                    }

                    return $next($request);
                }
            }
        }

        // Redirect if any validation fails
        return redirect('/');
    }
}
