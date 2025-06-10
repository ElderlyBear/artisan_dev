<?php

namespace App\Jobs;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        Log::info("start work job");

        $start = now()->subHours(env('REPORT_TIME_INTERVAL_HOURS', 24)); //  временной интервал ссбора данных


        $methodState = DB::table('logs_requests')->where('created_at', '>', $start)
            ->select('method', DB::raw('count(*) as count'), DB::raw('MAX(created_at) as last_used'))
            ->groupBy('method')
            ->get();

        $entityState = DB::table('change_logs')->where('created_at', '>', $start)
            ->select('entity_id', DB::raw('count(*) as count'))
            ->groupBy('entity_id')
            ->get();

        $usersState = DB::table('logs_requests')->where('created_at', '>', $start)
            ->select('identifier', DB::raw('count(*) as total'), DB::raw('MAX(created_at) as last_used'))
            ->groupBy('identifier')
            ->get();

        $repeat = env('REPORT_REPEAT_SIZE', 1);


        for ($i = 0; $i < $repeat; $i++) {
            $path = storage_path('app/report_' . now()->format('Ymd_His') . '.docx');
            $this->generateDocxReport($methodState, $entityState, $usersState, $path);
            if($i < $repeat) {
                sleep(env('REPORT_REPEAT_TIMEOUT_MINUTES', 1) * 60);
            }
        }


        Log::info("Отчет сгенерирован");

        Log::info("Ответ отправлен администраторам:", ["path" => $path]);

        Log::info("end work job");

//        unlink($path); // удаление
    }

    private function generateDocxReport($methodStats, $entityStats, $usersState, $filePath)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('Отчет по системе', ['bold' => true, 'size' => 16]);
        $section->addText('Актуальность: ' . now()->format('Y-m-d H:i'));

        $section->addTextBreak(1);
        $section->addText('Рейтинг вызываемых методов:');
        foreach ($methodStats as $item) {
            $section->addText("Метод: {$item->method} | Кол-во: {$item->count} | Последнее использование: {$item->last_used}");
        }

        $section->addTextBreak(1);
        $section->addText('Рейтинг редактируемых сущностей:');
        foreach ($entityStats as $item) {
            $section->addText("Сущность: {$item->entity_id} | Изменений: {$item->count}");
        }

        $section->addTextBreak(1);
        $section->addText('Рейтинг пользователей:');
        foreach ($usersState as $item) {
            $section->addText("Пользователь: {$item->identifier} | Всего: {$item->total} | Последнее действие: {$item->last_used}");
        }

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filePath);
    }
}
