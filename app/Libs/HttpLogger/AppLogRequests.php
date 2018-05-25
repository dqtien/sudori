<?php

namespace App\Libs\HttpLogger;

use Spatie\HttpLogger\LogProfile;
use Illuminate\Http\Request;

class AppLogRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete', 'get']);
    }
}
