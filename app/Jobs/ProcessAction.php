<?php

namespace App\Jobs;

use App\Dto\ActionDTO;
use App\Models\Action;
use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use function _PHPStan_e7eb9612d\React\Async\delay;

class ProcessAction implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ActionDTO $actionDTO,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Action::create([
            'attributes' => $this->actionDTO->attributes,
            'entity_type' => $this->actionDTO->entity->getMorphClass(),
            'entity_id' => $this->actionDTO->entity->getKey(),
        ]);

        $goal = Goal::query()->whereRaw('attributes = ?::jsonb',
            json_encode(
                $this->actionDTO->attributes,
            ))->first();
        if (!is_null($goal)) {
            $progress = GoalProgress::where([
                'entity_id' => $this->actionDTO->entity->getKey(),
                'goal_id' => $goal->id,
            ])->first();
            if (is_null($progress)) {
                GoalProgress::create([
                    'entity_id' => $this->actionDTO->entity->getKey(),
                    'goal_id' => $goal->id,
                    'progress' => 1,
                ]);
            } else {
                $progress->increment('progress');
            }
        }
    }
}
