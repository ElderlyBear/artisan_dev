<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeLogs;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class GitWebHookController extends Controller
{
    public function hooks(Request $request)
    {
        $input_key = $request->input('key');
        $realKey = env('SECRET_KEY');

        if ($input_key !== $realKey) {
            return response()->json(['error' => 'wrong key'], 401);
        }

        Log::info("webhook called", [
            "ip" => $request->ip(),
            "time" => now()->toDateTimeString()
        ]);

        // Укажите путь к git.exe на вашей системе
        $gitPath = '"C:/Program Files/Git/bin/git.exe"'; // Обязательно в кавычках!

        $commands = [
            [$gitPath, 'checkout', 'master'],
            [$gitPath, 'reset', '--hard'],
            [$gitPath, 'pull']
        ];

        foreach ($commands as $command) {
            $process = new Process(array_merge($command)); // объединяем как массив
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Git command failed', [
                    'command' => implode(' ', $command),
                    'output' => $process->getErrorOutput()
                ]);

                return response()->json([
                    'error' => $process->getErrorOutput()
                ], 500);
            }

            Log::info('Git command executed successfully', [
                'command' => implode(' ', $command),
                'output' => $process->getOutput()
            ]);
        }

        return response()->json(['message' => 'Update successful'], 200);
    }
}