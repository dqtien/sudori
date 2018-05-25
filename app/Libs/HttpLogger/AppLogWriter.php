<?php

namespace App\Libs\HttpLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Spatie\HttpLogger\LogWriter;

class AppLogWriter implements LogWriter
{
    public function logRequest(Request $request)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $files = array_map(function (UploadedFile $file) {
            return $file->path();
        }, iterator_to_array($request->files));

        $message = "{$method} {$uri} - Body: {$bodyAsJson} - Files: ".implode(', ', $files);

        // Set log handler for logging request
        $monolog = Log::getMonolog();
        $defaultHandlers = $monolog->getHandlers();
        $monolog->setHandlers([]);
        Log::useDailyFiles(config('http-logger.log_path_file'), 0, 'info');
        Log::info($message);

        // Reset log handler
        $monolog->setHandlers($defaultHandlers);
    }
}
