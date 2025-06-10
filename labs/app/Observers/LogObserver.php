<?php

namespace App\Observers;

use App\Models\ChangeLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\DTOs\ChangeLogsDTO;

class LogObserver
{
    public function logChange(Model $model, ?array $before, ?array $after){
        $changeLogDTO = new ChangeLogsDTO(
            get_class($model),
            $model->getKey(),
            $before,
            $after
        );

        ChangeLogs::create([
            'entity_type' => $changeLogDTO->entity_type,
            'entity_id' => $changeLogDTO->entity_id,
            'before' => json_encode($changeLogDTO->before),
            'after' => json_encode($changeLogDTO->after),
        ]);

    }

    public function created(Model $model){
        $this->logChange($model, null, $model->toArray());
    }

    public function updated(Model $model){
        $before = $model->getOriginal();
        $after = $model->getAttributes();

        $this->logChange($model, $before, $after);
    }
}
