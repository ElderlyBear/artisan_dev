<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DTOs\infoDTO;
use App\DTOs\databaseDTO;
use App\DTOs\clientDTO;

class Info extends Controller
{
    public function serverInfo(){
        $php = phpinfo();
        $dto = new infoDTO($php);

        return response()->json($dto);
    }

    public function dataBaseInfo(){
        $database = [
            'title' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 8889,
            'name' => 'server_dev'
        ];

        $dto = new databaseDTO($database);

        return response()->json($dto);

    }

    public function clientInfo(Request $request){
        $user_ip = $request->ip();
        $user_agent = $request->header('User-Agent');

        return response()->json(['ip' => $user_ip, 'agent' => $user_agent]);
    }
}
