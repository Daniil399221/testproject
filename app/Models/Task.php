<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => TaskStatus::class,
    ];


    #Нужна доработка реализации, еще не доделал
    protected static function booted()
    {
        static::updated(function ($task) {
            if ($task->isDirty('status') &&
                in_array($task->status, [TaskStatus::IN_PROGRESS, TaskStatus::DONE])) {

                $statusName = $task->status->value;
                $message = "Задача #{$task->id} была переведена в статус {$statusName}";

                foreach ($task->assignees as $user) {
                    $user->notifications()->create([
                        'message' => $message,
                        'read' => false,
                    ]);

                    logger()->info("Отправлено уведомление пользователю #{$user->id}: {$message}");
                }
            }
        });
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class
        );
    }
}
