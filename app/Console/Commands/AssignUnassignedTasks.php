<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;

class AssignUnassignedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-unassigned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unassignedTasks = Task::whereDoesntHave('assignees')->get();
        $availableUsers = User::where('status', '!=', 'on_vacation')->get();

        if ($availableUsers->isEmpty()) {
            logger()->info('Нет доступных пользователей для назначения задач');
            return 0;
        }

        foreach ($unassignedTasks as $task) {
            $randomUser = $availableUsers->random();
            $task->assignees()->attach($randomUser->id);

            logger()->info("Задача #{$task->id} назначена на пользователя #{$randomUser->id}");
        }

        logger()->info("Назначено задач: {$unassignedTasks->count()}");
        return 0;
    }
}
