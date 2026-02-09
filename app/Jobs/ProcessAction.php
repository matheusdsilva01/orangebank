<?php

namespace App\Jobs;

use App\Dto\ActionDTO;
use App\Models\Action;
use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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

        $goal = Goal::query()->where('attributes', '=',
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
                    'entity_type' => $this->actionDTO->entity->getMorphClass(),
                    'goal_id' => $goal->id,
                    'progress' => 1,
                    'completed' => 1 >= $goal->threshold,
                ]);
            } else {
                $progress->increment('progress');
                if (!$progress->completed && $progress->progress >= $goal->threshold) {
                    $progress->update(['completed' => true]);
                }
            }
        }
    }
}
