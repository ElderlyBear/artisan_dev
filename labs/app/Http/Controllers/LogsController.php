<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogRequest;


class LogsController extends Controller
{
    public function requests(Request $request){
        $fields = ['address', 'controller_method', 'status', 'created_at'];

        $logs = LogRequest::all()->map(function ($log) use ($fields) {
            return $log->only($fields);
        });

        return response()->json($logs);
    }

    public function request(Request $request, $log_id){
        $log = LogRequest::find($log_id);

        if(!$log){
            return response()->json(['message' => 'Log not found'], 404);
        }

        return response()->json($log);

    }

    public function destroy(Request $request, $log_id){
        $log = LogRequest::find($log_id);

        if(!$log){
            return response()->json(['message' => 'Log not found'], 404);
        }

        $log->delete();

        return response()->json(['message' => 'Log deleted'], 200);
    }
}
